<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Kernel\Payment;

use App\Exception\LogicException;

use Hyperf\Guzzle\ClientFactory;
use Hyperf\Utils\Codec\Json;
use Psr\Container\ContainerInterface;

/**
 * IPay
 *
 * @author  Zunea
 * @package App\Kernel\Payment
 */
class IPay implements PayInterface
{

    /**
     * @var string
     */
    const BASE_URL = 'http://ipay-in.yynn.me/';

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
            'api_name'     => 'quickpay.all.native',
            'notify_url'   => config('app_host') . 'v1/notify/ipay',
            'shop_id'      => env('IPAY_SHOP_ID'),
            'out_trade_no' => $pay_no,
            'money'        => $amount,
            'order_des'    => $pay_no
        ];
        $params['sign'] = $this->getSign($params, env('IPAY_KEY'));

        $request = $this->guzzle->create()->post(self::BASE_URL . 'pay', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'body'    => Json::encode($params)
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['rtn_code'] ?? '') !== 1000) {
            throw new LogicException($data['rtn_msg']);
        }

        return [
            'payUrl' => $data['native_url']
        ];
    }

    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param float  $amount
     * @param string $upi_account
     * @param string $user_name
     * @param string $bank_account
     * @param string $bank_name
     * @param string $ifsc
     *
     * @return mixed
     */
    public function payout(
        string $pay_no,
        float $amount,
        string $upi_account,
        string $user_name,
        string $bank_account,
        string $bank_name,
        string $ifsc
    )
    {
        $params         = [
            'shop_id'        => env('IPAY_SHOP_ID'),
            'out_trade_no'   => $pay_no,
            'money'          => $amount,
            'upi_id'         => $upi_account,
            'account_holder' => $user_name,
            'bank_number'    => $bank_account,
            'bank_name'      => $bank_name,
            'ifsc_code'      => $ifsc,
            'notify_url'     => config('app_host') . 'v1/notify/ipay_payout'
        ];
        $params['sign'] = $this->getSign($params, env('IPAY_KEY'));

        $request = $this->guzzle->create()->post(self::BASE_URL . 'withdrawal', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'body'    => Json::encode($params)
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['rtn_code'] ?? '') !== 1000) {
            throw new LogicException($data['rtn_msg']);
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

        return md5(urldecode(http_build_query($data) . $key));
    }
}