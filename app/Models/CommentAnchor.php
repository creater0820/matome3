<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentAnchor extends Model
{
    protected $table = 'comment_anchors';
    public function post()
    {
        return $this->hasOne(
            'App\Models\Post',
            'id',
            'post_comment_id',
        );
    }
}
