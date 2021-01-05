<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\RechargeQrCode;

/**
 * 充值二维码DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class RechargeQrCodeDAO extends Base
{
    /**
     * 添加二维码
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return RechargeQrCode::query()->create($data);
    }

    /**
     * 获取充值二维码列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = RechargeQrCode::query();

        if (isset($params['status'])) {
            $model->where('status', $params['status']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 编辑二维码
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function edit(int $id, array $data)
    {
        return RechargeQrCode::query()->where('id', $id)->update($data);
    }

    /**
     * 修改二维码状态
     *
     * @param array $ids
     * @param int $status
     * @return int
     */
    public function changeStatus(array $ids, int $status)
    {
        return RechargeQrCode::query()->whereIn('id', $ids)->update([
            'status' => $status
        ]);
    }

    /**
     * 删除二维码
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return RechargeQrCode::destroy($ids);
    }
}