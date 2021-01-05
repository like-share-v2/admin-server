<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Country\CountryRequest;
use App\Service\CountryService;
use App\Service\DAO\CountryDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 国家控制器
 *
 * @Controller()
 * @package App\Controller
 */
class CountryController extends AbstractController
{
    /**
     * 获取国家列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'code' => 'String',
            'name' => 'String',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(CountryDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加国家
     *
     * @PostMapping(path="")
     * @param CountryRequest $request
     */
    public function create(CountryRequest $request)
    {
        $params = $request->all();

        if (!isset($params['phone_code']) || $params['phone_code'] === '') {
            $this->formError([
                'phone_code' => 'validation.CountryCode.CountryCodeRequest.code.required'
            ]);
        }

        $this->container->get(CountryService::class)->create($params);

        $this->success();
    }

    /**
     * 更新国家
     *
     * @PutMapping(path="{id}")
     * @param CountryRequest $request
     * @param int $id
     */
    public function update(CountryRequest $request, int $id)
    {
        $params = $request->all();

        if (!isset($params['phone_code']) || $params['phone_code'] === '') {
            $this->formError([
                'phone_code' => 'validation.CountryCode.CountryCodeRequest.code.required'
            ]);
        }

        $this->container->get(CountryService::class)->update($id, $params);

        $this->success();
    }

    /**
     * 删除国家
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(CountryDAO::class)->delete($ids);

        $this->success();
    }
}