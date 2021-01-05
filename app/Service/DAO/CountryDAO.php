<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Country;

/**
 * 国家DAO
 *
 * @package App\Service\DAO
 */
class CountryDAO extends Base
{
    /**
     * 添加国家
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Country::query()->create($data);
    }

    /**
     * 更新国家
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id, array $data)
    {
        return Country::query()->where('id', $id)->update($data);
    }

    /**
     * 检查字段是否存在
     *
     * @param string $column
     * @param $value
     * @param null $id
     * @return bool
     */
    public function checkColumnExist(string $column, $value, $id = null)
    {
        return Country::query()->when($id !== null, function ($query) use ($id) {
            return $query->where('id', '<>', $id);
        })->where($column, $value)->exists();
    }

    /**
     * 获取国家列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = Country::query();

        if (isset($params['code'])) {
            $model->where('code', $params['code']);
        }

        if (isset($params['name'])) {
            $model->whereHas('language', function ($query) use ($params) {
                $query->where('value', 'like', '%' . $params['name'] . '%');
            })->orWhere('name', 'like', '%' . $params['name']. '%');
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 通过语言代码获取国家
     *
     * @param string $code
     * @return mixed
     */
    public function firstByCode(string $code): ?Country
    {
        return Country::query()->where('code', $code)->first();
    }

    /**
     * 通过ID获取国家
     *
     * @param int $id
     * @return mixed
     */
    public function firstById(int $id): ?Country
    {
        return Country::query()->where('id', $id)->first();
    }

    /**
     * 删除国家
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return Country::destroy($ids);
    }
}