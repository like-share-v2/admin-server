<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Request\Account;

use App\Request\RequestAbstract;

/**
 * 修改密码验证器
 *
 * @author
 * @package App\Request\Account
 */
class ChangePasswordRequest extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'old_password'              => 'required|alpha_dash|between:6,30',
            'new_password'              => 'required|confirmed|alpha_dash|between:6,30',
            'new_password_confirmation' => 'required',
        ];
    }
}