<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasOne;

/**
 * 充值表模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $level 
 * @property float $balance 
 * @property int $payment_id 
 * @property int $recharge_time 
 * @property int $channel 
 * @property int $admin_id 
 * @property string $remark 
 * @property \Carbon\Carbon $updated_at 
 */
class UserRecharge extends Model
{
    public $dateFormat = 'U';

    public const CREATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_recharge';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'level' => 'integer', 'balance' => 'float', 'payment_id' => 'integer', 'recharge_time' => 'date:Y-m-d H:i:s', 'channel' => 'integer', 'admin_id' => 'integer', 'updated_at' => 'date:Y-m-d H:i:s'];

    /**
     * 关联订单号
     *
     * @return HasOne
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'id', 'payment_id');
    }

    /**
     * 关联用户
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 关联等级
     *
     * @return BelongsTo
     */
    public function userLevel()
    {
        return $this->belongsTo(UserLevel::class, 'level', 'level');
    }
}