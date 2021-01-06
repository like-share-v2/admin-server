<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserWithdrawal;
use Hyperf\DbConnection\Db;

/**
 * 用户提现DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserWithdrawalDAO extends Base
{
    /**
     * 获取用户提现列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserWithdrawal::query()->with(['user:id,account,nickname,phone', 'country:id,name']);

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['country_id'])) {
            $model->where('country_id', $params['country_id']);
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

        // 筛选提现状态
        if (isset($params['type'])) {
            switch ($params['type']) {
                case 0:
                case 1:
                case 2:
                    $model->where('status', $params['type']);
                    break;
                case 3: // 全部
                default:
                    break;
            }
        }

        if (isset($params['account'])) {
            $model->where('account', $params['account']);
        }

        if (isset($params['admin_id'])) {
            $model->where('admin_id', $params['admin_id']);
        }

        if (isset($params['time']) && is_array($params['time']) && count($params['time']) === 2) {
            $model->whereBetween('updated_at', $params['time']);
        }

        if (isset($params['created_at'])) {
            $model->whereBetween('created_at', $params['created_at']);
        }

        if (isset($params['updated_at'])) {
            $model->whereIn('status', [1, 2])->whereBetween('updated_at', $params['updated_at']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 通过ID查找提现记录
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?UserWithdrawal
    {
        return UserWithdrawal::query()->where('id', $id)->first();
    }

    /**
     * 通过状态获取数量
     *
     * @param int $status
     * @return int
     */
    public function getUserWithdrawalCountByStatus(int $status)
    {
        return UserWithdrawal::query()->where('status', $status)->count();
    }

    /**
     * 获取提现成功总额
     *
     * @return int
     */
    public function getPassAmountSum()
    {
        return UserWithdrawal::query()->where('status', 1)->sum('amount');
    }

    public function getAmountSum(array $params)
    {
        $model = UserWithdrawal::query()->where('status', 1);

        if (isset($params['time'])) {
            $model->whereBetween('updated_at', $params['time']);
        }

        return $model->sum('amount');
    }

    public function getUserCount(array $params)
    {
        $model = UserWithdrawal::query()->where('status', 1);

        if (isset($params['time'])) {
            $model->whereBetween('updated_at', $params['time']);
        }

        return $model->count(DB::raw('DISTINCT(user_id)'));
    }
}