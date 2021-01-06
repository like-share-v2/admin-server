<?php
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Kernel\Payment;

/**
 * 微信支付
 *
 * @author
 * @package App\Kernel\Payment
 */
interface PayInterface
{
    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param float $amount
     * @param array $extra
     * @return mixed
     */
    public function pay(string $pay_no, float $amount, array $extra = []);

    /**
     * 验证签名
     *
     * @param array $data
     * @param string $sign
     */
    public function verifySign(array $data, string $sign);
}