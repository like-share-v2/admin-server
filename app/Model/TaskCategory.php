<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Contract\TranslatorInterface;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * 任务分类模型
 *
 * @property int $id
 * @property string $name 
 * @property string $icon 
 * @property string $banner
 * @property float $lowest_price
 * @property int $sort 
 * @property int $status 
 * @property string $job_step 
 * @property string $audit_sample 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class TaskCategory extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'task_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'lowest_price' => 'float', 'sort' => 'integer', 'status' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    protected $appends = [
        'name_text'
    ];

    /**
     * 关联任务
     *
     * @return HasMany
     */
    public function task()
    {
        return $this->hasMany(Task::class, 'category_id', 'id')->withCount('userTask');
    }

    /**
     * 名字获取器
     *
     * @param $value
     * @return mixed
     */
    public function getNameTextAttribute()
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $name = Language::query()->where('local', $local)->where('key', $this->name)->value('value') ?? $this->name;

        return $name;
    }
}