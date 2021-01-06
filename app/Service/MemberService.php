<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Model\UserInfo;
use App\Service\DAO\IpBlackListDAO;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\PaymentDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserCreditRecordDAO;
use App\Service\DAO\UserInfoDAO;
use App\Service\DAO\UserLevelDAO;
use App\Service\DAO\UserMemberDAO;
use App\Service\DAO\UserNotifyDAO;
use App\Service\DAO\UserRechargeDAO;
use App\Service\DAO\UserRelationDAO;

use Carbon\Carbon;
use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;

/**
 * 会员服务
 *
 * @author  
 * @package App\Service
 */
class MemberService extends Base
{
    /**
     * 充值操作
     *
     * @param int    $user_id
     * @param int    $level
     * @param int    $credit
     * @param string $recharge_remark
     * @param string $credit_remark
     */
    public function recharge(int $user_id, int $level, int $credit, string $recharge_remark, string $credit_remark)
    {
        // 查找用户
        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);
        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        // 获取会员等级
        $user_level = $this->container->get(UserLevelDAO::class)->findByLevel($level);
        if (!$user_level) {
            $this->error('logic.LEVEL_NOT_FOUND');
        }

        // 进行添加操作
        Db::beginTransaction();
        try {
            $now = time();

            // 进行修改等级操作
            if ($level > 0 && $user->level !== $level) {
                // 添加支付记录
                $payment = $this->container->get(PaymentDAO::class)->create([
                    'user_id' => $user_id,
                    'pay_no'  => 'admin' . date('YmdHis') . mt_rand(1000, 9999),
                    'amount'  => $user_level->price,
                    'type'    => 1,
                    'channel' => 'admin',
                    'status'  => 2
                ]);

                // 添加充值账单
                $this->container->get(UserRechargeDAO::class)->create([
                    'user_id'       => $user_id,
                    'level'         => $level,
                    'balance'       => $user_level->price,
                    'payment_id'    => $payment->id,
                    'recharge_time' => $now,
                    'channel'       => 2,
                    'admin_id'      => JwtInstance::instance()->build()->getId(),
                    'remark'        => $recharge_remark,
                    'status'        => 1
                ]);

                // 修改用户等级
                $user->level = $level;
                $user->save();

                // 添加系统通知
                $this->container->get(UserNotifyDAO::class)->create([
                    'type'    => 1,
                    'user_id' => $user_id,
                    'title'   => '系统通知',
                    'content' => '恭喜您，您的VIP等级已升级为' . $user_level->name
                ]);
            }

            if ($credit > 0) {
                // 记录信用分变动
                $this->container->get(UserCreditRecordDAO::class)->create([
                    'user_id' => $user_id,
                    'type'    => '后台充值',
                    'credit'  => 2,
                    'remark'  => $credit_remark,
                ]);

                // 增加信用分
                $user->increment('credit', $credit);
            }

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('recharge')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     * 修改用户余额
     *
     * @param int    $user_id
     * @param float  $amount
     * @param string $remark
     * @param int    $type
     */
    public function changeUserBalance(int $user_id, float $amount, string $remark, int $type)
    {
        // 查找用户
        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);

        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        // 金额为空不进行下面操作
        if (empty($amount))
            return;

        // 判断减少余额
        if ($user->balance < abs($amount) && $amount < 0) {
            $this->error('logic.USER_BALANCE_NOT_ENOUGH');
        }

        Db::beginTransaction();
        try {
            if ($type === 6) {
                $bill_type = ($amount > 0) ? 6 : 7;
            }
            else {
                $bill_type = 11;
            }

            // 创建用户账号账单
            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $user_id,
                'type'           => $bill_type,
                'balance'        => $amount,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance + $amount,
                'remark'         => $remark
            ]);

            // 修改用户余额
            $user->increment('balance', $amount);
            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('recharge')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     * 修改用户积分
     *
     * @param int    $user_id
     * @param int    $credit
     * @param string $remark
     */
    public function changeUserCredit(int $user_id, int $credit, string $remark)
    {
        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);

        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        if ($credit === 0)
            return;

        // var_dump('用户信用分为:' . $user->credit);

        if ($credit < 0 && abs($credit) > $user->credit) {
            $this->error('用户信用分不足');
        }

        Db::beginTransaction();
        try {
            $credit_type = ($credit > 0) ? '后台充值' : '后台扣除';

            $this->container->get(UserCreditRecordDAO::class)->create([
                'user_id' => $user_id,
                'type'    => $credit_type,
                'credit'  => $credit,
                'remark'  => $remark
            ]);

            // 修改用户信用分
            $user->increment('credit', $credit);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('recharge')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    /*public function changeUserParentId(int $user_id, int $parent_id)
    {
        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);

        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

//        $lowers_id = $this->container->get(UserRelation)
    }*/

    /**
     * 获取用户团队
     *
     * @param int $user_id
     *
     * @return array
     */
    public function getUserTeam(int $user_id)
    {
        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);

        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        $lowers = $this->container->get(UserRelationDAO::class)->getLowersByParentId($user_id)->toArray();

        $level_one_data = array_values(array_filter($lowers, function ($low) {
            return $low['level'] === 1;
        }));

        $level_two_data = array_values(array_filter($lowers, function ($low) {
            return $low['level'] === 2;
        }));

        $level_three_data = array_values(array_filter($lowers, function ($low) {
            return $low['level'] === 3;
        }));

        return [
            'level_one'   => $level_one_data,
            'level_two'   => $level_two_data,
            'level_three' => $level_three_data,
        ];
    }

    public function create(array $params)
    {
        // 判断邀请码
        if (!$this->container->get(MemberDAO::class)->checkValueIsUsed('id', (int)$params['parent_id'])) {
            $this->error('logic.PARENT_ID_ERROR');
        }

        // 判断手机号是否存在
        if ($this->container->get(MemberDAO::class)->checkValueIsUsed('phone', $params['phone'])) {
            $this->error('logic.PHONE_USED');
        }

        // 判断账号
        /* if ($this->container->get(MemberDAO::class)->checkValueIsUsed('account', $params['account'])) {
            $this->error('logic.USERNAME_USED');
        } */

        // 判断ip
        $ip = $this->container->get(RequestInterface::class)->getHeaderLine('X-Real-IP');
        Db::beginTransaction();
        try {
            $credit = 300;

            $user = $this->container->get(MemberDAO::class)->create([
                'country_id'     => (int)$params['country_id'],
                'parent_id'      => (int)$params['parent_id'],
                // 'account' => $params['account'],
                'password'       => password_hash($params['password'], PASSWORD_DEFAULT),
                'phone'          => $params['phone'],
                'nickname'       => $params['phone'],
                'credit'         => $credit,
                'ip'             => $ip,
                'type'           => $params['type'] ?? 0,
            ]);

            // 添加用户积分记录
            $this->container->get(UserCreditRecordDAO::class)->create([
                'user_id' => $user->id,
                'type'    => 1,
                'credit'  => $credit
            ]);

            $users = $this->container->get(MemberDAO::class)->getAllUsers()->toArray();

            // 添加用户关系
            $this->container->get(UserRelationService::class)->register($user->id, $user->parent_id, $users);

            // 添加用户信息
            $this->container->get(UserInfoDAO::class)->create([
                'user_id' => $user->id
            ]);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    public function setUpUserAgent(int $id)
    {
        $user = $this->container->get(MemberDAO::class)->findUserById($id);

        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        $user->type = 1;
        $user->save();
    }

    public function cancelUserAgent(int $id)
    {
        $user = $this->container->get(MemberDAO::class)->findUserById($id);

        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        $user->type = 0;
        $user->save();
    }

    public function update(array $params)
    {
        $user = $this->container->get(MemberDAO::class)->first((int)$params['id']);

        if (isset($params['country_id']) && $params['country_id'] !== '') {
            $user->country_id = $params['country_id'];
        }

        if (isset($params['type'])) {
            $user->type = $params['type'];
        }

        if (!empty($params['password'])) {
            $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
        }

        if (!empty($params['trade_pass'])) {
            $user->trade_pass = password_hash($params['trade_pass'], PASSWORD_DEFAULT);
        }

        if (!empty($params['phone'])) {
            // 检查用户手机号
            if ($this->container->get(MemberDAO::class)->checkValueIsUsedExpectUserId('phone', $params['phone'], $user->id)) {
                $this->formError([
                    'phone' => 'logic.PHONE_USED'
                ]);
            }
            $user->phone = $params['phone'];
        }

        $user_info_data = [];
        $user_info_data['bank_name'] = $params['user_info']['bank_name'];
        $user_info_data['name'] = $params['user_info']['name'];
        if (!empty($params['user_info']['account'])) {
            if ($this->container->get(UserInfoDAO::class)->checkColumnExisted($user->id, 'account', $params['user_info']['account'])) {
                $this->error('logic.BANK_ACCOUNT_EXIST');
            }
        }
        $user_info_data['account'] = $params['user_info']['account'];
        $user_info_data['bank_code'] = $params['user_info']['bank_code'];
        $user_info_data['ifsc'] = $params['user_info']['ifsc'];
        $user_info_data['upi'] = $params['user_info']['upi'];
        $user_info_data['email'] = $params['user_info']['email'];
        $user_info_data['phone'] = $params['user_info']['phone'];

        Db::beginTransaction();
        try {
            $user->save();
            if (count($user_info_data) > 0) {
                UserInfo::query()->where('user_id', $params['id'])->update($user_info_data);
            }
            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     *
     *
     * @param int $user_id
     * @param int $day
     */
    public function updateEffectiveTime(int $user_id, int $day, int $level)
    {
        // 游客不允许修改有效期
        if ($level === -1) {
            $this->error('logic.LEVEL_NOT_FOUND');
        }

        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);
        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        $user_member = $this->container->get(UserMemberDAO::class)->firstByUserIdLevel($user_id, $level);
        if (!$user_member) {
            $this->error('logic.LEVEL_NOT_FOUND');
        }

        if ($day === 0)
            return;

        $time = $day * 86400;

        Db::beginTransaction();
        try {
            if ($time < 0) {
                /* $user->level = 0;
                $user->save();

                Db::table('user')->where('id', $user_id)->decrement('effective_time', abs($time));*/
                // 扣除用户等级时间
                //                $user_member->decrement('effective_time', abs($time));
                Db::table('user_member')
                    ->where('user_id', $user_id)
                    ->where('level', $level)
                    ->decrement('effective_time', abs($time));

            }
            else {
                // Db::table('user')->where('id', $user_id)->increment('effective_time', $time);
                // 增加用户等级时间
                //                $user_member->increment('effective_time', $time);
                Db::table('user_member')
                    ->where('user_id', $user_id)
                    ->where('level', $level)
                    ->increment('effective_time', $time);
            }

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('userLevel')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     * 修改上级ID
     *
     * @param int $user_id
     * @param int $parent_id
     */
    public function changeParentId(int $user_id, int $parent_id)
    {
        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);
        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        $parent = $this->container->get(MemberDAO::class)->findUserById($parent_id);
        if (!$parent) {
            $this->error('logic.PARENT_ID_ERROR');
        }

        // 判断修改的上级是否是当前用户下级
        if ($this->container->get(UserRelationDAO::class)->existByUserIdParentId($parent_id, $user_id)) {
            $this->error('logic.PARENT_IS_SUBORDINATE_OF_CURRENT_USER');
        }

        if ($this->cache->has('change_parent_lock_' . $user_id) === true) {
            $this->error('logic.SERVER_ERROR');
        }

        Db::beginTransaction();
        try {
            // 删除原来的用户关联
            $this->container->get(UserRelationDAO::class)->deleteByUserId($user_id);

            // 修改原用户的上级ID
            $user->parent_id = $parent_id;
            $user->save();

            $users = $this->container->get(MemberDAO::class)->getAllUsers()->toArray();

            // 新增新的用户关联数据
            $this->container->get(UserRelationService::class)->register($user_id, $parent_id, $users);

            // 所有下级重新修改关联
            $lowers_ids = array_column($this->container->get(UserRelationDAO::class)->getUserLowerIds($user_id)->toArray(), 'user_id');

            $this->cache->set('change_parent_lock_' . $user_id, 1);
            go(function () use ($lowers_ids, $user_id, $users) {
                foreach ($lowers_ids as $key => $lowers_id) {
                    $lower = $this->container->get(MemberDAO::class)->first($lowers_id);

                    // 删除原来的用户关联
                    $this->container->get(UserRelationDAO::class)->deleteByUserId($lowers_id);

                    // 新增新的用户关联数据
                    $this->container->get(UserRelationService::class)->register($lowers_id, $lower->parent_id, $users);
                }

                $this->cache->delete('change_parent_lock_' . $user_id);
            });

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('changeParent')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }
}