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
 * CustomPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class CustomPay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://www.geejjian.com/';

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
     * @param string $name
     * @param string $bank_account
     * @param string $phone
     * @param string $ifsc
     * @param string $email
     * @param string $bank_name
     *
     * @return mixed
     */
    public function payout(
        string $pay_no,
        float $amount,
        string $name,
        string $bank_account,
        string $phone,
        string $ifsc,
        string $email,
        string $bank_name
    )
    {
        $params                = [
            'pay_memberid'   => env('CUSTOM_PAY_ID'),
            'out_trade_no'   => $pay_no,
            'card_number'    => $bank_account,
            'real_name'      => $name,
            'password'       => getConfig('CustomPayPassword'),
            'money'          => $amount,
            'bank_name'      => $bank_name,
            'bank_subbranch' => $bank_name,
            'mobile'         => $phone,
            'email'          => $email,
            'ifsc'           => $ifsc,
            'notifyurl'      => config('app_host') . 'v1/notify/custom_payout'
        ];
        $params['pay_md5sign'] = $this->getSign($params, env('CUSTOM_PAY_KEY'));

        $request = $this->guzzle->create()->post(self::BASE_URL . 'Payment_Payout_Add.html', [
            'form_params' => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['status'] ?? '') !== 'success') {
            throw new LogicException($data['msg']);
        }

        return $data;
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
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });
        ksort($data);

        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
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
        // TODO: Implement pay() method.
    }
}