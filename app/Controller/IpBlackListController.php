<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DAO\IpBlackListDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * IP黑名单列表
 *
 * @Controller()
 * @package App\Controller
 */
class IpBlackListController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'ip' => 'String',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(IpBlackListDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * @PostMapping(path="")
     */
    public function create()
    {
        $ip = $this->request->input('ip', '');
        if ($ip === '') {
            $this->error('logic.IP_REQUIRED');
        }

        // 检查是否重复
        if ($this->container->get(IpBlackListDAO::class)->checkIp($ip)) {
            $this->error('logic.IP_EXIST');
        }

        // 添加黑名单
        $this->container->get(IpBlackListDAO::class)->create([
            'ip' => trim($ip)
        ]);

        $this->success();
    }

    /**
     *
     * @PutMapping(path="{id}")
     * @param int $id
     */
    public function update(int $id)
    {
        $ip = $this->request->input('ip', '');
        if ($ip === '') {
            $this->error('logic.IP_REQUIRED');
        }

        // 检查是否重复
        if ($this->container->get(IpBlackListDAO::class)->checkIp($ip, $id)) {
            $this->error('logic.IP_EXIST');
        }

        $this->container->get(IpBlackListDAO::class)->update($id, [
            'ip' => trim($ip)
        ]);

        $this->success();
    }

    /**
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
           return is_numeric($id);
        });

        $this->container->get(IpBlackListDAO::class)->delete($ids);

        $this->success();
    }
}