<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Model\MFIncome;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * MFIncomeDAO
 *
 * @author
 * @package App\Service\DAO
 */
class MFIncomeDAO
{
    public function get(array $params)
    {
        $model = MFIncome::query();

        if (array_keys_exists(['account', 'phone'], $params)) {
            $model->whereHas('user', function (Builder $builder) use ($params) {
                if (isset($params['account'])) {
                    $builder->where('account', $params['account']);
                }
                if (isset($params['phone'])) {
                    $builder->where('phone', $params['phone']);
                }
            });
        }

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (array_keys_exists(['order_no'], $params)) {
            $model->whereHas('order', function (Builder $builder) use ($params) {
                if (isset($params['order_no'])) {
                    $builder->where('order_no', $params['order_no']);
                }
            });
        }

        if (isset($params['record_time'])) {
            $model->whereBetween('record_time', $params['record_time']);
        }

        return $model->with(['order' => function (BelongsTo $builder) {
            $builder->select('id', 'order_no');
        }])->with(['user' => function (BelongsTo $builder) {
            $builder->select('id', 'account', 'phone');
        }])->orderByDesc('record_time')->orderBy('id')->paginate((int)($params['perPage'] ?? 15));
    }
}