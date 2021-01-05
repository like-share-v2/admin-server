<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\CountryBank;

/**
 * 国家银行卡DAO
 *
 * @package App\Service\DAO
 */
class CountryBankDAO extends Base
{
    /**
     * 获取国家银行卡列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = CountryBank::query()->with(['country']);

        if (isset($params['country_id'])) {
            $model->where('country_id', $params['country_id']);
        }

        if (isset($params['bank_name'])) {
            $model->where('bank_name', $params['bank_name']);
        }

        if (isset($params['bank_address'])) {
            $model->where('bank_address', $params['bank_address']);
        }

        if (isset($params['bank_account'])) {
            $model->where('bank_account', $params['bank_account']);
        }

        if (isset($params['address'])) {
            $model->where('address', $params['address']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 添加国家银行卡
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CountryBank::query()->create($data);
    }

    /**
     * 编辑国家银行卡
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id, array $data)
    {
        return CountryBank::query()->where('id', $id)->update($data);
    }

    /**
     * 删除国家银行卡
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return CountryBank::destroy($ids);
    }

    /**
     * 检查字段
     *
     * @param string $column
     * @param $value
     * @param null $id
     * @return bool
     */
    public function checkColumnExist(string $column, $value, $id = null)
    {
        return CountryBank::query()->where($column, $value)->when($id !== null, function ($query) use ($id) {
            return $query->where('id', '<>', $id);
        })->exists();
    }
}