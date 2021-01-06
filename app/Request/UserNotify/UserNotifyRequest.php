<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Request\UserNotify;

use App\Request\RequestAbstract;

/**
 * 新闻验证器
 *
 * @author 
 * @package App\Request\UserNotify
 */
class UserNotifyRequest extends RequestAbstract
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
            'sort' => 'required|integer|gte:0'
        ];
    }
}