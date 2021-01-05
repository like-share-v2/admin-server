<?php

declare(strict_types=1);

namespace App\Request\CountryBank;

use App\Request\RequestAbstract;

/**
 * 国家银行卡验证器
 *
 * @package App\Request\CountryBank
 */
class CountryBankRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country_id' => 'required',
            'name' => 'required',
            'bank_name' => 'required',
            'bank_address' => 'required',
            'bank_account' => 'required',
            'address' => 'required'
        ];
    }
}