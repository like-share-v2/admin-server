<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserBankRechargeDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserLevelDAO;
use Hyperf\DbConnection\Db;

/**
 * 用户银行充值服务
 *
 * @package App\Service
 */
class UserBankRechargeService extends Base
{
    /**
     * 审核用户银行卡充值
     *
     * @param array $params
     */
    public function audit(array $params)
    {
        // 判断扫码充值记录是否存在
        $user_bank_recharge = $this->container->get(UserBankRechargeDAO::class)->findById((int)$params['id']);
        if (!$user_bank_recharge) {
            $this->error('logic.USER_BANK_RECHARGE_NOT_FOUND');
        }

        // 判断用户
        $user = $this->container->get(MemberDAO::class)->findUserById($user_bank_recharge->user_id);
        if (!$user) {
            $this->error('logic.BANK_RECHARGE_USER_NOT_FOUND');
        }

        // 扫码充值状态判断
        if ($user_bank_recharge->status !== 0) {
            $this->error('logic.BANK_RECHARGE_STATUS_ERROR');
        }

        $user_bank_recharge->admin_id = JwtInstance::instance()->build()->getId();
        $user_bank_recharge->remark = trim($params['remark'] ?? '');

        if ((int)$params['status'] === 2) {
            // 拒绝
            $user_bank_recharge->status = 2;
            $user_bank_recharge->save();
        } else {
            // 通过
            Db::beginTransaction();
            try {
                // 修改手动充值状态
                $user_bank_recharge->status = 1;
                $user_bank_recharge->save();

                // 记录账单
                $this->container->get(UserBillDAO::class)->create([
                    'user_id' => $user->id,
                    'type' => 8,
                    'balance' => $user_bank_recharge->amount,
                    'before_balance' => $user->balance,
                    'after_balance' => $user->balance + $user_bank_recharge->amount,
                ]);

                // 进行充值操作
                $user->increment('balance', $user_bank_recharge->amount);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                $this->logger('recharge')->error($e->getMessage());
                $this->error('logic.SERVER_ERROR');
            }
        }
    }
}