<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserBill;

/**
 * 用户账单DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserBillDAO extends Base
{
    /**
     * 创建账单
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserBill::query()->create($data);
    }

    /**
     * 获取用户账单列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserBill::query()->with('user:id,account,phone,nickname');

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['user_account'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('account', $params['user_account']);
            });
        }

        if (isset($params['user_phone'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('phone', $params['user_phone']);
            });
        }

        if (isset($params['way'])) {
            $model->where('way', $params['way']);
        }

        if (isset($params['type'])) {
            $model->where('type', 'like', '%' . $params['type'] . '%');
        }

        if (isset($params['create_time'])) {
            $model->whereBetween('created_at', $params['create_time']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 获取用户收入总额
     *
     * @return int
     */
    public function getIncomeAmountSum()
    {
        return UserBill::query()->where('balance', '>', 0)->sum('balance');
    }

    /**
     * 获取用户支出金额
     *
     * @return int
     */
    public function getPayAmountSum()
    {
        return UserBill::query()->where('balance', '<', 0)->sum('balance');
    }

    public function getAmountSum(array $params, array $types)
    {
        $model = UserBill::query();

        if (count($types) > 0) {
            $model->whereIn('type', $types);
        }

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return $model->sum('balance');
    }

    public function getFirstRechargeUserCount(array $params)
    {
        $model = UserBill::query()->whereIn('type', [6, 8]);

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return count($model->select(['user_id'])->groupBy('user_id')->get());
    }


    public function getDetail(array $params)
    {
        $model = UserBill::query()->with('user:id,account,phone,nickname');

        if (isset($params['type']) && is_array($params['type'])) {
            $model->whereIn('type', $params['type']);
        }

        if (isset($params['time']) && is_array($params['time']) && count($params['time']) === 2) {
            $model->whereBetween('created_at', $params['time']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 通过ID获取用户账单
     * @param int $id
     * @return mixed
     */
    public function firstById(int $id): ?UserBill
    {
        return UserBill::query()->where('id', $id)->first();
    }
}