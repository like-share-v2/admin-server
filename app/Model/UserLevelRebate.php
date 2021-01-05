<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 用户等级奖励模型
 *
 * @property int $id 
 * @property int $level_id 
 * @property int $type 
 * @property float $p_one_rebate 
 * @property float $p_two_rebate 
 * @property float $p_three_rebate 
 */
class UserLevelRebate extends Model
{
    public const CREATED_AT = null;

    public const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_level_rebate';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'level_id' => 'integer', 'type' => 'integer', 'p_one_rebate' => 'float', 'p_two_rebate' => 'float', 'p_three_rebate' => 'float'];
}