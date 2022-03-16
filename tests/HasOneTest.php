<?php

namespace Staudenmeir\EloquentEagerLimit\Tests;

use Staudenmeir\EloquentEagerLimit\Tests\Models\User;

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

    public function testEagerLoadingWithOffset()
    {
        $users = User::with('postWithOffset')->get();

        $this->assertEquals(2, $users[0]->postWithOffset->id);
        $this->assertEquals(5, $users[1]->postWithOffset->id);
    }

    public function testLazyEagerLoading()
    {
        $users = User::all()->load('post');

        $this->assertEquals(3, $users[0]->post->id);
        $this->assertEquals(6, $users[1]->post->id);
    }
}
