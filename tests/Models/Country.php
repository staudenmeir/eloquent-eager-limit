<?php

namespace Tests\Models;

class Country extends Model
{
    public function posts()
    {
        return $this->hasManyThrough(Post::class, User::class)->latest()->take(2);
    }

    public function postsWithOffset()
    {
        return $this->hasManyThrough(Post::class, User::class)->latest()->take(2)->offset(1);
    }
}
