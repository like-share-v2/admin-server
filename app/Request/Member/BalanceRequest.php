<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Request\Member;

use App\Request\RequestAbstract;

/**
 * 余额充值验证器
 *
 * @author
 * @package App\Request\Member
 */
class BalanceRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required',
            'id' => 'required',
            'amount' => 'required|numeric'
        ];
    }
}