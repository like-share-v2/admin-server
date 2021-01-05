<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 用户手动充值模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $level 
 * @property float $amount 
 * @property string $trade_no 
 * @property string $image 
 * @property int $status 
 * @property int $admin_id 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class UserManualRecharge extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_manual_recharge';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'level' => 'integer', 'amount' => 'float', 'status' => 'integer', 'admin_id' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

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
     * 关联用户等级
     *
     * @return BelongsTo
     */
    public function userLevel()
    {
        return $this->belongsTo(UserLevel::class, 'level', 'level');
    }
}