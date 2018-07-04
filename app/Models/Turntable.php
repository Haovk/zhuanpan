<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Turntable extends Model
{
    protected $table='Turntable';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'Name','CreateTime','StartTime','EndTime','FullInfo','RuleInfo','PrizeInfo','LotteryType','Number',
        'IsShowPrizeName','IsPlacePrizeNumber','IsPlaceUserNumber','UserNumber','PrizeNumber','BackMusicPath',
        'IsShare',
    ];

    public function turntableUsers()
    {
        return $this->hasMany(TurntableUser::class,'Turntable_Id');
    }
    public function prizes()
    {
        return $this->hasMany(Prize::class,'Turntable_Id','Id');
    }
}
