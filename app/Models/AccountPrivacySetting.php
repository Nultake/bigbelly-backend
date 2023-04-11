<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountPrivacySetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = ['is_private' => 'boolean'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
