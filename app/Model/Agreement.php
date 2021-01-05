<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 协议模型
 *
 * @property int $id 
 * @property int $type 
 * @property string $locale 
 * @property string $content 
 */
class Agreement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'agreement';
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
    protected $casts = ['id' => 'integer', 'type' => 'integer'];
}