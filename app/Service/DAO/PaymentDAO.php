<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Payment;

/**
 * 支付DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class PaymentDAO extends Base
{
    /**
     * 创建支付记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Payment::query()->create($data);
    }

    /**
     * 获取支付成功总金额
     *
     * @return int
     */
    public function getSuccessAmountSum()
    {
        return Payment::query()->where('status', 2)->sum('amount');
    }
}