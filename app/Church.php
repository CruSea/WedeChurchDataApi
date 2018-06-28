<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Church extends Model
{
    //
    public function denominations(){
        return $this->belongsTo('App\Denomination', 'denomination_id');
    }
    protected $fillable = [ 'church_name', 'description', 'location', 'latitude', 
    'longitude', 'phone_number', 'email', 'denomination_id', 'user_id'];
}