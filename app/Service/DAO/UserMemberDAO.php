<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserMember;

use Hyperf\Database\Model\Model;

/**
 * @package App\Service\DAO
 */
class UserMemberDAO extends Base
{
    /**
     * 添加或更新用户会员等级
     *
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate(array $data)
    {
        return UserMember::query()->updateOrCreate([
            'user_id' => $data['user_id'],
            'level' => $data['level']
        ], [
            'effective_time' => $data['effective_time']
        ]);
    }

    /**
     * 通过用户ID获取等级列表
     *
     * @param int $user_id
     * @return mixed
     */
    public function getListByUserId(int $user_id)
    {
        return UserMember::query()->with(['userLevel:level,name,duration'])->where('user_id', $user_id)
            ->get();
    }

    /**
     * 通过用户ID和等级获取用户等级
     *
     * @param int $user_id
     * @param int $level
     * @return mixed
     */
    public function firstByUserIdLevel(int $user_id, int $level): ?UserMember
    {
        return UserMember::query()->where('user_id', $user_id)->where('level', $level)
            ->first();
    }

    /**
     * 获取用户最大等级
     *
     * @param int $user_id
     * @return mixed
     */
    public function getUserMaxLevel(int $user_id)
    {
        return UserMember::query()->where('user_id', $user_id)
            // ->where('level', '<>', 7)
            // ->where('level', '<>', 8)
            ->max('level');
    }

    /**
     * @param array $data
     *
     * @return UserMember|Model
     */
    public function create(array $data)
    {
        return UserMember::create($data);
    }
}