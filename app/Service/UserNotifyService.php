<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\UserNotifyDAO;

/**
 * 新闻服务
 *
 * @author 
 * @package App\Service
 */
class UserNotifyService extends Base
{
    /**
     * 添加新闻
     *
     * @param array $params
     */
    public function create(array $params)
    {
        $this->container->get(UserNotifyDAO::class)->create([
            'type'    => 2,
            'user_id' => 0,
            'title'   => trim($params['title']),
            'content' => $params['content'],
            'sort'    => (int)$params['sort']
        ]);
    }

    /**
     * 编辑新闻
     *
     * @param int $id
     * @param array $params
     */
    public function edit(int $id, array $params)
    {
        $user_notify = $this->container->get(UserNotifyDAO::class)->findById($id);

        if (!$user_notify || $user_notify->type !== 2) {
            $this->error('logic.NEWS_NOT_FOUND');
        }

        $this->container->get(UserNotifyDAO::class)->edit($id, [
            'title'   => trim($params['title']),
            'content' => $params['content'],
            'sort'    => (int)$params['sort']
        ]);
    }
}