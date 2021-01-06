<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Service;

use App\Common\Base;
use App\Service\DAO\TaskCategoryDAO;
use App\Service\DAO\TaskDAO;
use Hyperf\DbConnection\Db;

/**
 * 任务分类服务
 *
 * @author 
 * @package App\Service
 */
class TaskCategoryService extends Base
{
    /**
     * 添加任务分类
     *
     * @param array $params
     * @return mixed
     */
    public function create(array $params)
    {
        return $this->container->get(TaskCategoryDAO::class)->create([
            'name'         => trim($params['name']),
            'icon'         => $params['icon'],
            'banner'       => $params['banner'],
            'lowest_price' => (float)$params['lowest_price'],
            'sort'         => (int)$params['sort'],
            'status'       => (int)$params['status'],
            'job_step'     => '',
            'audit_sample' => ''
        ]);
    }

    /**
     * 修改分类内容
     *
     * @param array $params
     * @return bool
     */
    public function editContent(array $params)
    {
        // 判断内容
        if (empty($params['content'])) {
            $this->error('logic.TASK_CONTENT_REQUIRED');
        }

        // 查找分类
        $category = $this->container->get(TaskCategoryDAO::class)->findById((int)$params['id']);
        if (!$category) {
            $this->error('logic.TASK_CATEGORY_NOT_FOUND');
        }

        switch ((int)$params['type']) {
            case 1:
                $category->job_step = $params['content'];
                break;
            case 2:
                $category->audit_sample = $params['content'];
                break;
            default :
                $this->error('logic.SERVER_ERROR');
                break;
        }

        return $category->save();
    }

    /**
     * 编辑分类
     *
     * @param int $id
     * @param array $params
     */
    public function edit(int $id, array $params)
    {
        // 查找分类
        $category = $this->container->get(TaskCategoryDAO::class)->findById((int)$params['id']);
        if (!$category) {
            $this->error('logic.TASK_CATEGORY_NOT_FOUND');
        }

        $this->container->get(TaskCategoryDAO::class)->edit($id, [
            'name'   => trim($params['name']),
            'icon'   => $params['icon'],
            'banner' => $params['banner'],
            'lowest_price' => (float)$params['lowest_price'],
            'sort'   => (int)$params['sort'],
            'status' => (int)$params['status']
        ]);
    }

    /**
     * 删除任务分类
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        Db::beginTransaction();
        try {
            // 删除分类
            $this->container->get(TaskCategoryDAO::class)->delete($id);

            // 删除分类下任务
            $this->container->get(TaskDAO::class)->deleteByCategoryId($id);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->logger('taskCategory')->error($e->getMessage());
            $this->error('logic.SERVER_ERROR');
        }
    }
}