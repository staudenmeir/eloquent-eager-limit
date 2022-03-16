<?php

namespace Staudenmeir\EloquentEagerLimit\Tests\Models;

class Post extends Model
{
    public function comment()
    {
        return $this->morphOne(Comment::class, 'commentable')->latest()->limit(1);
    }

    public function commentWithOffset()
    {
        return $this->morphOne(Comment::class, 'commentable')->latest()->limit(1)->offset(1);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest()->limit(2);
    }

    public function commentsWithOffset()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest()->limit(2)->offset(1);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->latest()->limit(2);
    }

    public function tagsWithOffset()
    {
        return $this->morphToMany(Tag::class, 'taggable')->latest()->limit(2)->offset(1);
    }
}
