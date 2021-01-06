<?php
declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Common;

use App\Service\ConfigService;
use Psr\Container\ContainerInterface;

/**
 * Initialize
 *
 * @author
 * @package App\Common
 */
class Initialize
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Initialize constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 启动事件
     */
    public function onStart()
    {
        go(function () {
            $this->container->get(ConfigService::class)->initConfigs();
        });
    }
}