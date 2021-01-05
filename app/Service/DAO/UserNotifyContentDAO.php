<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\UserNotifyContent;

/**
 * 用户通知DAO
 *
 * @package App\Service\DAO
 */
class UserNotifyContentDAO extends Base
{
    public function updateOrCreate(array $data)
    {
        return UserNotifyContent::query()->updateOrInsert([
            'notify_id' => $data['notify_id'],
            'locale' => $data['locale']
        ], ['content' => $data['content']]);
    }

    public function get(array $params)
    {
        $model = UserNotifyContent::query();

        if (isset($params['notify_id'])) {
            $model->where('notify_id', $params['notify_id']);
        }

        if (isset($params['locale'])) {
            $model->where('locale', $params['locale']);
        }

        return $model->select(['content'])->first();
    }

    public function saveLanguage(int $notify_id, string $content)
    {
        $save_data = [];
        $countries = $this->container->get(CountryDAO::class)->get([]);
        foreach ($countries as $country) {
            $save_data[] = ['notify_id' => $notify_id, 'locale' => $country->code, 'content' => __($content, [], $country->code)];
        }

        return UserNotifyContent::query()->insert($save_data);
    }

    public function saveAll(array $data)
    {
        return UserNotifyContent::query()->insert($data);
    }
}