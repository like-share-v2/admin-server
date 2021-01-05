<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Model\MFOrder;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * MFOrderDAO
 *
 * @author
 * @package App\Service\DAO
 */
class MFOrderDAO
{
    /**
     * @param array $params
     *
     * @return LengthAwarePaginatorInterface
     */
    public function get(array $params)
    {
        $model = MFOrder::query();

        if (isset($params['mode'])) {
            $model->where('mode', $params['mode']);
        }

        if (isset($params['income_mode'])) {
            $model->where('income_mode', $params['income_mode']);
        }

        if (isset($params['is_settle'])) {
            $model->where('is_settle', $params['is_settle']);
        }

        if (isset($params['title'])) {
            $model->where('mode_title', 'like', '%' . $params['title'] . '%');
        }

        if (isset($params['order_no'])) {
            $model->where('order_no', $params['order_no']);
        }

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

        if (isset($params['unfreeze_time'])) {
            $model->whereBetween('unfreeze_time', $params['unfreeze_time']);
        }

        if (isset($params['settle_time'])) {
            $model->whereBetween('settle_time', $params['settle_time']);
        }

        if (isset($params['created_at'])) {
            $model->whereBetween('created_at', $params['created_at']);
        }

        return $model->with(['user' => function (BelongsTo $builder) {
            $builder->select('id', 'account', 'phone');
        }
        ])->orderByDesc('created_at')->orderBy('id')->paginate((int)($params['perPage'] ?? 15));
    }

    /**
     * 获取理财余额
     *
     * @param int $user_id
     *
     * @return mixed
     */
    public function getMfCoin(int $user_id)
    {
        return MFOrder::where('user_id', $user_id)->sum('amount') + MFOrder::where('user_id', $user_id)->sum('profit');
    }
}