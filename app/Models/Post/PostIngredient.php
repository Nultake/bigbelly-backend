<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostIngredient extends Model
{
    protected $guarded = [];


    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
