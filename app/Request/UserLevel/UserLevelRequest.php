<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Request\UserLevel;

use App\Request\RequestAbstract;

/**
 * 用户等级验证器
 *
 * @author 
 * @package App\Request\UserLevel
 */
class UserLevelRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'level'                   => 'required',
            'name'                    => 'required',
            'icon'                    => 'required|max:255',
            'price'                   => 'required',
            'task_num'                => 'required|gt:0',
            'recharge_p_one_rebate'   => 'required|numeric',
            'recharge_p_two_rebate'   => 'required|numeric',
            'recharge_p_three_rebate' => 'required|numeric',
            'task_p_one_rebate'       => 'required|numeric|between:0,100|integer',
            'task_p_two_rebate'       => 'required|numeric|between:0,100|integer',
            'task_p_three_rebate'     => 'required|numeric|between:0,100|integer',
            'day'                     => 'required',
            'hour'                    => 'required',
            'minute'                  => 'required'
        ];
    }
}