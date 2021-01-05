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
 * JasonBagPay
 *
 * @author
 * @package
 */
class SeproPay
{
    /**
     * @var string
     */
    const BASE_URL = 'https://pay.sepropay.com/';

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
        ?string $ifsc
    )
    {
        $params              = [
            'mch_id'          => env('SEPRO_PAY_ID'),
            'mch_transferId'  => $pay_no,
            'transfer_amount' => $amount,
            'apply_date'      => date('Y-m-d H:i:s'),
            'bank_code'       => $bank_code,
            'receive_name'    => $name,
            'receive_account' => $bank_account,
            'remark'          => $ifsc,
            'back_url'        => config('app_host') . 'v1/notify/sepro_payout',
        ];
        $params['sign']      = $this->getSign($params, env('SEPRO_PAY_KEY'));
        $params['sign_type'] = 'MD5';

        $request = $this->guzzle->create()->post(self::BASE_URL . 'pay/transfer', [
            'form_params' => $params
        ]);
        $data    = Json::decode($request->getBody()->getContents(), true);
        if (($data['respCode'] ?? '') !== 'SUCCESS') {
            throw new LogicException($data['errorMsg']);
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
    public function getSign(array $data, string $key): string
    {
        unset($data['sign']);
        ksort($data);
        $data = array_filter($data, function ($item) {
            return $item !== '';
        });

        return md5(urldecode(http_build_query($data) . '&key=' . $key));
    }
}