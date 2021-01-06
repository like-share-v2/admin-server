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
 * IPayIndia
 *
 * @author
 * @package App\Kernel\Payment
 */
class IPayIndia implements PayInterface
{
    /**
     * @var string
     */
    const BASE_URL = 'https://testapi.ipayindian.com/';

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
        // 将所有的参数装入数组
        $params         = [
            'userNo'  => $extra['user_id'],
            'orderId' => $pay_no,
            'amount'  => $amount
        ];
        $params['sign'] = $this->getSign($params, env('IPAY_INDIA_KEY', ''));
        $request        = $this->guzzle->create()->post(self::BASE_URL . 'v1/pay', [
            'headers' => [
                'Content-Type' => 'application/json;charset=utf-8',
                'keyId'        => env('IPAY_INDIA_ID', '')
            ],
            'json'    => $params
        ]);
        $data           = Json::decode($request->getBody()->getContents(), true);
        if (!isset($data['result']) || $data['result'] !== 2) {
            throw new LogicException($data['msg'] ?? 'error');
        }

        return [
            'payUrl' => $data['data']
        ];
    }

    /**
     * 统一下单接口
     *
     * @param string $pay_no
     * @param float  $amount
     * @param string $user_no
     * @param string $account_name
     * @param string $bank_account
     * @param string $ifsc
     */
    public function payout(
        string $pay_no,
        float $amount,
        string $user_no,
        string $account_name,
        string $bank_account,
        string $ifsc
    )
    {
        // 将所有的参数装入数组
        $params         = [
            'userNo'      => $user_no,
            'orderId'     => $pay_no,
            'accountName' => $account_name,
            'accountNo'   => $bank_account,
            'ifsc'        => $ifsc,
            'amount'      => $amount
        ];
        $params['sign'] = $this->getSign($params, env('IPAY_INDIA_KEY', ''));
        $request        = $this->guzzle->create()->post(self::BASE_URL . 'v1/withdrawal', [
            'headers' => [
                'Content-Type' => 'application/json;charset=utf-8',
                'keyId'        => env('IPAY_INDIA_ID', '')
            ],
            'json'    => $params
        ]);
        $data           = Json::decode($request->getBody()->getContents(), true);
        if (!isset($data['result']) || $data['result'] !== 1) {
            throw new LogicException($data['msg'] ?? 'error');
        }
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
        $str = '';
        foreach ($data as $v) {
            $str .= $v;
        }
        return md5(urldecode($str . $key));
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
}