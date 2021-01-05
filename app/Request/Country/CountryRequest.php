<?php

declare(strict_types=1);

namespace App\Request\Country;

use App\Request\RequestAbstract;

/**
 * 国家验证器
 *
 * @package App\Request\Country
 */
class CountryRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'code' => 'required',
            'name' => 'required',
            'lang' => 'required',
            'image' => 'required'
        ];
    }
}