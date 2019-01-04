<?php

namespace Tests;

use Tests\Models\Post;

class MorphToManyTest extends TestCase
{
    public function testLazyLoading()
    {
        $tags = Post::first()->tags()->offset(1)->get();

        $this->assertEquals([2, 1], $tags->pluck('id')->all());
    }

    public function testEagerLoading()
    {
        $posts = Post::with('tags')->get();

        $this->assertEquals([3, 2], $posts[0]->tags->pluck('id')->all());
        $this->assertEquals([6, 5], $posts[1]->tags->pluck('id')->all());
    }

    public function testLazyEagerLoading()
    {
        $posts = Post::all()->load('tags');

        $this->assertEquals([3, 2], $posts[0]->tags->pluck('id')->all());
        $this->assertEquals([6, 5], $posts[1]->tags->pluck('id')->all());
    }
}
