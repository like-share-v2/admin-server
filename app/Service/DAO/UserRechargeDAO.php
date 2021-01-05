<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserRecharge;
use Hyperf\DbConnection\Db;

/**
 * 用户充值DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserRechargeDAO extends Base
{
    /**
     * 创建充值记录
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserRecharge::query()->create($data);
    }

    /**
     * 获取用户充值记录
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = UserRecharge::query()->with(['user:id,account,phone,nickname', 'userLevel:level,name', 'payment']);

        if (isset($params['user_id'])) {
            $model->where('user_id', $params['user_id']);
        }

        if (isset($params['user_phone'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('phone', $params['user_phone']);
            });
        }

        if (isset($params['user_account'])) {
            $model->whereHas('user', function ($query) use ($params) {
                $query->where('account', $params['user_account']);
            });
        }

        if (isset($params['level'])) {
            $model->where('level', $params['level']);
        }

        if (isset($params['channel'])) {
            $model->where('channel', $params['channel']);
        }

        if (isset($params['recharge_time'])) {
            $model->whereBetween('recharge_time', $params['recharge_time']);
        }

        if (isset($params['pay_no'])) {
            $model->whereHas('payment', function ($query) use ($params) {
                $query->where('pay_no', $params['pay_no']);
            });
        }

        if (isset($params['trade_no'])) {
            $model->whereHas('payment', function ($query) use ($params) {
                $query->where('trade_no', $params['trade_no']);
            });
        }

        return isset($params['perPage']) ? $model->orderByDesc('recharge_time')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 获取开通叠加会员人数
     *
     * @param array $params
     * @return int
     */
    public function getOverlayMemberCount(array $params)
    {
        $model = UserRecharge::query();

        if (isset($params['time'])) {
            $model->whereBetween('updated_at', $params['time']);
        }

        return $model->where('status', 1)->count(DB::raw('DISTINCT(user_id)'));
    }

    /**
     * 获取开通叠加会员IDS
     *
     * @param array $params
     * @return array
     */
    public function getOverLayMemberIds(array $params)
    {
        $model = UserRecharge::query();

        if (isset($params['time']) && is_array($params['time']) && count($params['time']) === 2) {
            $model->whereBetween('recharge_time', $params['time']);
        }

        return array_column($model->where('status', 1)->groupBy('user_id')->get()->toArray(), 'user_id');
    }

    /**
     * 查看用户是否充值过该等级
     *
     * @param int $user_id
     * @param int $level
     * @return bool
     */
    public function checkUserRechargeLevel(int $user_id, int $level)
    {
        return UserRecharge::query()
            ->where('user_id', $user_id)
            ->where('level', $level)
            ->where('status', 1)
            ->exists();
    }
}