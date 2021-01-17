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
 * RunningPay
 *
 * @author
 * @package App\Kernel\Payment
 */
class RunningPay
{
    /**
     * @var string
     */
    const BASE_URL = 'http://loanmanager.in/';

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
     * @param string $pay_no
     * @param float  $amount
     * @param string $bank_account
     * @param string $name
     * @param string $ifsc
     *
     * @return mixed
     */
    public function payout(
        string $pay_no,
        float $amount,
        string $bank_account,
        string $name,
        string $ifsc
    )
    {
        $amount     = number_format($amount, 2, '.', '');
        $merchantId = '10091';
        $params     = [
            'merchantId'      => $merchantId,
            'merchantOrderId' => $pay_no,
            'amount'          => $amount,
            'timestamp'       => time() * 1000,
            'sign'            => md5('merchantId=' . $merchantId . '&merchantOrderId=' . $pay_no . '&amount=' . $amount . '&orjrFAkYFTPjcCBS'),
            'notifyUrl'       => config('app_host') . 'v1/notify/running_payout',
            'fundAccount'     => [
                'accountType' => 'bank_account',
                'bankAccount' => [
                    'ifsc'          => $ifsc,
                    'name'          => $name,
                    'accountNumber' => $bank_account,
                ]
            ]
        ];
        $request    = $this->guzzle->create()->post(self::BASE_URL . 'rpay-api/payout/submit', [
            'headers' => [
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'json'    => $params
        ]);
        $data       = Json::decode($request->getBody()->getContents(), true);
        if (($data['code'] ?? '') !== 0) {
            throw new LogicException($data['error']);
        }

        return $data;
    }
}