<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Model\User;
use App\Model\UserLevel;
use App\Model\UserTask;
use App\Service\DAO\CountryDAO;
use App\Service\DAO\LanguageDAO;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\PaymentDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserCreditRecordDAO;
use App\Service\DAO\UserLevelDAO;

use App\Service\DAO\UserLevelRebateDAO;
use App\Service\DAO\UserMemberDAO;
use App\Service\DAO\UserNotifyContentDAO;
use App\Service\DAO\UserNotifyDAO;
use App\Service\DAO\UserRechargeDAO;
use Hyperf\Cache\Annotation\CacheEvict;
use Hyperf\DbConnection\Db;

/**
 * 会员等级服务
 *
 * @author  
 * @package App\Service
 */
class UserLevelService extends Base
{
    /**
     * 添加会员等级
     *
     * @param array $params
     */
    public function create(array $params)
    {
        // 判断等级是否重复
        if ($this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level'])) {
            $this->formError([
                'level' => 'logic.LEVEL_ALREADY_EXISTS'
            ]);
        }

        // 处理有效时间
        $duration = $params['day'] * 86400 + $params['hour'] * 3600 + $params['minute'] * 60;

        // 创建等级
        Db::beginTransaction();
        try {
            // 创建等级
            $level = $this->container->get(UserLevelDAO::class)->create([
                'type'        => (int)$params['type'],
                'level'       => (int)$params['level'],
                'name'        => trim($params['name']),
                'icon'        => trim($params['icon']),
                'price'       => (float)$params['price'],
                'task_num'    => (int)$params['task_num'],
                'max_buy_num' => (int)$params['max_buy_num'],
                'duration'    => $duration
            ]);

            // 创建充值奖励
            $this->container->get(UserLevelRebateDAO::class)->create([
                'level_id'       => $level->id,
                'type'           => 1,
                'p_one_rebate'   => (float)$params['recharge_p_one_rebate'],
                'p_two_rebate'   => (float)$params['recharge_p_two_rebate'],
                'p_three_rebate' => (float)$params['recharge_p_three_rebate'],
            ]);

            // 创建完成任务奖励
            $this->container->get(UserLevelRebateDAO::class)->create([
                'level_id'       => $level->id,
                'type'           => 2,
                'p_one_rebate'   => (float)($params['task_p_one_rebate'] / 100),
                'p_two_rebate'   => (float)($params['task_p_two_rebate'] / 100),
                'p_three_rebate' => (float)($params['task_p_three_rebate'] / 100),
            ]);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('userLevel')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     * 修改会员等级
     *
     * @param int   $id
     * @param array $params
     */
    public function edit(int $id, array $params)
    {
        // 查找会员等级
        $level = $this->container->get(UserLevelDAO::class)->findById($id);
        if (!$level) {
            $this->error('logic.LEVEL_NOT_FOUND');
        }

        // 判断等级是否重复
        $findLevel = $this->container->get(UserLevelDAO::class)->findByLevel((int)$params['level']);
        if ($findLevel && $findLevel->level !== (int)$params['level']) {
            $this->formError([
                'level' => 'logic.LEVEL_ALREADY_EXISTS'
            ]);
        }

        // 处理有效时间
        $duration = $params['day'] * 86400 + $params['hour'] * 3600 + $params['minute'] * 60;

        Db::beginTransaction();
        try {
            // 修改会员等级
            $level->type = (int)$params['type'];
            if ($level->level !== -1) {
                $level->level = (int)$params['level'];
            }
            $level->name        = trim($params['name']);
            $level->icon        = trim($params['icon']);
            $level->price       = (float)$params['price'];
            $level->task_num    = (int)$params['task_num'];
            $level->max_buy_num = (int)$params['max_buy_num'];
            $level->duration    = $duration;
            $level->save();

            // 修改会员奖励
            $this->container->get(UserLevelRebateDAO::class)->edit($level->id, 1, [
                'p_one_rebate'   => (float)$params['recharge_p_one_rebate'],
                'p_two_rebate'   => (float)$params['recharge_p_two_rebate'],
                'p_three_rebate' => (float)$params['recharge_p_three_rebate']
            ]);

            $this->container->get(UserLevelRebateDAO::class)->edit($level->id, 2, [
                'p_one_rebate'   => (float)($params['task_p_one_rebate'] / 100),
                'p_two_rebate'   => (float)($params['task_p_two_rebate'] / 100),
                'p_three_rebate' => (float)($params['task_p_three_rebate'] / 100),
            ]);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('userLevel')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     * 删除会员等级
     *
     * @param array $ids
     */
    public function delete(array $ids)
    {
        Db::beginTransaction();
        try {
            // 删除会员等级
            $this->container->get(UserLevelDAO::class)->delete($ids);

            // 删除会员等级奖励
            $this->container->get(UserLevelRebateDAO::class)->delete($ids);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('userLevel')->error($e->getMessage());
            $this->error($e->getMessage());
        }
    }


    /**
     * 充值操作
     *
     * @param int    $user_id
     * @param int    $level
     * @param string $recharge_remark
     * @param        $trade_no
     * @param int    $channel
     */
    public function rechargeLevel(int $user_id, int $level, string $recharge_remark, $trade_no, int $channel)
    {
        // 查找用户
        $user = $this->container->get(MemberDAO::class)->findUserById($user_id);
        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        if ($level === -1) {
            $this->error('logic.LEVEL_NOT_FOUND');
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
            // 添加支付记录
            $payment = $this->container->get(PaymentDAO::class)->create([
                'user_id'  => $user_id,
                'pay_no'   => 'admin' . date('YmdHis') . mt_rand(1000, 9999),
                'amount'   => $user_level->price,
                'type'     => 1,
                'channel'  => 'admin',
                'trade_no' => $trade_no ?? null,
                'status'   => 2
            ]);

            // 取当前已开通的对应等级
            if ($findLevel = $this->container->get(UserMemberDAO::class)->firstByUserIdLevel($user->id, $user_level->level)) {
                // 累加会员时长
                $findLevel->increment('effective_time', $user_level->duration);
            }
            else {
                // 添加用户开通等级
                $this->container->get(UserMemberDAO::class)->create([
                    'user_id'        => $user->id,
                    'level'          => $user_level->level,
                    'effective_time' => time() + $user_level->duration
                ]);
            }

            // 添加系统通知
            $user_notify      = $this->container->get(UserNotifyDAO::class)->create([
                'type'    => 1,
                'user_id' => $user_id,
                'title'   => 'system_notification',
                'content' => 'Recharge VIP level successfully'
            ]);
            $notify_save_data = [];
            $country_list     = array_column($this->container->get(CountryDAO::class)->get([])->toArray(), 'code');

            $level_name_list = $this->container->get(LanguageDAO::class)->getKeyList($user_level->name);

            foreach ($country_list as $key => $lang_code) {
                $level_name         = $level_name_list[$lang_code] ?? $user_level->name;
                $notify_save_data[] = ['notify_id' => $user_notify->id,
                                       'locale'    => $lang_code,
                                       'content'   => __('logic.RECHARGE_USER_LEVEL_SUCCESS', ['name' => $level_name], $lang_code)
                ];
            }

            $this->container->get(UserNotifyContentDAO::class)->saveAll($notify_save_data);
            // 添加用户账单
            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $user_id,
                'type'           => 10,
                'balance'        => $user_level->price,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance
            ]);

            // 判断是否为首次充值
            if (!$this->container->get(UserRechargeDAO::class)->checkUserRechargeLevel($user_id, $level)) {
                // 首次充值进行上级返利
                $this->addRechargeRebate($user, $user_level->level);
            }


            // 添加充值账单
            $this->container->get(UserRechargeDAO::class)->create([
                'user_id'       => $user_id,
                'level'         => $level,
                'balance'       => $user_level->price,
                'payment_id'    => $payment->id,
                'recharge_time' => $now,
                'channel'       => $channel,
                'admin_id'      => JwtInstance::instance()->build()->getId(),
                'remark'        => $recharge_remark,
                'status'        => 1
            ]);

            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('recharge')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }

    /**
     * 上级返利
     *
     * @param User $user
     */
    public function addRechargeRebate(User $user, int $user_level)
    {
        // 获取会员等级列表
        $level_list = $this->container->get(UserLevelDAO::class)->get(['rebate_type' => 1]);
        $level_list = array_column($level_list->toArray(), null, 'level');

        $type = 2;

        // 一级返利
        $p1_user = $this->container->get(MemberDAO::class)->findUserById($user->parent_id);
        if ($p1_user && ($p1_rebate = $level_list[$user_level]['recharge_level_rebate']['p_one_rebate'] ?? 0) > 0) {
            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p1_user->id,
                'type'           => $type,
                'balance'        => $p1_rebate,
                'before_balance' => $p1_user->balance,
                'after_balance'  => $p1_user->balance + $p1_rebate,
                'low_id'         => $user->id
            ]);

            $p1_user->increment('balance', $p1_rebate);
        }

        // 二级返利
        $p2_user = $this->container->get(MemberDAO::class)->findUserById($p1_user->parent_id ?? 0);
        if ($p2_user && ($p2_rebate = $level_list[$user_level]['recharge_level_rebate']['p_two_rebate'] ?? 0) > 0) {

            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p2_user->id,
                'type'           => $type,
                'balance'        => $p2_rebate,
                'before_balance' => $p2_user->balance,
                'after_balance'  => $p2_user->balance + $p2_rebate,
                'low_id'         => $user->id
            ]);

            $p2_user->increment('balance', $p2_rebate);
        }

        // 三级返利
        $p3_user = $this->container->get(MemberDAO::class)->findUserById($p2_user->parent_id ?? 0);
        if ($p3_user && $p3_user->level > 0 && ($p3_rebate = $level_list[$user_level]['recharge_level_rebate']['p_three_rebate'] ?? 0) > 0) {

            $this->container->get(UserBillDAO::class)->create([
                'user_id'        => $p3_user->id,
                'type'           => $type,
                'balance'        => $p3_rebate,
                'before_balance' => $p3_user->balance,
                'after_balance'  => $p3_user->balance + $p3_rebate,
                'low_id'         => $user->id
            ]);

            $p3_user->increment('balance', $p3_rebate);
        }
    }
}