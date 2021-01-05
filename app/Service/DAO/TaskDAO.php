<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Task;
use App\Model\TaskCategory;

/**
 * 任务DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class TaskDAO extends Base
{
    /**
     * 获取任务列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = Task::query()->with(['category:id,name', 'userLevel:level,name'])->withCount([
            'userTask' => function ($query) {
                $query->whereIn('status', [0, 1, 2]);
            }
        ]);

        if (isset($params['category_id']) && $params['category_id'] !== '') {
            $model->where('category_id', (int)$params['category_id']);
        }

        if (isset($params['title']) && $params['title'] !== '') {
            $model->where('title', 'like', '%' . trim($params['title']) . '%');
        }

        if (isset($params['level']) && $params['level'] !== '') {
            $model->where('level', (int)$params['level']);
        }

        if (isset($params['status']) && $params['status'] !== '') {
            $model->where('status', (int)$params['status']);
        }

        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) === 2) {
            $model->whereBetween('created_at', [strtotime($params['created_at'][0]), strtotime($params['created_at'][1] . '23:59:59')]);
        }

        return isset($params['perPage']) ? $model->orderByDesc('sort')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    /**
     * 通过分类ID删除任务
     *
     * @param int $category_id
     * @return int|mixed
     */
    public function deleteByCategoryId(int $category_id)
    {
        return Task::query()->where('category_id', $category_id)->delete();
    }

    /**
     * 添加任务
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Task::query()->create($data);
    }

    /**
     * 通过ID获取任务
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?Task
    {
        return Task::query()->where('id', $id)->first();
    }

    /**
     * 修改任务
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function edit(int $id, array $data)
    {
        return Task::query()->where('id', $id)->update($data);
    }

    /**
     * 设置任务状态
     *
     * @param array $ids
     * @param int $status
     * @return int
     */
    public function setTaskStatusByIds(array $ids, int $status)
    {
        return Task::query()->whereIn('id', $ids)->update([
            'status' => $status
        ]);
    }

    /**
     * 删除任务
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids)
    {
        return Task::destroy($ids);
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function insert(array $data)
    {
        return Task::insert($data);
    }
}