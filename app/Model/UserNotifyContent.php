<?php

declare (strict_types=1);
namespace App\Model;

/**
 *
 *
 * @property int $id 
 * @property int $notify_id 
 * @property string $locale 
 * @property string $content 
 */
class UserNotifyContent extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_notify_content';
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
    protected $casts = ['id' => 'integer', 'notify_id' => 'integer'];
}