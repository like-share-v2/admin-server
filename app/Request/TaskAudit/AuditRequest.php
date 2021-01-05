<?php

declare(strict_types=1);

namespace App\Request\TaskAudit;

use App\Request\RequestAbstract;

/**
 * 审核任务验证器
 *
 * @package App\Request\TaskAudit
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
            'status' => 'required|in:1,2'
        ];
    }
}