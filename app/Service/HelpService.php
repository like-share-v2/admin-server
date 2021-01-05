<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\HelpDAO;

/**
 * 帮助手册服务
 *
 * @author 
 * @package App\Service
 */
class HelpService extends Base
{
    /**
     * 添加帮助手册
     *
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        return $this->container->get(HelpDAO::class)->create([
            'title'   => trim($params['title']),
            'content' => trim($params['content']),
            'status'  => (int)$params['status'],
            'sort'    => (int)$params['sort']
        ]);
    }

    /**
     * 编辑帮助手册
     *
     * @param int $id
     * @param array $params
     */
    public function edit(int $id, array $params)
    {
        $help = $this->container->get(HelpDAO::class)->findById($id);

        if (!$help) {
            $this->error('logic.HELP_NOT_FOUND');
        }

        $this->container->get(HelpDAO::class)->edit($id, [
            'title'   => trim($params['title']),
            'content' => trim($params['content']),
            'status'  => (int)$params['status'],
            'sort'    => (int)$params['sort']
        ]);
    }
}