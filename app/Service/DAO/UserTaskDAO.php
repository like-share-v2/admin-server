<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserTask;
use Hyperf\DbConnection\Db;

/**
 * 用户任务DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserTaskDAO extends Base
{
    /**
     * 获取用户任务列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserTask::query()->with(['user:id,account,phone', 'task']);

        if (isset($params['user_id'])) {
            $model->where('user_id', (int)$params['user_id']);
        }

        if (isset($params['user_account'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('account', $params['user_account']);
            });
        }

        if (isset($params['user_phone'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('phone', $params['user_phone']);
            });
        }

        if (isset($params['task_id'])) {
            $model->where('task_id', (int)$params['task_id']);
        }

        if (isset($params['created_at'])) {
            $model->whereBetween('created_at', $params['created_at']);
        }

        // 用户任务类型
        switch ($params['type'] ?? 0) {
            case 1: // 进行中
                $model->where('status', 0);
                break;
            case 2: // 待审核
                $model->where('status', 1);
                break;
            case 3: // 已审核
                $model->whereIn('status', [2, 3]);
                break;
            case 4: // 已取消
                $model->where('status', 4);
                break;
            case 0: // 全部
            default:
                break;
        }

        return isset($params['perPage']) ? $model->orderByDesc('updated_at')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    /**
     * 通过ID获取用户任务
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?UserTask
    {
        return UserTask::query()->where('id', $id)->first();
    }

    /**
     * 获取用户今日完成任务数
     *
     * @param int $user_id
     * @return int
     */
    public function getUserTodayCompleteCount(int $user_id)
    {
        return UserTask::query()->where('user_id', $user_id)->where('status', 2)->where('created_at', '>', strtotime(date('Y-m-d')))->count();
    }

    /**
     * 通过状态获取任务数量
     *
     * @param array $status
     * @return int
     */
    public function getUserTaskCountByStatus(array $status)
    {
        return UserTask::query()->whereIn('status', $status)->count();
    }

    /**
     * 获取用户完成任务总金额
     *
     * @return int
     */
    public function getCompleteUserTask()
    {
        return UserTask::query()->where('status', 2)->sum('amount');
    }

    /**
     * 通过状态获取用户任务列表
     *
     * @param int $status
     * @param int $limit
     * @return mixed
     */
    public function getListByStatus(int $status, int $limit)
    {
        return UserTask::query()->where('status', $status)->limit($limit)->get();
    }

    public function getCompleteUserCount(array $params)
    {
        $model = UserTask::query();

        if (isset($params['time'])) {
            $model->whereBetween('audit_time', $params['time']);
        }

        return $model->where('status', 2)->count(DB::raw('DISTINCT(user_id)'));
    }
}