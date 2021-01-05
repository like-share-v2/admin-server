<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DAO\UserOnlineRechargeDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 用户在线充值控制器
 *
 * @Controller()
 * @package App\Controller
 */
class UserOnlineRechargeController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'user_id'     => 'Integer',
            'user_phone'  => 'String',
            'channel'     => 'Integer',
            'status'      => 'Integer',
            'updated_at' => 'TimestampBetween',
            'perPage'     => 'Integer',
            'pay_no'      => 'String'
        ], $this->request->all());

        $result = $this->container->get(UserOnlineRechargeDAO::class)->get($params);

        $this->success($result);
    }
}