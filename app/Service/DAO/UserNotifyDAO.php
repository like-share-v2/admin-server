<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserNotify;

/**
 * 用户通知DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserNotifyDAO extends Base
{
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?UserNotify
    {
        return UserNotify::query()->create($data);
    }

    /**
     * 获取新闻列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserNotify::query()->where('type', 2);

        if (isset($params['title'])) {
            $model->where('title', 'like', '%' . $params['title'] . '%');
        }

        if (isset($params['create_time'])) {
            $model->whereBetween('created_at', $params['create_time']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('sort')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 通过ID获取新闻
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?UserNotify
    {
        return UserNotify::query()->where('id', $id)->first();
    }

    /**
     * 编辑新闻
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function edit(int $id, array $data)
    {
        return UserNotify::query()->where('id', $id)->update($data);
    }

    /**
     * 删除
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return UserNotify::destroy($ids);
    }
}