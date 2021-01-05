<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Contract\TranslatorInterface;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;
use Hyperf\Di\Annotation\Inject;

/**
 * 会员等级模型
 *
 * @property int            $id
 * @property int            $level
 * @property string         $name
 * @property string         $icon
 * @property float          $price
 * @property int            $task_num
 * @property int            $duration
 * @property int            $day
 * @property int            $hour
 * @property int            $minute
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property int            $max_buy_num
 */
class UserLevel extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_level';
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
    protected $casts = [
        'id'          => 'integer',
        'level'       => 'integer',
        'price'       => 'float',
        'task_num'    => 'integer',
        'created_at'  => 'date:Y-m-d H:i:s',
        'updated_at'  => 'date:Y-m-d H:i:s',
        'max_buy_num' => 'integer'
    ];

    protected $appends = [
        'name_text',
        'day',
        'hour',
        'minute'
    ];

    /**
     * 关联等级奖励
     *
     * @return HasMany
     */
    public function levelRebate()
    {
        return $this->hasMany(UserLevelRebate::class, 'level_id', 'id');
    }

    /**
     * 关联充值奖励
     *
     * @return HasOne
     */
    public function rechargeLevelRebate()
    {
        return $this->hasOne(UserLevelRebate::class, 'level_id', 'id')->where('type', 1);
    }

    /**
     * 关联任务奖励
     *
     * @return HasOne
     */
    public function taskLevelRebate()
    {
        return $this->hasOne(UserLevelRebate::class, 'level_id', 'id')->where('type', 2);
    }

    /**
     * 名字获取器
     *
     * @param $value
     *
     * @return mixed
     */
    public function getNameTextAttribute()
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $name = Language::query()->where('local', $local)->where('key', $this->name)->value('value') ?? $this->name;

        return $name;
    }

    public function getDayAttribute()
    {
        $day = (int)($this->duration / 86400);
        return $day;
    }

    public function getHourAttribute()
    {
        $hour = (int)(($this->duration - $this->day * 86400) / 3600);
        return $hour;
    }

    public function getMinuteAttribute()
    {
        $minute = (int)(($this->duration - $this->day * 86400 - $this->hour * 3600) / 60);

        return $minute;
    }
}