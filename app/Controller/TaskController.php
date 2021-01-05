<?php

declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Controller;

use App\Request\Task\CategoryContentRequest;
use App\Request\Task\CategoryRequest;
use App\Request\Task\TaskRequest;
use App\Service\DAO\TaskCategoryDAO;
use App\Service\DAO\TaskDAO;
use App\Service\DAO\UserLevelDAO;
use App\Service\TaskCategoryService;
use App\Service\TaskService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Vtiful\Kernel\Excel;

/**
 * 任务控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class TaskController extends AbstractController
{
    /**
     * 获取任务分类列表
     *
     * @GetMapping(path="category")
     */
    public function getCategories()
    {
        $params = $this->request->all();

        $result = $this->container->get(TaskCategoryDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加任务分类
     *
     * @PostMapping(path="category")
     * @param CategoryRequest $request
     */
    public function createCategory(CategoryRequest $request)
    {
        $params = $request->all();

        $this->container->get(TaskCategoryService::class)->create($params);

        $this->success();
    }

    /**
     * 修改任务分类
     *
     * @PutMapping(path="category/{id}")
     * @param int             $id
     * @param CategoryRequest $request
     */
    public function editCategory(int $id, CategoryRequest $request)
    {
        $params = $request->all();

        $this->container->get(TaskCategoryService::class)->edit($id, $params);

        $this->success();
    }

    /**
     * 修改分类内容
     *
     * @PutMapping(path="category_content")
     * @param CategoryContentRequest $request
     */
    public function editCategoryContent(CategoryContentRequest $request)
    {
        $params = $request->all();

        $this->container->get(TaskCategoryService::class)->editContent($params);

        $this->success();
    }

    /**
     * 删除分类
     *
     * @DeleteMapping(path="category/{id}")
     * @param int $id
     */
    public function deleteCategory(int $id)
    {
        $this->container->get(TaskCategoryService::class)->delete($id);

        $this->success();
    }

    /**
     * 批量启用分类
     *
     * @PutMapping(path="category/enable/{ids}")
     * @param string $ids
     */
    public function enableCategories(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(TaskCategoryDAO::class)->setStatusByIds($ids, 1);

        $this->success();
    }

    /**
     * 批量禁用分类
     *
     * @PutMapping(path="category/disable/{ids}")
     * @param string $ids
     */
    public function disableCategories(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(TaskCategoryDAO::class)->setStatusByIds($ids, 0);

        $this->success();
    }

    /**
     * 获取任务列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        $result = $this->container->get(TaskDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加任务
     *
     * @PostMapping(path="")
     */
    public function create()
    {
        $params = $this->request->all();

        if (isset($params['add_method']) && $params['add_method'] == 2) {
            if (!$file = $this->request->file('file')) {
                $this->error('logic.PLEASE_UPLOAD_FILE');
            }
            try {
                $excel = new Excel(['path' => '']);
                $excel->openFile($file->getRealPath());
                $sheetData = $excel->openSheet('task')->getSheetData();
                unset($sheetData[0]);

                $this->container->get(TaskService::class)->importTask($sheetData);
            }
            catch (\Throwable $e) {
                $this->error('logic.SERVER_ERROR');
            }

            $this->success();
        }

        $this->container->get(TaskService::class)->create($params);

        $this->success();
    }

    /**
     * 修改任务
     *
     * @PutMapping(path="{id}")
     * @param int         $id
     * @param TaskRequest $request
     */
    public function edit(int $id, TaskRequest $request)
    {
        $params = $request->all();

        $this->container->get(TaskService::class)->edit($id, $params);

        $this->success();
    }

    /**
     * 批量启用任务
     *
     * @PutMapping(path="enable/{ids}")
     * @param string $ids
     */
    public function enable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(TaskDAO::class)->setTaskStatusByIds($ids, 1);

        $this->success();
    }

    /**
     * 批量禁用任务
     *
     * @PutMapping(path="disable/{ids}")
     * @param string $ids
     */
    public function disable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(TaskDAO::class)->setTaskStatusByIds($ids, 0);

        $this->success();
    }

    /**
     * 删除任务
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(TaskDAO::class)->delete($ids);

        $this->success();
    }

    /**
     * 获取任务需求
     *
     * @GetMapping(path="demands")
     */
    public function getTaskDemands()
    {
        $config = getConfig('task_demands');
        $this->success($config);
    }
}