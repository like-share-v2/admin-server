<?php

declare(strict_types=1);

namespace App\Request\Defray;

use App\Request\RequestAbstract;

/**
 * @package App\Request\Defray
 */
class DefrayRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // 'country_id'   => 'required',
            'amount'       => 'required|gt:0',
            'name'         => 'required',
            'bank_code'    => 'required',
            'bank_name'    => 'required',
            'bank_account' => 'required',
            // 'open_province' => 'required',
            // 'open_city'     => 'required',
            'user_mobile'  => 'required',
            // 'user_email'   => 'required',
            // 'address'      => 'required'
        ];
    }
}