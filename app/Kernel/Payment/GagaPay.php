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
 * GagaPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class GagaPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'http://www.mesongogo.com/';

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
        $params  = [
            'merchant'   => env('GAGA_PAY_MERCHANT', ''),
            'outTradeNo' => $pay_no,
            'type'       => $extra['type'],
            'money'      => $amount,
            'time'       => time(),
            'notifyUrl'  => env('HOST') . 'notify/gaga_pay',
            // 'channelName' => 'CG_EASEBUZZ'
        ];
        $request = $this->guzzle->create()->post(self::BASE_URL . 'api/orderApi/new', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
            ],
            'form_params' => array_merge($params, [
                'sign' => $this->getSign($params, env('GAGA_PAY_KEY', ''))
            ])
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 'success') {
            throw new LogicException($data['success']);
        }
        return $data;
    }

    /**
     * 代付接口
     *
     * @param string $pay_no
     * @param string $pay_mode
     * @param float  $amount
     * @param string $account_name
     * @param string $bank_account
     * @param string $bank_code
     * @param string $vpa
     * @param string $phone
     * @param string $email
     * @param string $address
     *
     * @return array
     */
    public function payOut(
        string $pay_no,
        string $pay_mode,
        float $amount,
        string $account_name,
        string $bank_account,
        string $bank_code,
        string $vpa,
        string $phone,
        string $email,
        string $address
    )
    {
        $params  = [
            'merchant'    => env('GAGA_PAY_MERCHANT', ''),
            'outTradeNo'  => $pay_no,
            'payMode'     => $pay_mode,
            'amount'      => $amount,
            'accountName' => $account_name,
            'bankAccount' => $bank_account,
            'ifsc'        => $bank_code,
            'vpa'         => $vpa,
            'phone'       => $phone,
            'email'       => $email,
            'address'     => $address,
            'notifyUrl'   => env('HOST') . 'notify/gaga_payout'
        ];
        $request = $this->guzzle->create()->post(self::BASE_URL . 'api/payout/create', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
            ],
            'form_params' => array_merge($params, [
                'sign' => $this->getSign($params, env('GAGA_PAY_KEY', ''))
            ])
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 'success') {
            throw new LogicException($data['message']);
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
    private function getSign(array $data, string $key)
    {
        ksort($data);
        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}