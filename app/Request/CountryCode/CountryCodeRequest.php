<?php

declare(strict_types=1);

namespace App\Request\CountryCode;

use App\Request\RequestAbstract;

/**
 * 国家区号验证器
 *
 * @package App\Request\CountryCode
 */
class CountryCodeRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'code' => 'required'
        ];
    }
}