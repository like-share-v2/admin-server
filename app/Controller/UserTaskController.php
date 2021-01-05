<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;

use App\Request\UserTask\AuditRequest;
use App\Service\DAO\UserTaskDAO;
use App\Service\UserTaskService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 用户任务控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class UserTaskController extends AbstractController
{
    /**
     * 获取用户任务列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $map = map_filter([
            'user_id'      => 'Integer',
            'user_account' => 'String',
            'user_phone'   => 'String',
            'task_id'      => 'Integer',
            'created_at'  => 'TimestampBetween',
            'admin_id'     => 'Integer',
            'type'         => 'Integer',
            'perPage'      => 'Integer'
        ], $this->request->all());

        if (!isset($map['type'])) {
            $map['type'] = 0;
        }

        $result = $this->container->get(UserTaskDAO::class)->get($map)->toArray();

        $result['progress_count'] = $this->container->get(UserTaskDAO::class)->getUserTaskCountByStatus([0]);
        $result['audit_count'] = $this->container->get(UserTaskDAO::class)->getUserTaskCountByStatus([1]);
        $result['approved_count'] = $this->container->get(UserTaskDAO::class)->getUserTaskCountByStatus([2, 3]);
        $result['cancelled_count'] = $this->container->get(UserTaskDAO::class)->getUserTaskCountByStatus([4]);

        $this->success($result);
    }

    /**
     * 审核操作
     *
     * @PostMapping(path="audit")
     * @param AuditRequest $request
     * @throws InvalidArgumentException
     */
    public function audit(AuditRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserTaskService::class)->audit($params);

        $this->success();
    }

    /**
     * 全部通过
     *
     * @PostMapping(path="audit_all_pass")
     */
    public function auditAllPass()
    {
        $this->container->get(UserTaskService::class)->auditAllPass();

        $this->success();
    }
}