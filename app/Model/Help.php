<?php

declare (strict_types=1);
namespace App\Model;

use App\Service\DAO\LanguageDAO;
use Hyperf\Contract\TranslatorInterface;

/**
 * 帮助手册模型
 *
 * @property int $id 
 * @property string $title 
 * @property string $content 
 * @property int $status 
 * @property int $sort 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class Help extends Model
{
    public $dateFormat = 'U';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'help';
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
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'sort' => 'integer', 'created_at' => 'date:Y-m-d H:i:s', 'updated_at' => 'date:Y-m-d H:i:s'];

    protected $appends = [
        'title_text'
    ];

    /**
     * 获取
     *
     * @return mixed
     */
    public function getTitleTextAttribute()
    {
        $local = $this->getContainer()->get(TranslatorInterface::class)->getLocale();

        $name = $this->getContainer()->get(LanguageDAO::class)->getValueByKeyLocal($this->title, $local);

        return $name;
    }
}