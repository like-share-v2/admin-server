<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Controller\AbstractController;
use App\Model\IpBlackList;

/**
 * IP黑名单DAO
 *
 * @package App\Service\DAO
 */
class IpBlackListDAO extends AbstractController
{
    public function get(array $params)
    {
        $model = IpBlackList::query();

        if (isset($params['ip'])) {
            $model->where('ip', $params['ip']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    public function checkIp(string $ip, $id = null)
    {
        return IpBlackList::query()->where('ip', $ip)->when($id !== null, function ($query) use ($id) {
            $query->where('id', '<>', $id);
        })->exists();
    }

    public function create(array $data)
    {
        return IpBlackList::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        return IpBlackList::query()->where('id', $id)->update($data);
    }

    public function delete(array $ids)
    {
        return IpBlackList::destroy($ids);
    }
}