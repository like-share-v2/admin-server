<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int   $id
 * @property int   $user_id
 * @property int   $mf_order_id
 * @property float $amount
 * @property int   $record_time
 */
class MFIncome extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mf_income';
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
        'user_id'     => 'integer',
        'mf_order_id' => 'integer',
        'amount'      => 'float',
        'record_time' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(MFOrder::class, 'mf_order_id');
    }

    /**
     * 购买时间
     *
     * @param $value
     *
     * @return mixed
     */
    public function getRecordTimeAttribute($value)
    {
        return (int)$value === 0 ? null : date('Y-m-d H:i', (int)$value);
    }
}