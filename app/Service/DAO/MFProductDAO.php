<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Model\MFMode;

/**
 * MFProductDAO
 *
 * @author
 * @package App\Service\DAO
 */
class MFProductDAO
{
    public function get(array $params)
    {
        $model = MFMode::query();

        if (isset($params['mode'])) {
            $model->where('mode', $params['mode']);
        }

        if (isset($params['income_mode'])) {
            $model->where('income_mode', $params['income_mode']);
        }

        if (isset($params['is_enable'])) {
            $model->where('is_enable', $params['is_enable']);
        }

        if (isset($params['title'])) {
            $model->where('title', 'like', '%' . $params['title'] . '%');
        }

        return $model->orderByDesc('id')->paginate((int)($params['perPage'] ?? 15));
    }

    /**
     * @param array $data
     *
     * @return MFMode
     */
    public function create(array $data): ?MFMode
    {
        return MFMode::create($data);
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return int
     */
    public function update(int $id, array $data): int
    {
        return MFMode::where('id', $id)->update($data);
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function delete(int $id): int
    {
        return MFMode::where('id', $id)->delete();
    }
}