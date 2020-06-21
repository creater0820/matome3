<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostAnchor extends Model
{
    protected $table = 'post_anchors';
    public function content(){
        return $this->hasOne(
            'App\Models\Content',
            'id',
            'content_id',
        );
    }

}
