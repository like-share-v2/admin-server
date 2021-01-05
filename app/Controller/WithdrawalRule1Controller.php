<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Controller;

use App\Service\DAO\WithdrawalRule1DAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * WithdrawalRule1Controller
 *
 * @Controller(prefix="withdrawal_rule1")
 * @author
 * @package App\Controller
 */
class WithdrawalRule1Controller extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $result = $this->container->get(WithdrawalRule1DAO::class)->get();
        $this->success($result);
    }

    /**
     * @PostMapping(path="")
     */
    public function create()
    {
        $data   = $this->request->post();
        $result = $this->container->get(WithdrawalRule1DAO::class)->create([
            'name'             => $data['name'],
            'active_sub'       => (int)$data['active_sub'],
            'withdrawal_count' => (int)$data['withdrawal_count'],
            'is_enable'        => (bool)$data['is_enable']
        ]);
        $this->success($result);
    }

    /**
     * @PutMapping(path="{id}")
     * @param int $id
     */
    public function update(int $id)
    {
        $data = $this->request->post();
        $this->container->get(WithdrawalRule1DAO::class)->update($id, [
            'name'             => $data['name'],
            'active_sub'       => (int)$data['active_sub'],
            'withdrawal_count' => (int)$data['withdrawal_count'],
            'is_enable'        => (bool)$data['is_enable']
        ]);

        $this->success();
    }

    /**
     * @DeleteMapping(path="{id}")
     * @param int $id
     */
    public function delete(int $id)
    {
        $this->container->get(WithdrawalRule1DAO::class)->delete($id);

        $this->success();
    }
}