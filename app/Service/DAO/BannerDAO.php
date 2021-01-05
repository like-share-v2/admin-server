<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Banner;

/**
 * 轮播DAO
 *
 * @package App\Service\DAO
 */
class BannerDAO extends Base
{
    public function get(array $params)
    {
        $model = Banner::query();

        if (isset($params['url'])) {
            $model->where('url', 'like', $params['url']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('sort')->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    public function create(array $data)
    {
        return Banner::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        return Banner::query()->where('id', $id)->update($data);
    }

    public function delete(array $ids)
    {
        return Banner::destroy($ids);
    }
}