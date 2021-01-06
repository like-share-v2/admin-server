<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Kernel\Payment;

use App\Exception\LogicException;

use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;

/**
 * DSEDPay
 *
 * @author  Zunea
 * @package App\Kernel\Payment
 */
class DSEDPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://api.jfynp168.com/';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ClientFactory
     */
    private $guzzle;

    /**
     * GagaPay constructor.
     *
     * @param ContainerInterface $container
     * @param ClientFactory      $guzzle
     */
    public function __construct(ContainerInterface $container, ClientFactory $guzzle)
    {
        $this->container = $container;
        $this->guzzle    = $guzzle;
    }

    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param float  $amount
     * @param array  $extra
     *
     * @return mixed
     */
    public function pay(string $pay_no, float $amount, array $extra = [])

    {
        $params         = [
            'appid'        => env('DSED_PAY_ID'),
            'out_trade_no' => $pay_no,
            'version'      => 'v2.0',
            'pay_type'     => 'upi',
            'amount'       => number_format($amount, 2),
            'callback_url' => config('app_host') . 'v1/notify/dsed_pay'
        ];
        $params['sign'] = $this->getSign($params, env('DSED_PAY_KEY'));

        $request = $this->guzzle->create()->post(self::BASE_URL . 'index/unifiedorder?format=json', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 200) {
            throw new LogicException($data['msg']);
        }

        return [
            'payUrl' => $data['url']
        ];
    }

    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param string $bank_account
     * @param float  $amount
     * @param string $user_name
     * @param string $user_mobile
     *
     * @param string $bank_code
     * @param string $ifsc
     *
     * @return mixed
     */
    public function payout(
        string $pay_no,
        string $bank_account,
        float $amount,
        string $user_name,
        string $user_mobile,
        string $bank_code,
        string $ifsc
    )
    {
        $params         = [
            'appid'        => env('DSED_PAY_ID'),
            'out_trade_no' => $pay_no,
            'bank_type'    => 'upi',
            'account'      => $bank_account,
            'money'        => number_format($amount, 2),
            'name'         => $user_name,
            'remark'       => $user_mobile,
            'callback'     => config('app_host') . 'v1/notify/dsed_payout',
            'bank_id' => $bank_code,
            'ifsc' => $ifsc
        ];
        $params['sign'] = $this->getSign($params, env('DSED_PAY_KEY'));

        $request = $this->guzzle->create()->post(self::BASE_URL . 'withdrawal/creatWithdrawal', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'form_params' => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 200) {
            throw new LogicException($data['msg']);
        }

        return $data;
    }

    /**
     * 验证签名
     *
     * @param array  $data
     * @param string $sign
     */
    public function verifySign(array $data, string $sign)
    {
        // TODO: Implement verifySign() method.
    }

    /**
     * 获取签名
     *
     * @param array  $data
     * @param string $key
     *
     * @return string
     */
    public function getSign(array $data, string $key)
    {
        ksort($data);
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });

        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}