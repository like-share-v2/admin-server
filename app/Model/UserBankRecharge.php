<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * 用户银行卡充值模型
 *
 * @property int            $id
 * @property int            $user_id
 * @property string         $name
 * @property string         $bank
 * @property string         $bank_name
 * @property float          $amount
 * @property float          $remittance
 * @property string         $receive_bank_name
 * @property string         $receive_bank_account
 * @property string         $receive_bank_address
 * @property int            $status
 * @property int            $admin_id
 * @property string         $remark
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class UserBankRecharge extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_bank_recharge';
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
        'id'         => 'integer',
        'user_id'    => 'integer',
        'amount'     => 'float',
        'remittance' => 'float',
        'status'     => 'integer',
        'admin_id'   => 'integer',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s'
    ];

    /**
     * 关联用户
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 关联国家
     *
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
}