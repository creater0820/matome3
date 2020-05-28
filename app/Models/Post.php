<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'posts';
    public function post_anchors()
    {
        return $this->hasMany(
            'App\Models\PostAnchor',
            'post_id',
            'id',
        );
    }
    public function comment_anchors(){
        return $this->hasMany(
            'App\Models\CommentAnchor',
            'post_id',
            'id',
        );
    }
}
