<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\User;
use Hyperf\Cache\Annotation\CacheEvict;

/**
 * 用户DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class MemberDAO extends Base
{
    /**
     * 获取用户列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = User::query()->where('id', '<>', 0)
            ->with(['userInfo', 'country:id,name', 'yuebao', 'userMember']);

        if (isset($params['id']) && $params['id'] !== '') {
            $model->where('id', (int)$params['id']);
        }

        if (isset($params['type']) && $params['type'] !== '') {
            $model->where('type', (int)$params['type']);
        }

        if (isset($params['level']) && $params['level'] !== '') {
            $model->whereHas('userMember', function ($query) use ($params) {
                if ($params['level'] == -1) {
                    return $query->where('level', $params['level']);
                } else {
                    return $query->where('level', $params['level'])->where('effective_time', '>', time());
                }
            });
        }

        if (isset($params['account']) && $params['account'] !== '') {
            $model->where('account', trim($params['account']));
        }

        if (isset($params['phone']) && $params['phone'] !== '') {
            $model->where('phone', trim($params['phone']));
        }

        if (isset($params['nickname']) && $params['nickname'] !== '') {
            $model->where('nickname', trim($params['nickname']));
        }

        if (isset($params['status']) && $params['status'] !== '') {
            $model->where('status', (int)$params['status']);
        }

        if (isset($params['created_at']) && is_array($params['created_at']) && count($params['created_at']) === 2) {
            $model->whereBetween('created_at', [strtotime($params['created_at'][0]), strtotime($params['created_at'][1] . '23:59:59')]);
        }

        if (isset($params['search_time']) && is_array($params['search_time']) && count($params['search_time']) === 2) {
            $model->whereBetween('created_at', $params['search_time']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('created_at')->orderByDesc('id')->paginate((int)$params['perPage']) : $model->get();
    }

    public function getUsers()
    {
        return User::query()->with(['userInfo', 'country:id,name'])->orderBy('id', 'asc')->paginate(500);
    }

    public function getAllUsers()
    {
        return User::query()->get();
    }

    /**
     * 通过ID获取用户
     *
     * @param int $id
     * @return mixed
     */
    public function findUserById(int $id): ?User
    {
        return User::query()->where('id', $id)->first();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function first(int $id): ?User
    {
        return User::query()->with(['userInfo'])->where('id', $id)->first();
    }

    /**
     * 通过ID批量修改状态
     *
     * @CacheEvict(prefix="UserById", value="#{id}")
     * @param int $id
     * @param int $status
     * @return int
     */
    public function changeStatusById(int $id, int $status)
    {
        return User::query()->where('id', $id)->update([
            'status' => $status
        ]);
    }

    /**
     * 获取用户总数
     *
     * @return int
     */
    public function getMemberCount()
    {
        return User::query()->count();
    }

    public function getMemberCountByParams(array $params)
    {
        $model = User::query();

        if (isset($params['time'])) {
            $model->whereBetween('created_at', $params['time']);
        }

        return $model->count();
    }

    /**
     * 检测值是否被使用
     *
     * @param string $column
     * @param $value
     * @return bool
     */
    public function checkValueIsUsed(string $column, $value): bool
    {
        return User::query()->where($column, $value)->exists();
    }

    /**
     * 检测值是否被使用
     *
     * @param string $column
     * @param $value
     * @param int $user_id
     * @return bool
     */
    public function checkValueIsUsedExpectUserId(string $column, $value, int $user_id): bool
    {
        return User::query()->where('id', '<>', $user_id)->where($column, $value)->exists();
    }

    /**
     * 创建用户
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return User::query()->create($data);
    }

    public function getIpUserCount(string $ip)
    {
        return User::query()->where('ip', $ip)->count();
    }
}