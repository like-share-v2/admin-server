<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Request\Task;

use App\Request\RequestAbstract;

/**
 * 任务内容验证器
 *
 * @author 
 * @package App\Request\Task
 */
class CategoryContentRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id'      => 'required',
            'type'    => 'required|in:1,2'
        ];
    }
}