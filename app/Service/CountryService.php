<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\CountryDAO;

/**
 * 国家服务
 *
 * @package App\Service
 */
class CountryService extends Base
{
    /**
     * 添加国家
     *
     * @param array $params
     */
    public function create(array $params)
    {
        // 判断语言代码是否存在
        if ($this->container->get(CountryDAO::class)->checkColumnExist('code', trim($params['code']))) {
            $this->error('logic.COUNTRY_CODE_EXIST');
        }

        $this->container->get(CountryDAO::class)->create([
            'code'  => trim($params['code']),
            'phone_code' => trim($params['phone_code']),
            'name'  => trim($params['name']),
            'lang' => trim($params['lang']),
            'image' => trim($params['image'])
        ]);
    }

    /**
     * 更新国家
     *
     * @param int $id
     * @param array $params
     */
    public function update(int $id, array $params)
    {
        // 判断语言代码是否存在
        if ($this->container->get(CountryDAO::class)->checkColumnExist('code', trim($params['code']), $id)) {
            $this->error('logic.COUNTRY_CODE_EXIST');
        }

        $this->container->get(CountryDAO::class)->update($id, [
            'code'  => trim($params['code']),
            'phone_code' => trim($params['phone_code']),
            'name'  => trim($params['name']),
            'lang' => trim($params['lang']),
            'image' => trim($params['image'])
        ]);
    }
}