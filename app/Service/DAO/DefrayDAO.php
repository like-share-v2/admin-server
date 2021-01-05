<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Defray;

/**
 * 代付DAO
 *
 * @package App\Service\DAO
 */
class DefrayDAO extends Base
{
    /**
     * 添加代付记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?Defray
    {
        return Defray::query()->create($data);
    }

    public function get(array $params)
    {
        $model = Defray::query()->with(['country:id,name']);

        if (isset($params['order_no'])) {
            $model->where('order_no', $params['order_no']);
        }

        if (isset($params['admin_id'])) {
            $model->where('admin_id', $params['admin_id']);
        }

        if (isset($params['country_id'])) {
            $model->where('country_id', $params['country_id']);
        }

        if (isset($params['channel'])) {
            $model->where('channel', $params['channel']);
        }

        if (isset($params['amount'])) {
            $model->where('amount', $params['amount']);
        }

        if (isset($params['name'])) {
            $model->where('name', $params['name']);
        }

        if (isset($params['bank_account'])) {
            $model->where('bank_account', $params['bank_account']);
        }

        if (isset($params['status'])) {
            $model->where('status', $params['status']);
        }

        if (isset($params['created_at'])) {
            $model->whereBetween('created_at', $params['created_at']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }
}