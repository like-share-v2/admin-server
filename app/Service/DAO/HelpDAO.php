<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Help;

/**
 * 帮助手册DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class HelpDAO extends Base
{
    /**
     * 帮助手册列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = Help::query();

        if (isset($params['title'])) {
            $model->where('title', $params['title']);
        }

        if (isset($params['status'])) {
            $model->where('status', $params['status']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('sort')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    /**
     * 添加帮助手册
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Help::query()->create($data);
    }

    /**
     * 通过ID获取帮助手册
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id)
    {
        return Help::query()->where('id', $id)->first();
    }

    /**
     * 编辑帮助手册
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function edit(int $id, array $data)
    {
        return Help::query()->where('id', $id)->update($data);
    }

    /**
     * 通过IDS修改状态
     *
     * @param array $ids
     * @param int $status
     * @return int
     */
    public function changeStatusByIds(array $ids, int $status)
    {
        return Help::query()->whereIn('id', $ids)->update([
            'status' => $status
        ]);
    }

    /**
     * 通过IDS删除帮助手册
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return Help::destroy($ids);
    }
}