<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\TaskCategory;
use Hyperf\Cache\Annotation\Cacheable;

/**
 * 任务分类DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class TaskCategoryDAO extends Base
{
    /**
     * 获取任务分类列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = TaskCategory::query();

        if (isset($params['name']) && $params['name'] !== '') {
            $model->where('name', trim($params['name']));
        }

        if (isset($params['status']) && $params['status'] !== '') {
            $model->where('status', (int)$params['status']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('sort')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    /**
     * 添加任务分类
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return TaskCategory::query()->create($data);
    }

    /**
     * 修改任务分类
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function edit(int $id, array $data)
    {
        return TaskCategory::query()->where('id', $id)->update($data);
    }

    /**
     * 通过ID获取分类
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?TaskCategory
    {
        return TaskCategory::query()->where('id', $id)->first();
    }

    /**
     * 删除任务分类
     *
     * @param int $id
     * @return int
     */
    public function delete(int $id)
    {
        return TaskCategory::destroy($id);
    }

    /**
     * 通过IDS设置任务分类状态
     *
     * @param array $ids
     * @param int $status
     * @return int
     */
    public function setStatusByIds(array $ids, int $status)
    {
        return TaskCategory::query()->whereIn('id', $ids)->update([
            'status' => $status
        ]);
    }

    /**
     * 获取任务数量
     *
     * @return mixed
     */
    public function getTaskNum()
    {
        return TaskCategory::query()->select(['name'])->withCount(['task'])->get();
    }

    /**
     * @Cacheable(prefix="TaskCategory", ttl=60)
     * @param string $name
     *
     * @return mixed
     */
    public function getCategoryIdByCategoryName(string $name)
    {
        return TaskCategory::where('name', $name)->value('id');
    }
}