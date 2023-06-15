<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitutionalPost extends Model
{
    use HasFactory;
    protected $fillable = ['post_id', 'is_hidden', 'price'];
    protected $casts = ['is_hidden' => 'boolean'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}