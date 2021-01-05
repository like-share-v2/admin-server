<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;

use App\Service\DAO\UserBillDAO;
use App\Service\UserBillService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 用户账单控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class UserBillController extends AbstractController
{
    /**
     * 获取用户账单列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'user_id'      => 'Integer',
            'way'          => 'Integer',
            'user_account' => 'String',
            'user_phone'   => 'String',
            'type'         => 'String',
            'create_time'  => 'TimestampBetween',
            'perPage'      => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(UserBillDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 撤销后台账单
     *
     * @PostMapping(path="cancel")
     */
    public function cancel()
    {
        $id = (int)$this->request->input('id', 0);

        $this->container->get(UserBillService::class)->cancel($id);

        $this->success();
    }
}