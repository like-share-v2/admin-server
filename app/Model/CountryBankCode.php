<?php

declare (strict_types=1);
namespace App\Model;

/**
 * 国家银行编码模型
 *
 * @property int $id 
 * @property int $country_id 
 * @property string $code 
 * @property string $name 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class CountryBankCode extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'country_bank_code';
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
    protected $casts = ['id' => 'integer', 'country_id' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}