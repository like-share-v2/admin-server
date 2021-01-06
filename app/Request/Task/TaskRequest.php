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
 * 任务验证器
 *
 * @author 
 * @package App\Request\Task
 */
class TaskRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_id' => 'required',
            'level' => 'required',
            'title' => 'required|max:100',
            'description' => 'required',
            'url' => 'required|max:255',
            'amount' => 'required|numeric|gt:0',
            'num' => 'required|between:0,99999999',
            'sort' => 'required|numeric|between:0,9999999',
            'status' => 'required|in:0,1'
        ];
    }
}