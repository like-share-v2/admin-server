<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Controller;

use App\Service\DAO\MFProductDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 理财产品控制器
 *
 * @Controller(prefix="mf_product")
 * @author
 * @package App\Controller
 */
class MFProductController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function list()
    {
        $params = map_filter([
            'title'       => 'String',
            'mode'        => 'Integer',
            'income_mode' => 'Integer',
            'is_enable'   => 'Boolean',
        ], $this->request->all());

        $result = $this->container->get(MFProductDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * @PostMapping(path="")
     */
    public function create()
    {
        $data = $this->request->post();

        if ((int)$data['mode'] === 2) {
            $data['period'] = 0;
        }
        $this->container->get(MFProductDAO::class)->create([
            'title'               => $data['title'],
            'daily_interest_rate' => (float)$data['daily_interest_rate'],
            'mode'                => (int)$data['mode'],
            'period'              => (int)$data['period'],
            'min_amount'          => (int)$data['min_amount'],
            'income_mode'         => (int)$data['income_mode'],
            'is_enable'           => (bool)$data['is_enable'],
        ]);

        $this->success();
    }

    /**
     * @PutMapping(path="{id}")
     * @param int $id
     */
    public function update(int $id)
    {
        $data = $this->request->post();

        if ((int)$data['mode'] === 2) {
            $data['period'] = 0;
        }
        $this->container->get(MFProductDAO::class)->update($id, [
            'title'               => $data['title'],
            'daily_interest_rate' => (float)$data['daily_interest_rate'],
            'mode'                => (int)$data['mode'],
            'period'              => (int)$data['period'],
            'min_amount'          => (int)$data['min_amount'],
            'income_mode'         => (int)$data['income_mode'],
            'is_enable'           => (bool)$data['is_enable'],
        ]);

        $this->success();
    }

    /**
     * @DeleteMapping(path="{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->container->get(MFProductDAO::class)->delete($id);

        $this->success();
    }
}