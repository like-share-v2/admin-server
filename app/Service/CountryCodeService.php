<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\CountryCodeDAO;

/**
 * 国家区号服务
 *
 * @package App\Service
 */
class CountryCodeService extends Base
{
    /**
     * 添加国家区号
     *
     * @param array $params
     */
    public function create(array $params)
    {
        // 检查名称是否存在
        if ($this->container->get(CountryCodeDAO::class)->checkNameExist(trim($params['name']))) {
            $this->error('logic.COUNTRY_NAME_EXIST');
        }

        // 添加国家区号
        $this->container->get(CountryCodeDAO::class)->create([
            'name' => trim($params['name']),
            'code' => trim($params['code'])
        ]);
    }

    /**
     * 更新国家区号
     *
     * @param int $id
     * @param array $params
     */
    public function update(int $id, array $params)
    {
        // 检查名称是否存在
        if ($this->container->get(CountryCodeDAO::class)->checkNameExist(trim($params['name']), $id)) {
            $this->error('logic.COUNTRY_NAME_EXIST');
        }

        $this->container->get(CountryCodeDAO::class)->update($id, [
            'name' => trim($params['name']),
            'code' => trim($params['code'])
        ]);
    }
}