<?php

declare(strict_types=1);

namespace App\Request\Help;

use App\Request\RequestAbstract;

/**
 * 帮助手册内容验证器
 *
 * @package App\Request\Help
 */
class HelpContentRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'help_id' => 'required',
            'locale' => 'required',
            'content' => 'required'
        ];
    }
}