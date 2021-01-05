<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\CountryBankDAO;
use App\Service\DAO\CountryDAO;

/**
 * 国家银行卡服务
 *
 * @package App\Service
 */
class CountryBankService extends Base
{
    public function create(array $params)
    {
        // 检查国家
        if (!$this->container->get(CountryDAO::class)->firstById((int)$params['country_id'])) {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        // 检查国家是否存在银行卡
        if ($this->container->get(CountryBankDAO::class)->checkColumnExist('country_id', (int)$params['country_id'])) {
            $this->error('logic.COUNTRY_BANK_EXIST');
        }

        // 添加银行卡
        $this->container->get(CountryBankDAO::class)->create([
            'country_id' => (int)$params['country_id'],
            'name' => trim($params['name']),
            'bank_name' => trim($params['bank_name']),
            'bank_address' => trim($params['bank_address']),
            'bank_account' => trim($params['bank_account']),
            'address' => trim($params['address'])
        ]);
    }

    /**
     * 更新国家银行卡
     *
     * @param array $params
     * @param int $id
     */
    public function update(array $params, int $id)
    {
        // 检查国家
        if (!$this->container->get(CountryDAO::class)->firstById((int)$params['country_id'])) {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        // 检查国家是否存在银行卡
        if ($this->container->get(CountryBankDAO::class)->checkColumnExist('country_id', (int)$params['country_id'], $id)) {
            $this->error('logic.COUNTRY_BANK_EXIST');
        }

        // 更新国家银行卡
        $this->container->get(CountryBankDAO::class)->update($id, [
            'country_id' => (int)$params['country_id'],
            'name' => trim($params['name']),
            'bank_name' => trim($params['bank_name']),
            'bank_address' => trim($params['bank_address']),
            'bank_account' => trim($params['bank_account']),
            'address' => trim($params['address'])
        ]);
    }
}