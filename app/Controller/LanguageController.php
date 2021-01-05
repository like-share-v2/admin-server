<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Language\LanguageRequest;
use App\Service\DAO\LanguageDAO;
use App\Service\LanguageService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 语言控制器
 *
 * @Controller()
 * @package App\Controller
 */
class LanguageController extends AbstractController
{
    /**
     * 获取语言列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'key' => 'String',
            'local' => 'String',
            'value' => 'String',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(LanguageDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * 添加语言
     *
     * @PostMapping(path="")
     * @param LanguageRequest $request
     */
    public function create(LanguageRequest $request)
    {
        $params = $request->all();

        $this->container->get(LanguageService::class)->create($params);

        $this->success();
    }

    /**
     * 更新语言
     *
     * @PutMapping(path="{id}")
     * @param LanguageRequest $request
     * @param int $id
     */
    public function update(LanguageRequest $request, int $id)
    {
        $params = $request->all();

        $this->container->get(LanguageService::class)->update($id, $params);

        $this->success();
    }

    /**
     * 删除语言
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(LanguageDAO::class)->delete($ids);

        $this->success();
    }
}