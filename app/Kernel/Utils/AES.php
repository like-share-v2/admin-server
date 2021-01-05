<?php

declare(strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Kernel\Utils;

/**
 * AES加密
 *
 * @package App\Kernel\Util
 */
class AES
{
    /**
     * AES加密
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    public static function encrypt(string $data, string $key, string $iv = '')
    {
        return base64_encode(openssl_encrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv));
    }

    /**
     * AES解密
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return false|string
     */
    public static function decrypt(string $data, string $key, string $iv)
    {
        return openssl_decrypt($data, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}