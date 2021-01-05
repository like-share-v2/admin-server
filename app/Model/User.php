<?php

declare (strict_types=1);

namespace App\Model;

use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasMany;
use Hyperf\Database\Model\Relations\HasOne;
use Hyperf\DbConnection\Db;

/**
 * 用户模型
 *
 * @property int            $id
 * @property int            $type
 * @property int            $parent_id
 * @property int            $level
 * @property string         $account
 * @property string         $password
 * @property string         $trade_pass
 * @property string         $phone
 * @property string         $email
 * @property string         $nickname
 * @property string         $avatar
 * @property int            $gender
 * @property float          $balance
 * @property int            $integral
 * @property int            $credit
 * @property int            $status
 * @property int            $last_login_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class User extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';
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
    protected $casts   = [
        'id'              => 'integer',
        'parent_id'       => 'integer',
        'level'           => 'integer',
        'gender'          => 'integer',
        'balance'         => 'float',
        'integral'        => 'integer',
        'credit'          => 'integer',
        'status'          => 'integer',
        'last_login_time' => 'date:Y-m-d H:i:s',
        'created_at'      => 'date:Y-m-d H:i:s',
        'updated_at'      => 'date:Y-m-d H:i:s'
    ];
    protected $appends = ['mf_amount', 'mf_profit'];

    /**
     * 关联用户等级
     *
     * @return mixed
     */
    public function userLevel()
    {
        return $this->hasOne(UserLevel::class, 'level', 'level');
    }

    /**
     * 关联用户信息
     *
     * @return HasOne
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
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

    /**
     * 关联用户等级
     *
     * @return HasMany
     */
    public function userMember()
    {
        return $this->hasMany(UserMember::class, 'user_id', 'id')
            ->with(['userLevel:level,name,task_num,duration']);
    }

    public function yuebao()
    {
        return $this->hasOne(YuebaoAccount::class, 'user_id');
    }

    public function getMfAmountAttribute()
    {
        return $this->mf()->where('is_settle', 0)->sum('amount');
    }

    public function getMfProfitAttribute()
    {
        return $this->mf()->where('is_settle', 0)->sum('profit');
    }

    public function mf()
    {
        return $this->hasMany(MFOrder::class, 'user_id');
    }
}