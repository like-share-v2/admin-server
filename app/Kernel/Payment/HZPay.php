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
 * HZPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class HZPay
{
    /**
     * @var string
     */
    const BASE_URL = 'http://sujary.fakgt.com/';

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

    public function payout(
        string $pay_no,
        float $amount,
        string $bank_account,
        string $name,
        string $bank_code,
        ?string $ifsc = null
    )
    {
        $params         = [
            'mer_no'       => env('HZ_PAY_ID'),
            'mer_order_no' => $pay_no,
            'acc_no'       => $bank_account,
            'acc_name'     => $name,
            'ccy_no'       => getConfig('HzPayCcyNo', 'THB'),
            'order_amount' => $amount,
            'bank_code'    => $bank_code,
            'province'     => $ifsc,
            'summary'      => 'withdrawal'
        ];
        $params['sign'] = $this->getSign($params, env('HZ_PAY_KEY'));

        $request = $this->guzzle->create()->post(self::BASE_URL . 'withdraw/singleOrder', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['status'] ?? '') !== 'SUCCESS') {
            throw new LogicException($data['err_msg']);
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
        ksort($data);
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });

        return strtoupper(md5(urldecode(http_build_query($data) . '&key=' . $key)));
    }
}