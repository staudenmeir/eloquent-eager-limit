<?php

namespace Tests\Models;

class User extends Model
{
    public $timestamps = false;

    public function post()
    {
        return $this->hasOne(Post::class)->latest()->limit(1);
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->latest()->limit(2);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->latest()->limit(2);
    }
}
