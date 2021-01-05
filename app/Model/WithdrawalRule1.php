<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int    $id
 * @property string $name
 * @property int    $active_sub
 * @property int    $withdrawal_count
 * @property int    $is_enable
 */
class WithdrawalRule1 extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'withdrawal_rule_1';
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
        'id'               => 'integer',
        'active_sub'       => 'integer',
        'withdrawal_count' => 'integer',
        'is_enable'        => 'boolean'
    ];
}