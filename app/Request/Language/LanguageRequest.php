<?php

declare(strict_types=1);

namespace App\Request\Language;

use App\Request\RequestAbstract;

/**
 * 语言验证器
 *
 * @package App\Request\Language
 */
class LanguageRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'key' => 'required',
            'local' => 'required',
            'value' => 'required'
        ];
    }
}