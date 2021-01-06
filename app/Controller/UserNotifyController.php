<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller;

use App\Request\Help\HelpContentRequest;
use App\Request\UserNotifyContent\UserNotifyContentRequest;
use App\Request\UserNotify\UserNotifyRequest;
use App\Service\DAO\HelpContentDAO;
use App\Service\DAO\UserNotifyContentDAO;
use App\Service\DAO\UserNotifyDAO;
use App\Service\UserNotifyService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 新闻控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class UserNotifyController extends AbstractController
{
    /**
     * 获取新闻列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'title' => 'String',
            'create_time' => 'TimestampBetween',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(UserNotifyDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加新闻
     *
     * @PostMapping(path="")
     * @param UserNotifyRequest $request
     */
    public function create(UserNotifyRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserNotifyService::class)->create($params);

        $this->success();
    }

    /**
     * 编辑新闻
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param UserNotifyRequest $request
     */
    public function edit(int $id, UserNotifyRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserNotifyService::class)->edit($id, $params);

        $this->success();
    }

    /**
     * 删除新闻
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(UserNotifyDAO::class)->delete($ids);

        $this->success();
    }

    /**
     * 修改帮助手册内容
     *
     * @PostMapping(path="content")
     * @param UserNotifyContentRequest $request
     */
    public function editContent(UserNotifyContentRequest $request)
    {
        $params = $request->all();

        $this->container->get(UserNotifyContentDAO::class)->updateOrCreate([
            'notify_id' => (int)$params['notify_id'],
            'locale' => trim($params['locale']),
            'content' => $params['content']
        ]);

        $this->success();
    }

    /**
     * @GetMapping(path="content")
     */
    public function getContent()
    {
        $params = map_filter([
            'notify_id' => 'Integer',
            'locale' => 'String'
        ], $this->request->all());

        $result = $this->container->get(UserNotifyContentDAO::class)->get($params);

        $this->success($result);
    }
}