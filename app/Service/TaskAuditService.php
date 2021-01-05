<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\TaskAuditDAO;
use App\Service\DAO\TaskDAO;
use App\Service\DAO\UserBillDAO;
use Hyperf\DbConnection\Db;

/**
 * 任务审核服务
 *
 * @package App\Service
 */
class TaskAuditService extends Base
{
    public function audit(array $params)
    {
        $task_audit = $this->container->get(TaskAuditDAO::class)->firstById((int)$params['id']);
        if (!$task_audit) {
            $this->error('logic.TASK_AUDIT_NOT_FOUND');
        }

        // 判断用户
        $user = $this->container->get(MemberDAO::class)->findUserById($task_audit->user_id);
        if (!$user) {
            $this->error('logic.USER_NOT_FOUND');
        }

        // 判断状态
        if ($task_audit->status !== 0) {
            $this->error('logic.TASK_AUDIT_STATUS_ERROR');
        }

        $task_audit->admin_id = JwtInstance::instance()->build()->getId();
        $task_audit->remark = $params['remark'] ?? '';


        Db::beginTransaction();

        try {
            if ((int)$params['status'] === 2) {
                $task_audit->status = 2;
                $task_audit->save();

                // 返还用户资金
                $this->container->get(UserBillDAO::class)->create([
                    'user_id' => $user->id,
                    'type' => 12,
                    'balance' => $task_audit->amount * $task_audit->num,
                    'before_balance' => $user->balance,
                    'after_balance' => $user->balance + $task_audit->amount * $task_audit->num,
                ]);

                $user->increment('balance', $task_audit->amount * $task_audit->num);

            } else {
                $task_audit->status = 1;
                $task_audit->save();

                // 创建任务
                $this->container->get(TaskDAO::class)->create([
                    'user_id'     => $user->id,
                    'category_id' => $task_audit->category_id,
                    'level'       => $task_audit->level,
                    'title'       => $task_audit->title,
                    'description' => $task_audit->description,
                    'url'         => $task_audit->url,
                    'amount'      => $task_audit->amount,
                    'num'         => $task_audit->num,
                    'sort'        => 0,
                    'status'      => 1
                ]);

                // 今日发布任务排名
                try {
                    $key  = sprintf('DailyPublishTaskRank:%s', date('Ymd'));
                    $key1 = sprintf('DailyPublishTaskAmount:%s:%s', date('Ymd'), $user->phone);
                    $this->redis->zIncrBy($key, 1, $user->phone);
                    $this->redis->incrByFloat(sprintf('DailyPublishTaskAmount:%s:%s', date('Ymd'), $user->phone), $task_audit->amount);
                    $this->redis->set(sprintf('UserAvatar:%s', $user->phone), $user->avatar, 86400);
                    $this->redis->expire($key, 86400);
                    $this->redis->expire($key1, 86400);
                }
                catch (\Throwable $e) {
                    var_dump($e->getMessage());
                }
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->logger('task')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }
}