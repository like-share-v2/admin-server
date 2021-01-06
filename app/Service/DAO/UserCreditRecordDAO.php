<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserCreditRecord;

/**
 * 用户信用分记录DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserCreditRecordDAO extends Base
{
    /**
     * 记录用户信用分变动
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserCreditRecord::query()->create($data);
    }

    /**
     * 获取用户信用分记录
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserCreditRecord::query()->with('user:id,account,phone,nickname');

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['user_account'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('account', $params['user_account']);
            });
        }

        if (isset($params['type'])) {
            $model->where('type', 'like', '%' . $params['type'] . '%');
        }

        if (isset($params['user_phone'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('phone', $params['user_phone']);
            });
        }

        if (isset($params['create_time'])) {
            $model->whereBetween('created_at', $params['create_time']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }
}