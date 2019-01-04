<?php

namespace Tests;

use Tests\Models\Country;

class HasManyThroughTest extends TestCase
{
    public function testLazyLoading()
    {
        $posts = Country::first()->posts()->offset(1)->get();

        $this->assertEquals([2, 1], $posts->pluck('id')->all());
    }

    public function testEagerLoading()
    {
        $countries = Country::with('posts')->get();

        $this->assertEquals([3, 2], $countries[0]->posts->pluck('id')->all());
        $this->assertEquals([6, 5], $countries[1]->posts->pluck('id')->all());
    }

    public function testLazyEagerLoading()
    {
        $countries = Country::all()->load('posts');

        $this->assertEquals([3, 2], $countries[0]->posts->pluck('id')->all());
        $this->assertEquals([6, 5], $countries[1]->posts->pluck('id')->all());
    }
}
