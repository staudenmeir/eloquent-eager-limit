<?php

namespace Tests;

use Tests\Models\Post;

class MorphManyTest extends TestCase
{
    public function testLazyLoading()
    {
        $comments = Post::first()->comments()->offset(1)->get();

        $this->assertEquals([2, 1], $comments->pluck('id')->all());
    }

    public function testEagerLoading()
    {
        $posts = Post::with('comments')->get();

        $this->assertEquals([3, 2], $posts[0]->comments->pluck('id')->all());
        $this->assertEquals([6, 5], $posts[1]->comments->pluck('id')->all());
    }

    public function testLazyEagerLoading()
    {
        $posts = Post::all()->load('comments');

        $this->assertEquals([3, 2], $posts[0]->comments->pluck('id')->all());
        $this->assertEquals([6, 5], $posts[1]->comments->pluck('id')->all());
    }
}
