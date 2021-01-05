<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Model\YuebaoCoinLog;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * YuebaoCoinLog
 *
 * @author
 * @package App\Service\DAO
 */
class YuebaoCoinLogDAO
{
    public function getCoinLog(array $params)
    {
        $model = YuebaoCoinLog::query();

        if (isset($params['type'])) {
            $model->where('type', $params['type']);
        }

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['record_time'])) {
            $model->whereBetween('record_time', $params['record_time']);
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

        return $model->with(['user' => function (BelongsTo $builder) {
            $builder->select('id', 'account', 'phone');
        }])->orderByDesc('record_time')->orderBy('id')->paginate((int)($params['perPage'] ?? 15));
    }

    /**
     * @param array $data
     *
     * @return YuebaoCoinLog
     */
    public function create(array $data)
    {
        return YuebaoCoinLog::create($data);
    }
}