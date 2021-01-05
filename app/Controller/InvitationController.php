<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request\Invitation\InvitationRequest;
use App\Service\DAO\InvitationDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 邀请函模型
 *
 * @Controller()
 * @package App\Controller
 */
class InvitationController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'locale' => 'String',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(InvitationDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * @PostMapping(path="")
     * @param InvitationRequest $request
     */
    public function create(InvitationRequest $request)
    {
        $params = $request->all();

        $this->container->get(InvitationDAO::class)->create([
            'image' => $params['image'],
            'locale' => $params['locale']
        ]);

        $this->success();
    }

    /**
     * @PutMapping(path="{id}")
     * @param int $id
     * @param InvitationRequest $request
     */
    public function update(int $id, InvitationRequest $request)
    {
        $params = $request->all();

        $this->container->get(InvitationDAO::class)->update($id, [
            'image' => $params['image'],
            'locale' => $params['locale']
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

        $this->container->get(InvitationDAO::class)->delete($ids);

        $this->success();
    }
}