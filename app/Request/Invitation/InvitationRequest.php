<?php

declare(strict_types=1);

namespace App\Request\Invitation;

use App\Request\RequestAbstract;

/**
 * 邀请函验证器
 *
 * @package App\Request\Invitation
 */
class InvitationRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => 'required',
            'locale' => 'required'
        ];
    }
}