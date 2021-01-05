<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\CountryCode\CountryCodeRequest;
use App\Service\CountryCodeService;
use App\Service\DAO\CountryCodeDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 国家区号控制器
 *
 * @Controller()
 * @package App\Controller
 */
class CountryCodeController extends AbstractController
{
    /**
     * 获取国家区号列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'name' => 'String',
            'code' => 'String',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(CountryCodeDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加国家区号
     *
     * @PostMapping(path="")
     * @param CountryCodeRequest $request
     */
    public function create(CountryCodeRequest $request)
    {
        $params = $request->all();

        $this->container->get(CountryCodeService::class)->create($params);

        $this->success();
    }

    /**
     * 更新国家区号
     *
     * @PutMapping(path="{id}")
     * @param CountryCodeRequest $request
     * @param int $id
     */
    public function update(CountryCodeRequest $request, int $id)
    {
        $params = $request->all();

        $this->container->get(CountryCodeService::class)->update($id, $params);

        $this->success();
    }

    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(CountryCodeDAO::class)->delete($ids);

        $this->success();
    }
}