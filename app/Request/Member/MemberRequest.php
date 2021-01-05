<?php

declare(strict_types=1);

namespace App\Request\Member;

use App\Request\RequestAbstract;

/**
 * 用户验证器
 *
 * @package App\Request\Member
 */
class MemberRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type'      => 'required',
            'parent_id' => 'required',
            // 'account'   => 'required|alpha_dash|between:5,30',
            'password'  => 'required|alpha_dash|between:6,30',
            'phone'     => 'required',
        ];
    }
}