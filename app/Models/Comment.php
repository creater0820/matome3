<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    public function publisher()
    {
        return $this->belongsTo(
                'App\Models\Publisher',
                'id',
                'publisher_id'
            );
    }
}
