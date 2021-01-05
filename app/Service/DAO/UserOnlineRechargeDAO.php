<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserOnlineRecharge;

/**
 * @package App\Service\DAO
 */
class UserOnlineRechargeDAO extends Base
{
    /**
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserOnlineRecharge::query()->with(['user:id,phone,nickname', 'country:id,name', 'payment']);;

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['user_phone'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('phone', $params['user_phone']);
            });
        }

        if (isset($params['status'])) {
            $model->where('status', $params['status']);
        }

        if (isset($params['channel'])) {
            $model->where('channel', $params['channel']);
        }

        if (isset($params['pay_no'])) {
            $model->whereHas('payment', function ($query) use ($params) {
                $query->where('pay_no', $params['pay_no']);
            });
        }

        if (isset($params['updated_at'])) {
            $model->whereBetween('updated_at', $params['updated_at']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }
}