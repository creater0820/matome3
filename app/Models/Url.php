<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    //従テーブルsuredsと結びつける suredはモデル名
    public function sured(){
        return $this->hasMany('App\sured','url_id','id');
    }
}
