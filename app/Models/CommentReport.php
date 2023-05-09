<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReport extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function comment()
    {
        return $this->belongsTo(PostComment::class);
    }
}
