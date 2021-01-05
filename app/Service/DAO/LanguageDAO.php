<?php

declare(strict_types=1);

namespace App\Service\DAO;

use App\Common\Base;
use App\Model\Language;

/**
 * 语言DAO
 *
 * @package App\Service\DAO
 */
class LanguageDAO extends Base
{
    /**
     * 获取语言列表
     *
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $model = Language::query()->with(['country:code,name,lang']);

        if (isset($params['key'])) {
            $model->where('key', $params['key']);
        }

        if (isset($params['local'])) {
            $model->where('local', $params['local']);
        }

        if (isset($params['value'])) {
            $model->where('value', 'like', '%' . $params['value'] . '%');
        }

        return isset($params['perPage']) ? $model->orderByDesc('id')->paginate($params['perPage']) : $model->get();
    }

    /**
     * 添加翻译语言
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return Language::query()->create($data);
    }

    /**
     * 更新语言
     *
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(int $id, array $data)
    {
        return Language::query()->where('id', $id)->update($data);
    }

    public function delete(array $ids)
    {
        return Language::destroy($ids);
    }

    /**
     * 获取翻译文本
     *
     * @param string $key
     * @param string $local
     * @return mixed
     */
    public function getValueByKeyLocal(string $key, string $local)
    {
        return Language::query()->where('local', $local)->where('key', $key)->value('value') ?? $key;
    }

    /**
     * 通过键值获取列表
     *
     * @param string $key
     * @return array
     */
    public function getKeyList(string $key)
    {
        return array_column(Language::query()->where('key', $key)->get()->toArray(), 'value', 'local');
    }
}