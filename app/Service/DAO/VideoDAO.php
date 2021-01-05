<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Video;

/**
 * @package App\Service\DAO
 */
class VideoDAO extends Base
{
    public function get(array $params)
    {
        $model = Video::query();

        return isset($params['perPage']) ? $model->orderByDesc('sort')->paginate($params['perPage']) : $model->get();
    }

    public function create(array $data)
    {
        return Video::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        return Video::query()->where('id', $id)->update($data);
    }

    public function delete(array $ids)
    {
        return Video::destroy($ids);
    }
}