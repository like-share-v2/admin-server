<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 用户账单模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property int $type 
 * @property float $balance 
 * @property float $before_balance 
 * @property float $after_balance 
 * @property string $remark 
 * @property int $low_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class UserBill extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_bill';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'type' => 'integer', 'balance' => 'float', 'before_balance' => 'float', 'after_balance' => 'float', 'low_id' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    /**
     * 关联用户
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTypeAttribute($value)
    {
        return __('logic.USER_BILL_TYPE_'.$value);
    }
}