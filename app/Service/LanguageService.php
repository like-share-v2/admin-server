<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\CountryDAO;
use App\Service\DAO\LanguageDAO;

/**
 * 语言服务
 *
 * @package App\Service
 */
class LanguageService extends Base
{
    /**
     * 添加语言
     *
     * @param array $params
     */
    public function create(array $params)
    {
        // 判断国家
        if (!$this->container->get(CountryDAO::class)->firstByCode(trim($params['local']))) {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        $this->container->get(LanguageDAO::class)->create([
            'key' => trim($params['key']),
            'local' => trim($params['local']),
            'value' => trim($params['value'])
        ]);
    }

    /**
     * 更新语言
     *
     * @param int $id
     * @param array $params
     */
    public function update(int $id, array $params)
    {
        // 判断国家
        if (!$this->container->get(CountryDAO::class)->firstByCode(trim($params['local']))) {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        $this->container->get(LanguageDAO::class)->update($id, [
            'key' => trim($params['key']),
            'local' => trim($params['local']),
            'value' => trim($params['value'])
        ]);
    }
}