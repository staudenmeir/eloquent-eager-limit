<?php

namespace Staudenmeir\EloquentEagerLimit\Tests\Models;

class User extends Model
{
    public $timestamps = false;

    public function post()
    {
        return $this->hasOne(Post::class)->latest()->limit(1);
    }

    public function postWithOffset()
    {
        return $this->hasOne(Post::class)->latest()->limit(1)->offset(1);
    }

    public function posts()
    {
        return $this->hasMany(Post::class)->latest()->limit(2);
    }

    public function postsWithOffset()
    {
        return $this->hasMany(Post::class)->latest()->limit(2)->offset(1);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class)->latest()->limit(2);
    }

    public function rolesWithOffset()
    {
        return $this->belongsToMany(Role::class)->latest()->limit(2)->offset(1);
    }
}
