<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
