<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class url extends Model
{
    //従テーブルsuredsと結びつける suredはモデル名
    public function sured(){
        return $this->hasMany('App\sured','url_id','id');
    }
}
