<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserRelation;

/**
 * 用户关系DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserRelationDAO extends Base
{
    public function getUserLowerIds(int $parent_id)
    {
        return UserRelation::query()->where('parent_id', $parent_id)->select(['user_id'])->get();
    }

    /**
     * 通过上级ID获取下级列表
     *
     * @param int $parent_id
     * @return mixed
     */
    public function getLowersByParentId(int $parent_id)
    {
        return UserRelation::query()->with(['user' => function ($query) {
            $query->select(['id', 'parent_id', 'level', 'account', 'phone', 'nickname', 'avatar', 'gender', 'balance', 'credit', 'status', 'last_login_time', 'created_at']);
        }])->where('parent_id', $parent_id)->get();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserRelation::query()->with(['user']);

        if (isset($params['parent_id'])) {
            $model->where('parent_id', $params['parent_id']);
        }

        if (isset($params['level'])) {
            $model->where('level', $params['level']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    public function getTeamLevelByParentId(int $parent_id)
    {
        return array_column(UserRelation::query()->where('parent_id', $parent_id)->select(['level'])->groupBy('level')->get()->toArray(), 'level', 'level');
    }

    /**
     * 通过用户ID删除用户关联数据
     *
     * @param int $user_id
     * @return int|mixed
     */
    public function deleteByUserId(int $user_id)
    {
        return UserRelation::query()->where('user_id', $user_id)->delete();
    }

    /**
     * 检查用户是否存在关联关系
     *
     * @param int $user_id
     * @param int $parent_id
     * @return bool
     */
    public function existByUserIdParentId(int $user_id, int $parent_id)
    {
        return UserRelation::query()->where('user_id', $user_id)->where('parent_id', $parent_id)->exists();
    }
}