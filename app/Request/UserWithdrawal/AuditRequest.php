<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Request\UserWithdrawal;

use App\Request\RequestAbstract;

/**
 * 提现审核验证器
 *
 * @author 
 * @package App\Request\UserWithdrawal
 */
class AuditRequest extends RequestAbstract
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => 'required',
            'status' => 'required|in:1,2',
        ];
    }
}