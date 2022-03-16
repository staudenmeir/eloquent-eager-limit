<?php

namespace Staudenmeir\EloquentEagerLimit\Tests;

use Staudenmeir\EloquentEagerLimit\Tests\Models\Post;

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

    public function testEagerLoadingWithOffset()
    {
        $posts = Post::with('tagsWithOffset')->get();

        $this->assertEquals([2, 1], $posts[0]->tagsWithOffset->pluck('id')->all());
        $this->assertEquals([5, 4], $posts[1]->tagsWithOffset->pluck('id')->all());
    }

    public function testLazyEagerLoading()
    {
        $posts = Post::all()->load('tags');

        $this->assertEquals([3, 2], $posts[0]->tags->pluck('id')->all());
        $this->assertEquals([6, 5], $posts[1]->tags->pluck('id')->all());
    }
}
