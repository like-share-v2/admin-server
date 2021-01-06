<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserManualRecharge;

/**
 * 用户手动充值DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserManualRechargeDAO extends Base
{
    /**
     * 获取用户充值列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserManualRecharge::query()->with(['user:id,account,phone,nickname', 'userLevel:level,name']);

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

        if (isset($params['level'])) {
            $model->where('level', $params['level']);
        }

        if (isset($params['trade_no'])) {
            $model->where('trade_no', $params['trade_no']);
        }

        /*if (isset($params['status'])) {
            $model->where('status', $params['status']);
        }*/

        if (isset($params['admin_id'])) {
            $model->where('admin_id', $params['admin_id']);
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

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 通过ID查找扫码充值记录
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?UserManualRecharge
    {
        return UserManualRecharge::query()->where('id', $id)->first();
    }

    /**
     * 通过状态获取数量
     *
     * @param int $status
     * @return int
     */
    public function getCountByStatus(int $status)
    {
        return UserManualRecharge::query()->where('status', $status)->count();
    }
}