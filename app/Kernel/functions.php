<?php
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */

if (!function_exists('di')) {
    /**
     * di
     *
     * @param string $id
     * @return mixed
     */
    function di(string $id)
    {
        return \Hyperf\Utils\ApplicationContext::getContainer()->get($id);
    }
}

if (!function_exists('getConfig')) {
    /**
     * 获取配置
     *
     * @param string $name
     * @param null $default
     * @return mixed
     */
    function getConfig(string $name, $default = null)
    {
        return \Hyperf\Utils\ApplicationContext::getContainer()->get(\App\Service\ConfigService::class)->get($name, $default);
    }
}

if (!function_exists('map_filter')) {
    /**
     * 过滤查询条件
     *
     * @param array $maps
     * @param array $params
     *
     * @return array
     */
    function map_filter(array $maps, array $params): array
    {
        $data = [];
        foreach ($maps as $key => $type) {
            switch ($type) {
                case 'Integer':
                    if (isset($params[$key]) && $params[$key] !== '' && $params[$key] !== null) {
                        $data[$key] = (int)$params[$key];
                    }
                    break;

                case 'String': // 字符串
                    if (isset($params[$key]) && !empty($params[$key])) {
                        $data[$key] = trim($params[$key]);
                    }
                    break;

                case 'Between': // 范围
                    $filter = array_filter($params[$key] ?? [], function ($value) {
                        return $value !== 'null' ? true : false;
                    });
                    if (count($filter) === 2) {
                        $data[$key] = [(int)$params[$key][0], (int)$params[$key][1]];
                    }
                    break;

                case 'TimestampBetween': // 时间戳范围
                    $temp = array_filter(array_map(function ($map) {
                        return strtotime($map);
                    }, $params[$key] ?? []), function ($value) {
                        return !empty($value) && $value !== 'null' ? true : false;
                    });
                    if (count($temp) === 2) {
                        $temp[1]    += 86400;
                        $data[$key] = $temp;
                    }
                    break;

                case 'Boolean':
                    if (isset($params[$key]) && $params[$key] !== null && $params[$key] !== '') {
                        $data[$key] = (bool)$params[$key];
                    }
                    break;

                default:
            }
        }
        return $data;
    }
}

if (!function_exists('array_keys_exists')) {
    /**
     * 判断数组中是否包含其中一个key
     *
     * @param array $keys
     * @param array $array
     *
     * @return bool
     */
    function array_keys_exists(array $keys, array $array): bool
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                return true;
            }
        }

        return false;
    }
}

/**
 * 获取16位随机字符串
 * @param int $length
 * @return string
 */
 function getRandStr($length = 16)
{
    mt_srand();
    // 密码字符集，可任意添加你需要的字符
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[mt_rand(0, strlen($chars) - 1)];
    }
    return $password;
}