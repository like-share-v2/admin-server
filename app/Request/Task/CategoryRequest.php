<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\Task;

use App\Request\RequestAbstract;

/**
 * 任务分类验证器
 *
 * @author 
 * @package App\Request\Task
 */
class CategoryRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'         => 'required',
            'icon'         => 'required',
            'banner'       => 'required',
            'lowest_price' => 'required|gt:0',
            'sort'         => 'required|between:0,999999',
            'status'       => 'required|in:0,1'
        ];
    }
}