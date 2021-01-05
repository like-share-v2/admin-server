<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Agreement;

/**
 * 使用协议DAO
 *
 * @package App\Service\DAO
 */
class AgreementDAO extends Base
{
    public function updateOrCreate(array $data)
    {
        return Agreement::query()->updateOrInsert([
            'type' => $data['type'],
            'locale' => $data['locale']
        ], ['content' => $data['content']]);
    }

    public function get(array $params)
    {
        $model = Agreement::query();

        if (isset($params['type'])) {
            $model->where('type', $params['type']);
        }

        if (isset($params['locale'])) {
            $model->where('locale', $params['locale']);
        }

        return $model->select(['content'])->first();
    }
}