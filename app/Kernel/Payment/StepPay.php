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
 * StepPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class StepPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://gw1.giverupeeapp.vip/';

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
    }

    /**
     * 代付接口
     *
     * @param string $pay_no
     * @param float  $amount
     * @param string $email
     * @param string $phone
     * @param string $ifsc
     * @param string $bank_account
     * @param string $name
     *
     * @return mixed
     */
    public function payout(
        string $pay_no,
        float $amount,
        string $email,
        string $phone,
        string $ifsc,
        string $bank_account,
        string $name
    )
    {
        // 将所有的参数装入数组
        $params         = [
            'merchantNo'  => env('STEP_PAY_ID'),
            'outPayNo'    => $pay_no,
            'amount'      => $amount * 100,
            'email'       => $email,
            'phone'       => $phone,
            'ifscCode'    => $ifsc,
            'cardNo'      => $bank_account,
            'accountName' => $name,
        ];
        $params['sign'] = $this->getSign($params, env('STEP_PAY_PRIVATE_KEY', ''));

        // 采用post form data的形式传递参数
        $request = $this->guzzle->create()->post(self::BASE_URL . 'native/payoutPay/1.0.0', [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8'
            ],
            'form_params' => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (!isset($data['status']) || $data['status'] !== 'PROCESSING') {
            throw new LogicException($data['errorMsg'] ?? 'error');
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
     * @param array  $data
     * @param string $privateKey
     *
     * @return string
     */
    public function getSign(array $data, string $privateKey)
    {
        ksort($data);
        $signStr = http_build_query($data);
        //生成 sha1WithRSA 签名
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        $key        = openssl_get_privatekey($privateKey);
        openssl_sign(urldecode($signStr), $signature, $key);
        openssl_free_key($key);
        $sign = base64_encode($signature);
        return $sign;
    }
}