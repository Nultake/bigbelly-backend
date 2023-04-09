<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function steps()
    {
        return $this->hasMany(PostStep::class);
    }

    public function tags()
    {
        return $this->hasMany(PostTag::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }
}
