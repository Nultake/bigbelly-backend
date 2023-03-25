<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $guarded = [];

    public function privacy_setting()
    {
        return $this->hasOne(AccountPrivacySetting::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'followed_account_id');
    }

    public function followeds()
    {
        return $this->hasMany(Follower::class,);
    }
}
