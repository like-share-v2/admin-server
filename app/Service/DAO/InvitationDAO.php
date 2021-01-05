<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Invitation;

/**
 * 邀请函DAO
 *
 * @package App\Service\DAO
 */
class InvitationDAO extends Base
{
    public function get(array $params)
    {
        $model = Invitation::query()->with(['country']);

        if (isset($params['locale'])) {
            $model->where('locale', $params['locale']);
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    public function create(array $data)
    {
        return Invitation::query()->create($data);
    }

    public function update(int $id, array $data)
    {
        return Invitation::query()->where('id', $id)->update($data);
    }

    public function delete(array $ids)
    {
        return Invitation::destroy($ids);
    }
}