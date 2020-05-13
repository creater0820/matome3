<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sured extends Model
{
    //
    public function url()
    {
        return $this->belongsTo('App\url','id');
    }
}
