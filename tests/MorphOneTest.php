<?php

namespace Tests;

use Tests\Models\Post;

class MorphOneTest extends TestCase
{
    public function testLazyLoading()
    {
        $comment = Post::first()->comment()->offset(1)->first();

        $this->assertEquals(2, $comment->id);
    }

    public function testEagerLoading()
    {
        $posts = Post::with('comment')->get();

        $this->assertEquals(3, $posts[0]->comment->id);
        $this->assertEquals(6, $posts[1]->comment->id);
    }

    public function testLazyEagerLoading()
    {
        $posts = Post::all()->load('comment');

        $this->assertEquals(3, $posts[0]->comment->id);
        $this->assertEquals(6, $posts[1]->comment->id);
    }
}
