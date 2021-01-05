<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Video\VideoRequest;
use App\Service\DAO\VideoDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 视频控制器
 *
 * @Controller()
 * @package App\Controller
 */
class VideoController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(VideoDAO::class)->get($params);

        $this->success($result);
    }

    /**
     *
     * @PostMapping(path="")
     * @param VideoRequest $request
     */
    public function create(VideoRequest $request)
    {
        $params = $request->all();

        $this->container->get(VideoDAO::class)->create([
            'title' => $params['title'],
            'video' => $params['video'],
            'sort'  => (int)$params['sort']
        ]);

        $this->success();
    }

    /**
     *
     * @PutMapping(path="{id}")
     * @param int          $id
     * @param VideoRequest $request
     */
    public function update(int $id, VideoRequest $request)
    {
        $params = $request->all();

        $this->container->get(VideoDAO::class)->update($id, [
            'title' => $params['title'],
            'video' => $params['video'],
            'sort'  => (int)$params['sort']
        ]);

        $this->success();
    }

    /**
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(VideoDAO::class)->delete($ids);

        $this->success();
    }
}