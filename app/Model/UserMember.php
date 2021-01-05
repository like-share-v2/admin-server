<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 用户会员模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $level 
 * @property \Carbon\Carbon $created_at 
 * @property int $effective_time 
 */
class UserMember extends Model
{
    public $dateFormat = 'U';

    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_member';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'level' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'effective_time' => 'integer'];

    /**
     * 到期时间获取器
     *
     * @param $value
     * @return false|string
     */
    public function getEffectiveTimeAttribute($value)
    {
        if ($value === -1) {
            return '-';
        }
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 关联用户等级
     *
     * @return BelongsTo
     */
    public function userLevel()
    {
        return $this->belongsTo(UserLevel::class, 'level', 'level');
    }
}