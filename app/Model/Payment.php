<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 支付模型
 *
 * @property int $id 
 * @property int $user_id 
 * @property string $pay_no 
 * @property float $amount 
 * @property int $type 
 * @property string $channel 
 * @property string $trade_no 
 * @property int $status 
 * @property string $result_desc 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Payment extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment';
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
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'amount' => 'float', 'type' => 'integer', 'status' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];
}