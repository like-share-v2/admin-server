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
 * PopMode
 *
 * @author
 * @package App\Kernel\Payment
 */
class PopModePay implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://api.popmode.in/';

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
     *
     * @param string $address
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
        string $address
    )
    {
        $params                       = [
            'mchid'              => env('POP_MODE_PAY_ID'),
            'out_trade_no'       => $pay_no,
            'money'              => $amount,
            'beneficiaryIFSC'    => $ifsc,
            'beneficiaryName'    => $name,
            'beneficiaryAccount' => $bank_account,
            'beneficiaryPhoneNo' => $phone,
        ];
        $params['sign']               = $this->getSign($params, env('POP_MODE_PAY_KEY'));
        $params['beneficiaryContact'] = $phone;
        $params['beneficiaryEmail']   = $email;
        $params['beneficiaryAddress'] = $address;
        $params['notifyurl']          = config('app_host') . 'v1/notify/popmodepayout';

        $request = $this->guzzle->create()->post(self::BASE_URL . 'Payment_Payout_Add.html', [
            'headers'     => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'form_params' => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['status'] ?? '') !== 'SUCCESS') {
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