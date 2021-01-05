<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserBillDAO;
use Hyperf\DbConnection\Db;

/**
 * 用户账单服务
 *
 * @package App\Service
 */
class UserBillService extends Base
{
    /**
     * 撤销后台操作账单
     *
     * @param int $id
     */
    public function cancel(int $id)
    {
        // 判断用户账单是否存在
        $user_bill = $this->container->get(UserBillDAO::class)->firstById($id);
        if (!$user_bill) {
            $this->error('logic.USER_BILL_NOT_FOUND');
        }

        if ($user_bill->getAttributes()['type'] !== 7 && $user_bill->getAttributes()['type'] !== 6 && $user_bill->getAttributes()['type'] !== 11) {
            $this->error('logic.USER_BILL_IS_NOT_ADMIN');
        }

        $user = $this->container->get(MemberDAO::class)->first($user_bill->user_id);
        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        Db::beginTransaction();
        try {
            if ($user_bill->balance > 0) {
                // 后台充值账单撤销

                // 扣除充值金额
                $user->decrement('balance', $user_bill->balance);

                $last_bill = $this->container->get(UserBillDAO::class)->firstById($id);
                if (!$last_bill) {
                    throw new \Exception('repeat submit');
                }

                // 删除充值账单
                $user_bill->delete();
            } else {
                // 后台扣除账单撤销

                // 增加扣除金额
                $user->increment('balance', abs($user_bill->balance));

                $last_bill = $this->container->get(UserBillDAO::class)->firstById($id);
                if (!$last_bill) {
                    throw new \Exception('repeat submit');
                }

                // 删除扣除账单
                $user_bill->delete();
            }



            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->logger('userBill')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }
}