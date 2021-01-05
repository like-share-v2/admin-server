<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\TaskAudit\AuditRequest;
use App\Service\DAO\TaskAuditDAO;
use App\Service\TaskAuditService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 任务审核控制器
 *
 * @Controller()
 * @package App\Controller
 */
class TaskAuditController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'id' => 'Integer',
            'user_id' => 'Integer',
            'category_id' => 'Integer',
            'type'         => 'Integer',
            'title' => 'String',
            'create_time' => 'TimestampBetween',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(TaskAuditDAO::class)->get($params)->toArray();

        $result['audit_count']  = $this->container->get(TaskAuditDAO::class)->getCountByStatus(0);
        $result['pass_count']   = $this->container->get(TaskAuditDAO::class)->getCountByStatus(1);
        $result['refuse_count'] = $this->container->get(TaskAuditDAO::class)->getCountByStatus(2);

        $this->success($result);
    }

    /**
     * 审核
     *
     * @PostMapping(path="audit")
     * @param AuditRequest $request
     */
    public function audit(AuditRequest $request)
    {
        $params = $request->all();

        $this->container->get(TaskAuditService::class)->audit($params);

        $this->success();
    }
}