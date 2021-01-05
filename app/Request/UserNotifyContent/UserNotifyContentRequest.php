<?php

declare(strict_types=1);

namespace App\Request\UserNotifyContent;

use App\Request\RequestAbstract;

/**
 * @package App\Request\HelpContent
 */
class UserNotifyContentRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'notify_id' => 'required',
            'locale' => 'required',
            'content' => 'required'
        ];
    }
}