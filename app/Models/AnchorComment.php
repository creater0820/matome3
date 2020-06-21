<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnchorComment extends Model
{
    protected $table ='anchor_comments';
    public function anchors(){
        return $this->hasMany(
            'App\Models\Anchor',
            'id',
            'anchor_id',
        );
    }
}
