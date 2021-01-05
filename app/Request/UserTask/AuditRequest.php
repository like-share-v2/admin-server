<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\UserTask;

use App\Request\RequestAbstract;

/**
 * 审核任务验证器
 *
 * @author 
 * @package App\Request\UserTask
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
            'status' => 'required|in:2,3',
        ];
    }
}