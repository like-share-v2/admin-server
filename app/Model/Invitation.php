<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 邀请函模型
 *
 * @property int $id 
 * @property string $image 
 * @property string $locale 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Invitation extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invitation';
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
    protected $casts = ['id' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'locale', 'code');
    }
}