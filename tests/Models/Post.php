<?php

namespace Tests\Models;

class Post extends Model
{
    public function comment()
    {
        return $this->morphOne(Comment::class, 'commentable')->latest()->limit(1);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest()->limit(2);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->latest()->limit(2);
    }
}
