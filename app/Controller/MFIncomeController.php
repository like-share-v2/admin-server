<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link      https://dayiguo.com
 */

namespace App\Controller;

use App\Service\DAO\MFIncomeDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 理财收益
 *
 * @Controller(prefix="mf_income")
 * @author
 * @package App\Controller
 */
class MFIncomeController extends AbstractController
{
    /**
     * @GetMapping(path="")
     */
    public function list()
    {
        $params = map_filter([
            'order_no'    => 'String',
            'record_time' => 'TimestampBetween',
            'user_id'     => 'Integer',
            'account'     => 'String',
            'phone'       => 'String'
        ], $this->request->all());

        $result = $this->container->get(MFIncomeDAO::class)->get($params);

        $this->success($result);
    }
}