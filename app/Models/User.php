<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    public function favorites(){
     return $this->hasMany(
         'App\Models\Favorite',
         'user_id',
         'id',
     );
    }

    public function getFavoriteIpAttribute(){
        return $this->favorites->ip();
    }

}
