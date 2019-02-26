<?php

namespace Tests\Models;

class Country extends Model
{
    public function post()
    {
        return $this->hasOneThrough(Post::class, User::class)->latest()->limit(1);
    }

    public function postWithOffset()
    {
        return $this->hasOneThrough(Post::class, User::class)->latest()->limit(1)->offset(1);
    }

    public function posts()
    {
        return $this->hasManyThrough(Post::class, User::class)->latest()->take(2);
    }

    public function postsWithOffset()
    {
        return $this->hasManyThrough(Post::class, User::class)->latest()->take(2)->offset(1);
    }
}
