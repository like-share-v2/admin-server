<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\Help;

use App\Request\RequestAbstract;

/**
 * 帮助手册验证器
 *
 * @author
 * @package App\Request\Help
 */
class HelpRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'content' => 'required',
            'status' => 'required|in:0,1',
            'sort' => 'required|integer|gte:0'
        ];
    }
}