<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Request\RechargeQrCode;

use App\Request\RequestAbstract;

/**
 * 充值二维码验证器
 *
 * @author
 * @package App\Request\RechargeQrCode
 */
class QrCodeRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image'  => 'required',
            'status' => 'required|in:0,1'
        ];
    }
}