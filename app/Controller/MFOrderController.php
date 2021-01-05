<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Controller;

use App\Service\DAO\MFOrderDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 理财订单控制器
 *
 * @Controller(prefix="mf_order")
 * @author
 * @package App\Controller
 */
class MFOrderController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function list()
    {
        $params = map_filter([
            'title'         => 'String',
            'mode'          => 'Integer',
            'income_mode'   => 'Integer',
            'is_settle'     => 'Boolean',
            'order_no'      => 'String',
            'unfreeze_time' => 'TimestampBetween',
            'settle_time'   => 'TimestampBetween',
            'created_at'    => 'TimestampBetween',
            'amount'        => 'Between',
            'user_id'       => 'Integer',
            'account'       => 'String',
            'phone'         => 'String'
        ], $this->request->all());

        $result = $this->container->get(MFOrderDAO::class)->get($params);

        $this->success($result);
    }
}