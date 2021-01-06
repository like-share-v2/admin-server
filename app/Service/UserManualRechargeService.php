<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\UserLevelDAO;
use App\Service\DAO\UserManualRechargeDAO;

use Hyperf\DbConnection\Db;

/**
 * 用户扫码充值服务
 *
 * @author 
 * @package App\Service
 */
class UserManualRechargeService extends Base
{
    /**
     * 审核用户扫码充值
     *
     * @param array $params
     */
    public function audit(array $params)
    {
        // 判断扫码充值记录是否存在
        $user_manual_recharge = $this->container->get(UserManualRechargeDAO::class)->findById((int)$params['id']);
        if (!$user_manual_recharge) {
            $this->error('logic.USER_MANUAL_RECHARGE_NOT_FOUND');
        }

        // 判断扫码充值VIP等级是否存在
        $user_level = $this->container->get(UserLevelDAO::class)->findByLevel((int)$user_manual_recharge->level);
        if (!$user_level) {
            $this->error('logic.MANUAL_LEVEL_NOT_FOUND');
        }

        // 扫码充值状态判断
        if ($user_manual_recharge->status !== 0) {
            $this->error('logic.MANUAL_RECHARGE_STATUS_ERROR');
        }

        $user_manual_recharge->admin_id = JwtInstance::instance()->build()->getId();
        $user_manual_recharge->remark = trim($params['remark'] ?? '');

        if ((int)$params['status'] === 2) {
            // 拒绝
            $user_manual_recharge->status = 2;
            $user_manual_recharge->save();
        } else {
            // 通过
            Db::beginTransaction();
            try {
                // 修改手动充值状态
                $user_manual_recharge->status = 1;
                $user_manual_recharge->save();

                // 进行充值操作
                $this->container->get(UserLevelService::class)->rechargeLevel($user_manual_recharge->user_id, $user_manual_recharge->level, $user_manual_recharge->remark, $user_manual_recharge->trade_no, 3);

                Db::commit();
            } catch (\Exception $e) {
                Db::rollBack();
                $this->logger('recharge')->error($e->getMessage());
                $this->error('logic.SERVER_ERROR');
            }
        }
    }
}