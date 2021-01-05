<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Model\YuebaoAccount;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * YuebaoAccountDAO
 *
 * @author
 * @package App\Service\DAO
 */
class YuebaoAccountDAO
{
    public function getAccountList(array $params)
    {
        $model = YuebaoAccount::query();

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

        return $model->with(['user' => function (BelongsTo $builder) {
            $builder->select('id', 'account', 'phone');
        }])->orderBy('user_id')->paginate((int)($params['perPage'] ?? 15));
    }

    /**
     * @param int $user_id
     *
     * @return mixed
     */
    public function getAccountByUserId(int $user_id): ?YuebaoAccount
    {
        return YuebaoAccount::find($user_id);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): ?YuebaoAccount
    {
        return YuebaoAccount::create($data);
    }

    /**
     * @param int   $user_id
     * @param array $data
     *
     * @return mixed
     */
    public function update(int $user_id, array $data)
    {
        return YuebaoAccount::where('user_id', $user_id)->update($data);
    }
}