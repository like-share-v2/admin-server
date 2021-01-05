<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\CountryBank\CountryBankRequest;
use App\Service\CountryBankService;
use App\Service\DAO\CountryBankDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 国家银行表
 *
 * @Controller()
 * @package App\Controller
 */
class CountryBankController extends AbstractController
{
    /**
     * 获取国家银行卡列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'country_id' => 'Integer',
            'bank_name' => 'String',
            'bank_address' => 'String',
            'bank_account' => 'String',
            'address' => 'String',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(CountryBankDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加国家银行卡
     *
     * @PostMapping(path="")
     * @param CountryBankRequest $request
     */
    public function create(CountryBankRequest $request)
    {
        $params = $request->all();

        $this->container->get(CountryBankService::class)->create($params);

        $this->success();
    }

    /**
     * 更新国家银行卡
     *
     * @PutMapping(path="{id}")
     * @param CountryBankRequest $request
     * @param int $id
     */
    public function update(CountryBankRequest $request, int $id)
    {
        $params = $request->all();

        $this->container->get(CountryBankService::class)->update($params, $id);

        $this->success();
    }

    /**
     * 删除国家银行卡
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(CountryBankDAO::class)->delete($ids);

        $this->success();
    }
}