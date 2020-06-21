<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anchor extends Model
{
    protected $tabel = 'anchors';
    public function anchor_comment(){
        return $this->hasMany(
            'App\Models\AnchorComment',
            'anchor_id',
            'id',
        );
    }
}
