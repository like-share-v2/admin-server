<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use App\Exception\LogicException;
use App\Kernel\Payment\DSEDPay;
use App\Kernel\Payment\HZPay;
use App\Kernel\Payment\IPay;
use App\Kernel\Payment\IPayIndia;
use App\Kernel\Payment\LinkPay;
use App\Kernel\Payment\PopModePay;
use App\Kernel\Payment\ShineUPay;
use App\Kernel\Payment\StepPay;
use App\Kernel\Payment\YT2Pay;
use App\Kernel\Payment\YTPay;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\DefrayDAO;

use Hyperf\DbConnection\Db;

/**
 * @package App\Service
 */
class DefrayService extends Base
{
    public function defray(array $params)
    {
        // $defray_country = $this->container->get(CountryDAO::class)->firstById((int)$params['country_id']);

        Db::beginTransaction();
        try {
            $date     = date('YmdHis');
            $order_no = 'defray' . $date . mt_rand(1000, 9999);

            $defray = $this->container->get(DefrayDAO::class)->create([
                'country_id'    => 0,
                'order_no'      => $order_no,
                'admin_id'      => JwtInstance::instance()->build()->getId(),
                'channel'       => getConfig('pay_channel', ''),
                'amount'        => (int)$params['amount'],
                'name'          => $params['name'],
                'bank_account'  => $params['bank_account'],
                'bank_name'     => $params['bank_name'],
                'bank_code'     => $params['bank_code'],
                'open_province' => $params['open_province'] ?? '',
                'open_city'     => $params['open_city'] ?? '',
                'status'        => 0
            ]);

            switch (getConfig('pay_channel', '')) {
                case 'ytPay':
                    $this->container->get(YTPay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['bank_name'],
                        $params['name'],
                        $params['bank_account'],
                        $params['user_mobile'],
                        $params['bank_code']
                    );
                    break;

                case 'dsedPay':
                    $this->container->get(DSEDPay::class)->payout(
                        $order_no,
                        $params['bank_type'],
                        $params['bank_account'],
                        (float)$params['amount'],
                        $params['name'],
                        $params['user_mobile'],
                        $params['bank_code'],
                        $params['ifsc']
                    );
                    break;

                case 'iPay':
                    $this->container->get(IPay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        'xxxx',
                        $params['name'],
                        $params['bank_account'],
                        $params['bank_name'],
                        $params['ifsc']
                    );
                    break;

                case 'linkPay':
                    $this->container->get(LinkPay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['name'],
                        $params['bank_account'],
                        $params['bank_code'],
                        getConfig('linkPayAccountNo', '')
                    );
                    break;

                case 'stepPay':
                    $this->container->get(StepPay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['user_email'],
                        $params['user_mobile'],
                        $params['ifsc'],
                        $params['bank_account'],
                        $params['name']
                    );
                    break;

                case 'iPayIndia':
                    $this->container->get(IPayIndia::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['name'],
                        $params['user_mobile'],
                        $params['bank_account'],
                        $params['ifsc']
                    );
                    break;

                case 'hzPay':
                    $this->container->get(HZPay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['bank_account'],
                        $params['name'],
                        $params['ifsc']
                    );
                    break;

                case 'popModePay':
                    $this->container->get(PopModePay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['name'],
                        $params['bank_account'],
                        $params['user_mobile'],
                        $params['ifsc'],
                        $params['user_email'],
                        ($params['user_address'] ?? 'test')
                    );
                    break;

                case 'yt2Pay':
                    $this->container->get(YT2Pay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['bank_name'],
                        $params['name'],
                        $params['bank_account'],
                        $params['user_mobile'],
                        $params['ifsc'],
                        ($params['user_email'] ?? 'admin@qq.com')
                    );
                    break;

                case 'ShineUPay':
                    $this->container->get(ShineUPay::class)->payout(
                        $order_no,
                        (float)$params['amount'],
                        $params['bank_account'],
                        $params['name'],
                        $params['user_mobile'],
                        ($params['user_email'] ?? 'admin@qq.com'),
                        $params['ifsc']
                    );
                    break;

                default:
            }

            Db::commit();
        }
        catch (LogicException $e) {
            Db::rollBack();
            $this->error($e->getMessage());
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('efuPay')->error($e->getMessage());
            $this->error($e->getMessage());
        }
    }
}