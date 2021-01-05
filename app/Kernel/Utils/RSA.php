<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Kernel\Utils;

/**
 * RSA加密解密
 *
 * @author
 * @package App\Kernel\Utils
 */
class RSA
{
    /**
     * 私钥签名
     *
     * @param string $data
     * @param string $privateKey
     *
     * @return string|null
     */
    public static function privateKeySign(string $data, string $privateKey): ?string
    {
        $privatePem = chunk_split($privateKey, 64, "\n");
        $privateKey = "-----BEGIN PRIVATE KEY-----\n" . $privatePem . "-----END PRIVATE KEY-----\n";

        $res = openssl_pkey_get_private($privateKey);
        openssl_sign($data, $signature, $res);
        openssl_free_key($res);

        return base64_encode($signature);
    }

    /**
     * 公钥加密
     *
     * @param string $data
     * @param string $publicKey
     *
     * @return string
     */
    public static function publicEncrypt(string $data, string $publicKey)
    {
        $privatePem = chunk_split($publicKey, 64, "\n");
        $publicKey  = "-----BEGIN PUBLIC KEY-----\n" . $privatePem . "-----END PUBLIC KEY-----\n";

        $res       = openssl_pkey_get_public($publicKey);
        $encrypted = '';
        foreach (str_split($data, 128) as $chunk) {
            $partialEncrypted = '';
            //公钥加密
            $encryptionOk = openssl_public_encrypt($chunk, $partialEncrypted, $publicKey);
            if ($encryptionOk === false) {
                return false;
            }

            $encrypted .= $partialEncrypted;
        }
        openssl_free_key($res);
        return base64_encode($encrypted);
    }
}