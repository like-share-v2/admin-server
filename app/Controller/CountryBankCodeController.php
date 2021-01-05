<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\DAO\CountryBankCodeDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 银行编码控制器
 *
 * @Controller()
 * @package App\Controller
 */
class CountryBankCodeController extends AbstractController
{
    /**
     * 获取银行编码列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'perPage' => 'Integer',
            'country_id' => 'Integer',
            'code' => 'String',
            'name' => 'String'
        ], $this->request->all());

        $result = $this->container->get(CountryBankCodeDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加银行编码
     *
     * @PostMapping(path="")
     */
    public function create()
    {
        $params = $this->request->all();

        if (!isset($params['country_id']) || $params['country_id'] === '') {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        if (!isset($params['code']) || $params['code'] === '') {
            $this->error('validation.Defray.DefrayRequest.bank_code.required');
        }

        if (!isset($params['name']) || $params['name'] === '') {
            $this->error('validation.Defray.DefrayRequest.bank_name.required');
        }

        $this->container->get(CountryBankCodeDAO::class)->create([
            'country_id' => (int)$params['country_id'],
            'code' => $params['code'],
            'name' => $params['name']
        ]);

        $this->success();
    }

    /**
     * 更新银行编码
     *
     * @PutMapping(path="{id}")
     * @param int $id
     */
    public function update(int $id)
    {
        $params = $this->request->all();

        if (!isset($params['country_id']) || $params['country_id'] === '') {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        if (!isset($params['code']) || $params['code'] === '') {
            $this->error('validation.Defray.DefrayRequest.bank_code.required');
        }

        if (!isset($params['name']) || $params['name'] === '') {
            $this->error('validation.Defray.DefrayRequest.bank_name.required');
        }

        $this->container->get(CountryBankCodeDAO::class)->update($id, [
            'country_id' => (int)$params['country_id'],
            'code' => $params['code'],
            'name' => $params['name']
        ]);

        $this->success();
    }

    /**
     * 删除银行编码
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(CountryBankCodeDAO::class)->delete($ids);

        $this->success();
    }
}