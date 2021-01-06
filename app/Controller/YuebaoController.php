<?php
declare (strict_types=1);
/**
 * @copyright
 * @version   1.0.0
 * @link
 */

namespace App\Controller;

use App\Service\DAO\YuebaoAccountDAO;
use App\Service\DAO\YuebaoCoinLogDAO;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * YuebaoController
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class YuebaoController extends AbstractController
{
    /**
     * @GetMapping(path="account")
     */
    public function accountList()
    {
        $params = map_filter([
            'user_id' => 'Integer',
            'account' => 'String',
            'phone'   => 'String'
        ], $this->request->all());

        $result = $this->container->get(YuebaoAccountDAO::class)->getAccountList($params);

        $this->success($result);
    }

    /**
     * @GetMapping(path="coin_log")
     */
    public function coinLog()
    {
        $params = map_filter([
            'user_id'     => 'Integer',
            'type'        => 'Integer',
            'record_time' => 'TimestampBetween',
            'account'     => 'String',
            'phone'       => 'String'
        ], $this->request->all());

        $result = $this->container->get(YuebaoCoinLogDAO::class)->getCoinLog($params);

        $this->success($result);
    }
}