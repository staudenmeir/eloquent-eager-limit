<?php

namespace Tests;

use Tests\Models\User;

class HasOneTest extends TestCase
{
    public function testLazyLoading()
    {
        $post = User::first()->post()->offset(1)->first();

        $this->assertEquals(2, $post->id);
    }

    public function testEagerLoading()
    {
        $users = User::with('post')->get();

        $this->assertEquals(3, $users[0]->post->id);
        $this->assertEquals(6, $users[1]->post->id);
    }

    public function testLazyEagerLoading()
    {
        $users = User::all()->load('post');

        $this->assertEquals(3, $users[0]->post->id);
        $this->assertEquals(6, $users[1]->post->id);
    }
}
