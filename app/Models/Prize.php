<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Turntable;

class Prize extends Model
{
    protected $table='Prize';
    protected $primaryKey = 'Id';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'PrizeName','IsExChange','ExpiresDay','PrizeRate','PrizeUserNumber','ImageUrlPath',
        'PrizeUserNumberLimit','IsLimitPrizeUserNumber','ShowImageUrlPath',
    ];

    public function turntable()
    {
        return $this->belongsTo(Turntable::class,'Turntable_Id','Id');
    }

    public function prizeLogs()
    {
        return $this->hasMany(PrizeLog::class,'Prize_Id');
    }
}
