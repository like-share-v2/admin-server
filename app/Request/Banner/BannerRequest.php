<?php

declare(strict_types=1);

namespace App\Request\Banner;

use App\Request\RequestAbstract;

/**
 * 轮播验证器
 *
 * @package App\Request\Banner
 */
class BannerRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'image' => 'required',
            'sort' => 'required',
            'url' => 'required'
        ];
    }
}