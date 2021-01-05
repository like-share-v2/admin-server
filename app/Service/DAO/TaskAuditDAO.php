<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\TaskAudit;

/**
 * 任务审核DAO
 *
 * @package App\Service\DAO
 */
class TaskAuditDAO extends Base
{
    public function get(array $params)
    {
        $model = TaskAudit::query()->with(['user', 'category', 'userLevel']);

        if (isset($params['id'])) {
            $model->where('id', $params['id']);
        }

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['category_id'])) {
            $model->where('category_id', $params['category_id']);
        }

        if (isset($params['type']) && $params['type'] !== 3) {
            $model->where('status', $params['type']);
        }

        if (isset($params['title'])) {
            $model->where('title', 'like', '%'.$params['title'].'%');
        }

        if (isset($params['create_time'])) {
            $model->whereBetween('created_at', $params['create_time']);
        }

        return isset($params['perPage']) ? $model->orderBy('id', 'asc')->paginate($params['perPage']) : $model->get();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function firstById(int $id): ?TaskAudit
    {
        return TaskAudit::query()->where('id', $id)->first();
    }

    public function getCountByStatus(int $status)
    {
        return TaskAudit::query()->where('status', $status)->count();
    }
}