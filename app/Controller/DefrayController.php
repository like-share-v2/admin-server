<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Defray\DefrayRequest;
use App\Service\DAO\DefrayDAO;
use App\Service\DefrayService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;

/**
 * 代付控制器
 *
 * @Controller()
 * @package App\Controller
 */
class DefrayController extends AbstractController
{
    /**
     * 代付接口
     *
     * @PostMapping(path="")
     * @param DefrayRequest $request
     */
    public function defray(DefrayRequest $request)
    {
        $params = $request->all();

        $this->container->get(DefrayService::class)->defray($params);

        $this->success();
    }

    /**
     * 获取代付订单列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'perPage' => 'Integer',
            'order_no' => 'String',
            'admin_id' => 'Integer',
            'channel' => 'String',
            'amount' => 'Integer',
            'name' => 'String',
            'bank_account' => 'String',
            'status' => 'Integer',
            'created_at' => 'TimestampBetween',
            'country_id' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(DefrayDAO::class)->get($params);

        $this->success($result);
    }
}