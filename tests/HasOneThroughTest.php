<?php

namespace Tests;

use Tests\Models\Country;

class HasOneThroughTest extends TestCase
{
    public function testLazyLoading()
    {
        $post = Country::first()->post()->offset(1)->first();

        $this->assertEquals(2, $post->id);
    }

    public function testEagerLoading()
    {
        $countries = Country::with('post')->get();

        $this->assertEquals(3, $countries[0]->post->id);
        $this->assertEquals(6, $countries[1]->post->id);
    }

    public function testEagerLoadingWithOffset()
    {
        $countries = Country::with('postWithOffset')->get();

        $this->assertEquals(2, $countries[0]->postWithOffset->id);
        $this->assertEquals(5, $countries[1]->postWithOffset->id);
    }

    public function testLazyEagerLoading()
    {
        $countries = Country::all()->load('post');

        $this->assertEquals(3, $countries[0]->post->id);
        $this->assertEquals(6, $countries[1]->post->id);
    }
}
