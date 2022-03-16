<?php

namespace Staudenmeir\EloquentEagerLimit\Tests;

use Staudenmeir\EloquentEagerLimit\Tests\Models\User;

class HasManyTest extends TestCase
{
    public function testLazyLoading()
    {
        $posts = User::first()->posts()->offset(1)->get();

        $this->assertEquals([2, 1], $posts->pluck('id')->all());
    }

    public function testEagerLoading()
    {
        $users = User::with('posts')->get();

        $this->assertEquals([3, 2], $users[0]->posts->pluck('id')->all());
        $this->assertEquals([6, 5], $users[1]->posts->pluck('id')->all());
        $this->assertArrayNotHasKey('laravel_row', $users[0]->post);
        $this->assertArrayNotHasKey('@laravel_partition := `user_id`', $users[0]->post);
    }

    public function testEagerLoadingWithOffset()
    {
        $users = User::with('postsWithOffset')->get();

        $this->assertEquals([2, 1], $users[0]->postsWithOffset->pluck('id')->all());
        $this->assertEquals([5, 4], $users[1]->postsWithOffset->pluck('id')->all());
    }

    public function testLazyEagerLoading()
    {
        $users = User::all()->load('posts');

        $this->assertEquals([3, 2], $users[0]->posts->pluck('id')->all());
        $this->assertEquals([6, 5], $users[1]->posts->pluck('id')->all());
        $this->assertArrayNotHasKey('laravel_row', $users[0]->post);
        $this->assertArrayNotHasKey('@laravel_partition := `user_id`', $users[0]->post);
    }
}
