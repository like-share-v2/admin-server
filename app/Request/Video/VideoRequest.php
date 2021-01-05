<?php

declare(strict_types=1);

namespace App\Request\Video;

use App\Request\RequestAbstract;

/**
 * @package App\Request\Video
 */
class VideoRequest extends RequestAbstract
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'required',
            'video' => 'required',
            'sort'  => 'required'
        ];
    }
}