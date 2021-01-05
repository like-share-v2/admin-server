<?php

declare(strict_types=1);

namespace App\Request\Member;

use App\Request\RequestAbstract;

/**
 * 更新用户验证器
 *
 * @package App\Request\Member
 */
class UpdateRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id'           => 'required',
            'type'         => 'required',
            'bank_name'    => 'required',
            'bank_address' => 'required',
            'name'         => 'required',
            'bank_account' => 'required',
            'phone'        => 'required',
        ];
    }
}