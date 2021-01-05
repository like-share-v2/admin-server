<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int   $user_id
 * @property float $balance
 * @property int   $withdraw_time
 */
class YuebaoAccount extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string
     */
    protected $primaryKey = 'user_id';
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'yuebao_account';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id'       => 'integer',
        'balance'       => 'float',
        'withdraw_time' => 'date:Y-m-d H:i:s'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}