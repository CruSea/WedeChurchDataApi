<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    //
    public function churches(){
        return $this->hasMany('App\Church');
    }

    protected $fillable = [ 'name', 'description'];
}
