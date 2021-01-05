<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Banner\BannerRequest;
use App\Service\DAO\BannerDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 轮播控制器
 *
 * @Controller()
 * @package App\Controller
 */
class BannerController extends AbstractController
{
    /**
     * 获取轮播列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'url' => 'String',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(BannerDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加轮播
     *
     * @PostMapping(path="")
     * @param BannerRequest $request
     */
    public function create(BannerRequest $request)
    {
        $params = $request->all();

        $this->container->get(BannerDAO::class)->create([
            'image' => $params['image'],
            'sort' => $params['sort'],
            'url' => trim($params['url'])
        ]);

        $this->success();
    }

    /**
     * 修改轮播
     *
     * @PutMapping(path="{id}")
     * @param BannerRequest $request
     * @param int $id
     */
    public function update(BannerRequest $request, int $id)
    {
        $params = $request->all();

        $this->container->get(BannerDAO::class)->update($id, [
            'image' => $params['image'],
            'sort' => $params['sort'],
            'url' => trim($params['url'])
        ]);

        $this->success();
    }

    /**
     * 删除轮播
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(BannerDAO::class)->delete($ids);

        $this->success();
    }
}