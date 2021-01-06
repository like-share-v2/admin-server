<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller;

use App\Request\UserRecharge\AuditManualRequest;
use App\Service\DAO\UserBankRechargeDAO;
use App\Service\DAO\UserManualRechargeDAO;
use App\Service\DAO\UserRechargeDAO;
use App\Service\UserBankRechargeService;
use App\Service\UserManualRechargeService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 用户充值控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class UserRechargeController extends AbstractController
{
    /**
     * 获取用户充值记录
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'user_id'       => 'Integer',
            'user_phone'    => 'String',
            'user_account'  => 'String',
            'level'         => 'Integer',
            'channel'       => 'Integer',
            'admin_id'      => 'Integer',
            'recharge_time' => 'TimestampBetween',
            'perPage'       => 'Integer',
            'pay_no'        => 'String',
            'trade_no'      => 'String'
        ], $this->request->all());

        $result = $this->container->get(UserRechargeDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 获取用户手动充值记录
     *
     * @GetMapping(path="manual")
     */
    public function getManual()
    {
        $params = map_filter([
            'user_id'      => 'Integer',
            'user_phone'   => 'String',
            'user_account' => 'String',
            'level'        => 'Integer',
            'trade_no'     => 'String',
            'type'         => 'Integer',
            'admin_id'     => 'Integer',
            'perPage'      => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(UserManualRechargeDAO::class)->get($params)->toArray();

        $result['audit_count']  = $this->container->get(UserManualRechargeDAO::class)->getCountByStatus(0);
        $result['pass_count']   = $this->container->get(UserManualRechargeDAO::class)->getCountByStatus(1);
        $result['refuse_count'] = $this->container->get(UserManualRechargeDAO::class)->getCountByStatus(2);

        $this->success($result);
    }

    /**
     * 审核扫码充值
     *
     * @PostMapping(path="audit_manual")
     * @param AuditManualRequest $request
     */
    public function auditManual(AuditManualRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserManualRechargeService::class)->audit($params);

        $this->success();
    }

    /**
     * 获取用户银行充值列表
     *
     * @GetMapping(path="bank")
     */
    public function getBank()
    {
        $params = map_filter([
            'user_id'      => 'Integer',
            'country_id'   => 'Integer',
            'user_phone'   => 'String',
            'user_account' => 'String',
            'name'         => 'String',
            'bank'         => 'String',
            'bank_name'    => 'String',
            'amount'       => 'String',
            'remittance'   => 'String',
            'type'         => 'Integer',
            'admin_id'     => 'Integer',
            'perPage'      => 'Integer',
            'created_at' => 'TimestampBetween',
            'updated_at' => 'TimestampBetween'
        ], $this->request->all());

        $result = $this->container->get(UserBankRechargeDAO::class)->get($params)->toArray();

        $result['audit_count']  = $this->container->get(UserBankRechargeDAO::class)->getCountByStatus(0);
        $result['pass_count']   = $this->container->get(UserBankRechargeDAO::class)->getCountByStatus(1);
        $result['refuse_count'] = $this->container->get(UserBankRechargeDAO::class)->getCountByStatus(2);

        $this->success($result);
    }

    /**
     * 银行充值审核
     *
     * @PostMapping(path="audit_bank")
     * @param AuditManualRequest $request
     */
    public function auditBank(AuditManualRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserBankRechargeService::class)->audit($params);

        $this->success();
    }
}