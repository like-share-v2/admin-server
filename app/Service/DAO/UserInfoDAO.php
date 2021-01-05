<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserInfo;

/**
 * 用户信息DAO
 *
 * @package App\Service\DAO
 */
class UserInfoDAO extends Base
{
    /**
     * 添加用户信息
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserInfo::query()->create($data);
    }

    public function firstByUserId(int $user_id)
    {
        return UserInfo::query()->where('user_id', $user_id)->first();
    }

    /**
     * 检查字段是否存在
     *
     * @param int $user_id
     * @param string $column
     * @param string $value
     * @return bool
     */
    public function checkColumnExisted(int $user_id, string $column, string $value)
    {
        return UserInfo::query()->where('user_id', '<>', $user_id)->where($column, $value)->exists();
    }
}