<?php

declare(strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version   1.0.0
 * @link      https://github.com/Zunea/hyperf-admin
 */

namespace App\Constants;

/**
 * 常量合集
 *
 * @author  
 * @package App\Constants
 */
class Constants
{
    /**
     * Authorization
     *
     * @var string
     */
    const AUTHORIZATION = 'Authorization';

    /**
     * Token 有效期
     *
     * @var int
     */
    const AUTHORIZATION_EXPIRE = 86400;

    /**
     * Token 提前续期时间
     *
     * @var integer
     */
    const AUTHORIZATION_RENEW = 2 * 60 * 60;

    /**
     * 鉴权中间件白名单
     *
     * @var array
     */
    const AUTH_WHITE_LIST = [
        '/admin/auth/login',
        '/admin/account/logout',
        '/admin/auth/sendSms',
        '/country_code',
        '/csv/download',
        '/admin/auth/google'
    ];

    /**
     * 权限验证白名单
     *
     * @var array
     */
    const PERMISSION_WHITE_LIST = [
        '/admin/account/info',
        '/admin/account/menu',
        '/admin/account/resource',
        '/admin/account/option',
        '/admin/account/password',
        '/admin/account/wstoken',
        '/csv/user',
        '/admin/account/google_auth_qrcode',
        '/admin/account/bind_google_auth',
        '/admin/account/switch_google_auth',
    ];

    /**
     * 文件类型白名单
     *
     * @var array
     */
    const UPLOADS_CONFIG = [
        [
            'directory' => 'images',
            'mime'      => ['image/jpg', 'image/jpeg', 'image/png', 'image/gif', 'image/bmp'],
            'maxSize'   => 10485760
        ],
        [
            'directory' => 'videos',
            'mime'      => ['video/mpeg', 'video/x-msvideo', 'video/mp4', 'application/mp4', 'video/x-flv', 'video/x-m4v', 'video/ogg', 'application/octet-stream', 'application/octet-stream', 'video/quicktime'],
            'maxSize'   => 104857600
        ]
    ];

    /**
     * 行为记录级别
     *
     * @var array
     */
    const BEHAVIOR_METHOD = ['POST', 'PUT', 'DELETE'];
}
