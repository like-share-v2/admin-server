<?php

declare (strict_types=1);
namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 用户信用分记录模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property string $type 
 * @property float $credit 
 * @property string $remark 
 * @property \Carbon\Carbon $created_at 
 */
class UserCreditRecord extends Model
{
    public $dateFormat = 'U';

    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_credit_record';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'type' => 'integer', 'credit' => 'float', 'created_at' => 'date:Y-m-d H:i:s'];

    /**
     * 关联用户
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getTypeAttribute($value)
    {
        return __('logic.CREDIT_RECORD_TYPE_'.$value);
    }
}