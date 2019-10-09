<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    protected $guarded = [];

    public function fuel(){
        return $this->belongsTo(Fuel::class);
    }

    public function unloadings(){
        return $this->hasMany(Unloading::class);
    }
    
    public function fillings(){
        return $this->hasMany(Filling::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function user() {
        return $this->belongsTo('App\User');
    }
}
