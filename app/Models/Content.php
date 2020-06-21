<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Content extends Model
{
    protected $table = 'contents';
    public function favorites()
    {
        return $this->hasMany(
            'App\Models\Favorite',
            'content_id',
            'id'
        );
    }
  

    public function getFavoriteCountAttribute()
    {
        return $this->favorites->count();
    }
    public function getUserFavoriteAttribute()
    {
        $userId = Auth::id();
        $ignoreFavorites = $this->favorites->where('user_id',$userId)->pluck('content_id')->toArray();
        if(in_array($this->id,$ignoreFavorites)){
            return true;
        }
        return false;
    }

  


   
}
