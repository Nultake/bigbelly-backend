<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostStep extends Model
{
    protected $guarded = [];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
