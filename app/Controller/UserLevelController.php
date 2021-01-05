<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;

use App\Request\UserLevel\UserLevelRequest;
use App\Service\DAO\UserLevelDAO;
use App\Service\UserLevelService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 会员等级控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class UserLevelController extends AbstractController
{
    /**
     * 获取等级列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        $result = $this->container->get(UserLevelDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加会员等级
     *
     * @PostMapping(path="")
     * @param UserLevelRequest $request
     */
    public function create(UserLevelRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserLevelService::class)->create($params);

        $this->success();
    }

    /**
     * 修改会员等级
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param UserLevelRequest $request
     */
    public function edit(int $id, UserLevelRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserLevelService::class)->edit($id, $params);

        $this->success();
    }

    /**
     * 删除会员等级
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($item) {
            return is_numeric($item);
        });

        $this->container->get(UserLevelService::class)->delete(array_values($ids));

        $this->success();
    }
}