<?php

declare(strict_types=1);

namespace App\Service;

use App\Common\Base;
use Hyperf\Utils\Codec\Json;

/**
 * 短信服务
 *
 * @package App\Service
 */
class SMSService extends Base
{
    public function sendSMS(string $add_num, string $phone)
    {
        $guzzle = $this->guzzle();

        $sms_url = env('SMS_URL', '');

        try {
            $response = $guzzle->get($sms_url . $phone, [
                'query' => [
                    'type' => 5,
                    'add_num' => $add_num
                ]
            ]);
            $responseContents = $response->getBody()->getContents();
            $result           = Json::decode($responseContents, true);
            if (!isset($result['code']) || (int)$result['code'] !== 200) {
                throw new \Exception($result['message']);
            }
            return $result;
        } catch (\Exception $e) {
             $this->error($e->getMessage());
             return '';
        }
    }
}