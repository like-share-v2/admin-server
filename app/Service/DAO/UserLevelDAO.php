<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserLevel;
use Hyperf\Cache\Annotation\Cacheable;

/**
 * 用户等级DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserLevelDAO extends Base
{
    /**
     * 通过级别获取会员等级
     *
     * @param int $level
     * @return mixed
     */
    public function findByLevel(int $level): ?UserLevel
    {
        return UserLevel::query()->where('level', $level)->first();
    }

    /**
     * 通过ID获取会员等级
     *
     * @param int $id
     * @return mixed
     */
    public function findById(int $id): ?UserLevel
    {
        return UserLevel::query()->where('id', $id)->first();
    }

    /**
     * 获取等级列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserLevel::query();

        if (isset($params['rebate_type']) && in_array($params['rebate_type'], [1, 2])) {
            switch ($params['rebate_type']) {
                case 1:
                    $model->with('rechargeLevelRebate');
                    break;
                case 2:
                    $model->with('taskLevelRebate');
                    break;
            }
        } else {
            $model->with('levelRebate');
        }

        if (isset($params['level']) && $params['level'] !== '') {
            $model->where('level', (int)$params['level']);
        }

        if (isset($params['name']) && $params['name'] !== '') {
            $model->where('name', trim($params['name']));
        }

        return isset($params['perPage']) ? $model->orderBy('level', 'asc')->paginate((int)$params['perPage']) : $model->orderBy('level', 'asc')->get();
    }

    /**
     * 添加用户等级
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data): ?UserLevel
    {
        return UserLevel::query()->create($data);
    }

    /**
     * 删除会员等级
     *
     * @param array $ids
     * @return int
     */
    public function delete(array $ids): int
    {
        return Userlevel::where('level', '<>', -1)->whereIn('id', $ids)->delete();
    }

    /**
     * 获取各个等级用户数量
     *
     * @return mixed
     */
    public function getUserCount()
    {
        return UserLevel::query()->select(['name'])->get();
    }

    /**
     * @Cacheable(prefix="UserLevel", ttl=60)
     * @param string $name
     *
     * @return mixed
     */
    public function getLevelByLevelName(string $name)
    {
        return UserLevel::where('name', $name)->value('level');
    }
}