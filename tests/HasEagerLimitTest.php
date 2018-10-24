<?php

namespace Tests;

use Tests\Models\Country;
use Tests\Models\Post;
use Tests\Models\User;

class HasEagerLimitTest extends TestCase
{
    public function testHasOne()
    {
        $users = User::with('post')->get();

        $this->assertEquals(3, $users[0]->post->id);
        $this->assertEquals(6, $users[1]->post->id);
    }

    public function testMorphOne()
    {
        $posts = Post::with('comment')->get();

        $this->assertEquals(3, $posts[0]->comment->id);
        $this->assertEquals(6, $posts[1]->comment->id);
    }

    public function testHasMany()
    {
        $users = User::with('posts')->get();

        $this->assertEquals([3, 2], $users[0]->posts->pluck('id')->all());
        $this->assertEquals([6, 5], $users[1]->posts->pluck('id')->all());
    }

    public function testHasManyThrough()
    {
        $countries = Country::with('posts')->get();

        $this->assertEquals([3, 2], $countries[0]->posts->pluck('id')->all());
        $this->assertEquals([6, 5], $countries[1]->posts->pluck('id')->all());
    }

    public function testMorphMany()
    {
        $posts = Post::with('comments')->get();

        $this->assertEquals([3, 2], $posts[0]->comments->pluck('id')->all());
        $this->assertEquals([6, 5], $posts[1]->comments->pluck('id')->all());
    }

    public function testBelongsToMany()
    {
        $users = User::with('roles')->get();

        $this->assertEquals([3, 2], $users[0]->roles->pluck('id')->all());
        $this->assertEquals([6, 5], $users[1]->roles->pluck('id')->all());
    }

    public function testMorphToMany()
    {
        $posts = Post::with('tags')->get();

        $this->assertEquals([3, 2], $posts[0]->tags->pluck('id')->all());
        $this->assertEquals([6, 5], $posts[1]->tags->pluck('id')->all());
    }
}
