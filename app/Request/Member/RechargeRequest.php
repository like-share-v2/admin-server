<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\Member;

use App\Request\RequestAbstract;

/**
 * 充值验证器
 *
 * @author
 * @package App\Request\Member
 */
class RechargeRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id'     => 'required',
            'level'  => 'required|gte:0'
        ];
    }
}