<?php

declare (strict_types=1);

namespace App\Model;

/**
 * @property int $user_id 
 * @property string $secret
 * @property boolean $is_enable
 */
class AdminGoogleAuth extends Model
{
    /**
     * @var null
     */
    protected $primaryKey = null;
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_google_auth';
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
    protected $casts = ['user_id' => 'integer', 'is_enable' => 'boolean'];
}