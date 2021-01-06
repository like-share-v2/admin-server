<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Exception\LogicException;
use App\Kernel\Payment\DSEDPay;
use App\Kernel\Payment\HZPay;
use App\Kernel\Payment\JasonBagPay;
use App\Kernel\Payment\LinkPay;
use App\Kernel\Payment\SeproPay;
use App\Kernel\Payment\ShineUPay;
use App\Kernel\Payment\YT2Pay;
use App\Kernel\Payment\YTPay;
use App\Kernel\Payment\YZPay;
use App\Kernel\Utils\JwtInstance;
use App\Service\DAO\DefrayDAO;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserNotifyDAO;
use App\Service\DAO\UserWithdrawalDAO;

use Hyperf\DbConnection\Db;
use Hyperf\Snowflake\IdGeneratorInterface;

/**
 * 用户提现服务
 *
 * @author  
 * @package App\Service
 */
class UserWithdrawalService extends Base
{
    /**
     * 提现审核
     *
     * @param array $params
     */
    public function audit(array $params)
    {
        // 查找用户提现记录
        $user_withdrawal = $this->container->get(UserWithdrawalDAO::class)->findById((int)$params['id']);
        if (!$user_withdrawal) {
            $this->error('logic.USER_WITHDRAWAL_NOT_FOUND');
        }

        // 判断提现状态
        if ($user_withdrawal->status !== 0) {
            $this->error('logic.WITHDRAWAL_IS_NOT_TO_BE_REVIEWED');
        }

        // 查找提现用户
        $user = $this->container->get(MemberDAO::class)->findUserById($user_withdrawal->user_id);
        if (!$user) {
            $this->error('logic.WITHDRAWAL_USER_NOT_FOUND');
        }

        $user_withdrawal->admin_id = JwtInstance::instance()->build()->getId();
        $user_withdrawal->remark   = trim($params['remark'] ?? '');
        // 操作
        Db::beginTransaction();
        try {
            switch ((int)$params['status']) {
                case 2: // 拒绝
                    $user_withdrawal->status = 2;
                    $user_withdrawal->save();

                    // 记录账单
                    $this->container->get(UserBillDAO::class)->create([
                        'user_id'        => $user->id,
                        'type'           => 5,
                        'balance'        => $user_withdrawal->amount,
                        'before_balance' => $user->balance,
                        'after_balance'  => $user->balance + $user_withdrawal->amount
                    ]);

                    // 返还提现金额
                    $user->increment('balance', $user_withdrawal->amount);
                    // 返还积分
                    if ($user_withdrawal->integral > 0) {
                        $user->increment('integral', $user_withdrawal->integral);
                    }

                    // 添加用户通知
                    $this->container->get(UserNotifyDAO::class)->create([
                        'type'    => 1,
                        'user_id' => $user->id,
                        'title'   => 'system_notification',
                        'content' => 'Withdrawal failed'
                    ]);
                    break;

                case 1: // 处理中
                    $user_withdrawal->status = 3;
                    $user_withdrawal->save();

                    // 创建代付记录
                    $defray = $this->container->get(DefrayDAO::class)->create([
                        'country_id'    => 0,
                        'order_no'      => (string)$this->container->get(IdGeneratorInterface::class)->generate(),
                        'admin_id'      => JwtInstance::instance()->build()->getId(),
                        'channel'       => $params['pay_channel'],
                        'amount'        => $user_withdrawal->amount - $user_withdrawal->service_charge,
                        'name'          => $user_withdrawal->name,
                        'bank_account'  => $user_withdrawal->account,
                        'bank_name'     => $user_withdrawal->bank_name,
                        'bank_code'     => $user_withdrawal->bank_code,
                        'open_province' => '',
                        'open_city'     => '',
                        'status'        => 0,
                        'withdrawal_id' => $user_withdrawal->id,
                        'phone'         => $user_withdrawal->phone,
                        'email'         => $user_withdrawal->email,
                        'ifsc'          => $user_withdrawal->ifsc,
                        'upi'           => $user_withdrawal->upi,
                    ]);

                    switch ($params['pay_channel']) {
                        case 'hzPay':
                            switch (getConfig('HzPayCountryCode')) {
                                case 'IND':
                                    $bank_code = 'DEFAULT';
                                    break;

                                default:
                                    $bank_code = $user_withdrawal->bank_code;
                            }
                            $this->container->get(HZPay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $user_withdrawal->account,
                                $user_withdrawal->name,
                                $bank_code,
                                $user_withdrawal->ifsc
                            );
                            break;

                        case 'ytPay':
                            $this->container->get(YTPay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $defray->bank_name,
                                $defray->name,
                                $defray->bank_account,
                                $user->phone,
                                $defray->bank_code
                            );
                            break;

                        case 'yt2Pay':
                            $this->container->get(YT2Pay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $defray->bank_name,
                                $defray->name,
                                $defray->bank_account,
                                $defray->phone,
                                $defray->ifsc,
                                ($defray->email ?? $defray->phone . '@gmail.com')
                            );
                            break;

                        case 'linkPay':
                            $this->container->get(LinkPay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $defray->name,
                                $defray->bank_account,
                                $defray->bank_code,
                                getConfig('linkPayAccountNo', '')
                            );
                            break;

                        case 'dsedPay':
                            $this->container->get(DSEDPay::class)->payout(
                                $defray->order_no,
                                $defray->bank_account,
                                $defray->amount,
                                $defray->name,
                                $defray->phone,
                                $defray->bank_code,
                                $defray->ifsc
                            );
                            break;

                        case 'ShineUPay':
                            $this->container->get(ShineUPay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $defray->bank_account,
                                $defray->name,
                                $defray->phone,
                                ($defray->email ?? 'adsadamin@gmail.com'),
                                $defray->ifsc
                            );
                            break;

                        case 'JasonBagPay':
                            $this->container->get(JasonBagPay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $defray->bank_account,
                                $defray->name,
                                $defray->bank_code
                            );
                            break;

                        case 'SeproPay':
                            $this->container->get(SeproPay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $defray->bank_account,
                                $defray->name,
                                $defray->bank_code,
                                $defray->ifsc
                            );
                            break;

                        case 'YZPay':
                            $this->container->get(YZPay::class)->payout(
                                $defray->order_no,
                                $defray->amount,
                                $defray->bank_account,
                                $defray->name,
                                $defray->bank_code,
                                $defray->phone
                            );
                            break;

                        case 'null':
                            $defray->status          = 1;
                            $user_withdrawal->status = 1;
                            $defray->save();
                            $user_withdrawal->save();
                            break;

                        default:
                            throw new LogicException('暂未配置');
                    }
                    break;
                default:
                    throw new \Exception(__('logic.WITHDRAWAL_RESULT_ERROR'));
            }
            Db::commit();
        }
        catch (\Exception $e) {
            Db::rollBack();
            $this->logger('userWithdrawal')->error($e->getMessage());
            $this->error($e->getMessage());
        }
    }
}