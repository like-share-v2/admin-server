<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\HelpContent;

/**
 * 帮助手册内容DAO
 *
 * @package App\Service\DAO
 */
class HelpContentDAO extends Base
{
    public function updateOrCreate(array $data)
    {
        return HelpContent::query()->updateOrInsert([
            'help_id' => $data['help_id'],
            'locale' => $data['locale']
        ], ['content' => $data['content']]);
    }

    public function get(array $params)
    {
        $model = HelpContent::query();

        if (isset($params['help_id'])) {
            $model->where('help_id', $params['help_id']);
        }

        if (isset($params['locale'])) {
            $model->where('locale', $params['locale']);
        }

        return $model->select(['content'])->first();
    }
}