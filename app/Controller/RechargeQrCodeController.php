<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller;

use App\Request\RechargeQrCode\QrCodeRequest;
use App\Service\DAO\RechargeQrCodeDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 充值二维码控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class RechargeQrCodeController extends AbstractController
{
    /**
     * 获取二维码列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = map_filter([
            'status' => 'Integer',
            'perPage' => 'Integer'
        ], $this->request->all());

        $res = $this->container->get(RechargeQrCodeDAO::class)->get($params);

        $this->success($res);
    }

    /**
     * 添加二维码
     *
     * @PostMapping(path="")
     * @param QrCodeRequest $request
     */
    public function create(QrCodeRequest $request)
    {
        $params = $request->all();

        $this->container->get(RechargeQrCodeDAO::class)->create([
            'image'  => trim($params['image']),
            'status' => (int)$params['status']
        ]);

        $this->success();
    }

    /**
     * 编辑二维码
     *
     * @PutMapping(path="{id}")
     * @param int $id
     * @param QrCodeRequest $request
     */
    public function edit(int $id, QrCodeRequest $request)
    {
        $params = $request->all();

        $this->container->get(RechargeQrCodeDAO::class)->edit($id, [
            'image'  => trim($params['image']),
            'status' => (int)$params['status']
        ]);

        $this->success();
    }

    /**
     * 批量启用二维码
     *
     * @PutMapping(path="enable/{ids}")
     * @param string $ids
     */
    public function enable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(RechargeQrCodeDAO::class)->changeStatus($ids, 1);

        $this->success();
    }

    /**
     * 批量禁用二维码
     *
     * @PutMapping(path="disable/{ids}")
     * @param string $ids
     */
    public function disable(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id) ;
        });

        $this->container->get(RechargeQrCodeDAO::class)->changeStatus($ids, 0);

        $this->success();
    }

    /**
     * 删除二维码
     *
     * @DeleteMapping(path="{ids}")
     * @param string $ids
     */
    public function delete(string $ids)
    {
        $ids = array_filter(explode(',', $ids), function ($id) {
            return is_numeric($id);
        });

        $this->container->get(RechargeQrCodeDAO::class)->delete($ids);

        $this->success();
    }
}