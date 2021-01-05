<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 用户信息模型
 *
 * @property int $user_id 
 * @property string $id_card 
 * @property string $bank_name 
 * @property string $name 
 * @property string $account 
 */
class UserInfo extends Model
{
    const CREATED_AT = null;

    const UPDATED_AT = null;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_info';
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
    protected $casts = ['user_id' => 'integer'];
}