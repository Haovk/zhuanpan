<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrizeLog extends Model
{
    protected $table='Prize_Log';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrizeName','CreateTime','PrizeCode','ExpiresTime','GiveTime','IsGive','IPAddress','IPAddressName',
        
    ];

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }

    public function turntableUser()
    {
        return $this->belongsTo(TurntableUser::class,'TurntableUserId','Id');
    }
}
