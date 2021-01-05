<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service;

use App\Common\Base;
use App\Exception\LogicException;
use App\Service\DAO\MemberDAO;
use Hyperf\DbConnection\Db;

/**
 * 用户关系服务
 *
 * @author 
 * @package App\Service
 */
class UserRelationService extends Base
{
    /**
     * 注册时添加用户关系
     *
     * @param int $user_id
     * @param int $parent_id
     * @param array $users
     */
    public function register(int $user_id, int $parent_id, array $users)
    {
        try {

            if (count($users) === 0) {
                return ;
            } else {
                $users = array_column($users, null, 'id');

                $insert_data = $this->getInsertRelationData($users, $user_id, $parent_id, [['user_id' => $user_id, 'parent_id' => $parent_id, 'level' => 1, 'created_at' => time()]], 2);

                Db::table('user_relation')->insert($insert_data);
            }
        } catch (\Exception $e) {
            throw new LogicException($e->getMessage());
        }
    }

    /**
     * 递增获取添加用户关系数组
     *
     * @param array $users
     * @param int $user_id
     * @param int $parent_id
     * @param array $return_data
     * @param int $level
     * @return array
     */
    public function getInsertRelationData(array $users, int $user_id, int $parent_id, array $return_data, int $level)
    {
        $time = time();

        foreach ($users as $key => $user) {
            if ($key === $users[$parent_id]['parent_id']) {
                $return_data[] = ['user_id' => $user_id, 'parent_id' => $key, 'level' => $level, 'created_at' => $time];
                return $this->getInsertRelationData($users, $user_id, $key, $return_data, $level + 1);
            }
        }

        return $return_data;
    }
}