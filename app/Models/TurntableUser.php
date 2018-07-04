<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TurntableUser extends Model
{
    protected $table='Turntable_User';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'OpenId','NickName','UId','CreateTime','PrizeNumber','PrizeNumberSum','ShareNumber',
    ];

    public function turntable()
    {
        return $this->belongsTo(Turntable::class,'Turntable_Id','Id');
    }
    
    public function prizeLogs()
    {
        return $this->hasMany(PrizeLog::class,'TurntableUserId','Id');
    }
}
