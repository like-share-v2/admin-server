<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;

use App\Request\UserWithdrawal\AuditRequest;
use App\Service\DAO\UserWithdrawalDAO;
use App\Service\UserWithdrawalService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 用户提现控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class UserWithdrawalController extends AbstractController
{
    /**
     * 获取用户提现列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'user_id'      => 'Integer',
            'country_id'   => 'Integer',
            'user_account' => 'String',
            'user_phone'   => 'String',
            'account'      => 'String',
            'type'         => 'Integer',
            'admin_id'     => 'Integer',
            'perPage'      => 'Integer',
            'created_at' => 'TimestampBetween',
            'updated_at' => 'TimestampBetween'
        ], $this->request->all());

        $result = $this->container->get(UserWithdrawalDAO::class)->get($params)->toArray();

        $result['audit_count'] = $this->container->get(UserWithdrawalDAO::class)->getUserWithdrawalCountByStatus(0);
        $result['pass_count'] = $this->container->get(UserWithdrawalDAO::class)->getUserWithdrawalCountByStatus(1);
        $result['refuse_count'] = $this->container->get(UserWithdrawalDAO::class)->getUserWithdrawalCountByStatus(2);

        $this->success($result);
    }

    /**
     * 提现审核
     *
     * @PostMapping(path="audit")
     * @param AuditRequest $request
     */
    public function audit(AuditRequest $request)
    {
        $params = $request->all();
        $params['pay_channel'] = $params['pay_channel'] ?? getConfig('pay_channel', '');

        $this->container->get(UserWithdrawalService::class)->audit($params);

        $this->success();
    }
}