<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserLevelRebate;

/**
 * 会员等级奖励DAO
 *
 * @author 
 * @package App\Service\DAO
 */
class UserLevelRebateDAO extends Base
{
    /**
     * 添加会员等级奖励
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return UserLevelRebate::query()->create($data);
    }

    /**
     * 修改会员等级奖励
     *
     * @param int $level_id
     * @param int $type
     * @param array $data
     * @return int
     */
    public function edit(int $level_id, int $type, array $data)
    {
        return UserLevelRebate::query()->where('level_id', $level_id)->where('type', $type)->update($data);
    }

    /**
     * 删除会员等级
     *
     * @param array $level_ids
     * @return int
     */
    public function delete(array $level_ids)
    {
        return UserLevelrebate::query()->whereIn('level_id', $level_ids)->delete();
    }
}