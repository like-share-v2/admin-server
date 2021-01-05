<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserBankRecharge;

/**
 * 用户银行充值DAO
 *
 * @package App\Service\DAO
 */
class UserBankRechargeDAO extends Base
{
    /**
     * 获取用户银行充值列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserBankRecharge::query()->with(['user:id,account,phone,nickname', 'country:id,name']);

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['user_phone'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('phone', $params['user_phone']);
            });
        }

        if (isset($params['user_account'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('account', $params['user_account']);
            });
        }

        if (isset($params['country_id'])) {
            $model->where('country_id', $params['country_id']);
        }

        if (isset($params['name'])) {
            $model->where('name', $params['name']);
        }

        if (isset($params['bank'])) {
            $model->where('bank', $params['bank']);
        }

        if (isset($params['bank_name'])) {
            $model->where('bank_name', $params['bank_name']);
        }

        if (isset($params['amount'])) {
            $model->where('amount', $params['amount']);
        }

        if (isset($params['remittance'])) {
            $model->where('remittance', $params['remittance']);
        }

        if (isset($params['admin_id'])) {
            $model->where('admin_id', $params['admin_id']);
        }

        if (isset($params['created_at'])) {
            $model->whereBetween('created_at', $params['created_at']);
        }

        switch ($params['type'] ?? 3) {
            case 0:
            case 1:
            case 2:
                $model->where('status', $params['type']);
                break;
            default:
                break;
        }

        if (isset($params['updated_at'])) {
            $model->whereIn('status', [1, 2])->whereBetween('updated_at', $params['updated_at']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 通过ID查找记录
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id) :? UserBankRecharge
    {
        return UserBankRecharge::query()->where('id', $id)->first();
    }

    /**
     * 通过状态获取记录数量
     *
     * @param int $status
     * @return int
     */
    public function getCountByStatus(int $status)
    {
        return UserBankRecharge::query()->where('status', $status)->count();
    }
}