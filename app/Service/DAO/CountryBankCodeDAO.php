<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\CountryBankCode;

/**
 * @package App\Service\DAO
 */
class CountryBankCodeDAO extends Base
{
    /**
     * 获取银行卡编码列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = CountryBankCode::query()->with(['country:id,name']);

        if (isset($params['country_id'])) {
            $model->where('country_id', $params['country_id']);
        }

        if (isset($params['code'])) {
            $model->where('code', $params['code']);
        }

        if (isset($params['name'])) {
            $model->where('name', $params['name']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 添加银行编码
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CountryBankCode::query()->create($data);
    }

    /**
     * 更新银行编码
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id, array $data)
    {
        return CountryBankCode::query()->where('id', $id)->update($data);
    }

    /**
     * 删除银行编码
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return CountryBankCode::destroy($ids);
    }
}