<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\CountryCode;

/**
 * 国家区号DAO
 *
 * @package App\Service\DAO
 */
class CountryCodeDAO extends Base
{
    /**
     * 获取国家区号列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = CountryCode::query();

        if (isset($params['name'])) {
            $model->where('name', $params['name']);
        }

        if (isset($params['code'])) {
            $model->where('code', $params['code']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 添加国家区号
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CountryCode::query()->create($data);
    }

    /**
     * 更新国家区号
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id, array $data)
    {
        return CountryCode::query()->where('id', $id)->update($data);
    }

    /**
     * 删除国家区号
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return CountryCode::destroy($ids);
    }

    /**
     * 检查名称是否存在
     *
     * @param string $name
     * @param int|null $id
     * @return bool
     */
    public function checkNameExist(string $name, int $id = null)
    {
        return CountryCode::query()->when($id !== null, function ($query) use ($id) {
            return $query->where('id', '<>', $id);
        })->where('name', $name)->exists();
    }
}