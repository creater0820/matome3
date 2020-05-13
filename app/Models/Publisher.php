<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class Publisher extends Model
{
    //
    public function comment()
    {
        return $this->hasMany(
            'App\Models\Comment',
            'publisher_id',
            'id'
        );
    }
}
