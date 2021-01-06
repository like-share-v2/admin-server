<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller;

use App\Model\HelpContent;
use App\Request\Help\HelpContentRequest;
use App\Request\Help\HelpRequest;
use App\Service\ConfigService;
use App\Service\DAO\AdminConfigDAO;
use App\Service\DAO\HelpContentDAO;
use App\Service\DAO\HelpDAO;
use App\Service\HelpService;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 帮助手册控制器
 *
 * @Controller()
 * @author
 * @package App\Controller\Admin
 */
class HelpController extends AbstractController
{
    /**
     * 获取帮助手册列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'perPage' => 'Integer',
            'title'   => 'String',
            'status'  => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(HelpDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加帮助手册
     *
     * @PostMapping(path="")
     * @param HelpRequest $request
     * @throws InvalidArgumentException
     */
    public function create(HelpRequest $request)
    {
        $params = $request->all();

        Db::beginTransaction();
        try {
            $help = $this->container->get(HelpService::class)->create($params);
            $this->container->get(HelpContentDAO::class)->updateOrCreate(['help_id' => $help->id, 'locale' => 'zh-CN', 'content' => $params['content']]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $this->error('logic.SERVER_ERROR');
        }

        $this->cache->delete('help');

        $this->success();
    }

    /**
     * 编辑帮助手册
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param HelpRequest $request
     * @throws InvalidArgumentException
     */
    public function edit(int $id, HelpRequest $request)
    {
        $params = $request->all();

        $this->container->get(HelpService::class)->edit($id, $params);

        $this->cache->delete('help');

        $this->success();
    }

    /**
     * 批量启用
     *
     * @PutMapping(path="enable/{ids}")
     * @param string $ids
     * @throws InvalidArgumentException
     */
    public function enable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(HelpDAO::class)->changeStatusByIds($ids, 1);

        $this->cache->delete('help');

        $this->success();
    }

    /**
     * 批量禁用
     *
     * @PutMapping(path="disable/{ids}")
     * @param string $ids
     * @throws InvalidArgumentException
     */
    public function disable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(HelpDAO::class)->changeStatusByIds($ids, 0);

        $this->cache->delete('help');

        $this->success();
    }

    /**
     * 批量删除
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     * @throws InvalidArgumentException
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(HelpDAO::class)->delete($ids);

        $this->cache->delete('help');

        $this->success();
    }

    /**
     * 修改帮助手册内容
     *
     * @PostMapping(path="content")
     * @param HelpContentRequest $request
     */
    public function editContent(HelpContentRequest $request)
    {
        $params = $request->all();

        $this->container->get(HelpContentDAO::class)->updateOrCreate([
            'help_id' => (int)$params['help_id'],
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
            'help_id' => 'Integer',
            'locale' => 'String'
        ], $this->request->all());

        $result = $this->container->get(HelpContentDAO::class)->get($params);

        $this->success($result);
    }
}