<?php

namespace Tests;

use Tests\Models\User;

class BelongsToManyTest extends TestCase
{
    public function testLazyLoading()
    {
        $roles = User::first()->roles()->offset(1)->get();

        $this->assertEquals([2, 1], $roles->pluck('id')->all());
    }

    public function testEagerLoading()
    {
        $users = User::with('roles')->get();

        $this->assertEquals([3, 2], $users[0]->roles->pluck('id')->all());
        $this->assertEquals([6, 5], $users[1]->roles->pluck('id')->all());
        $this->assertArrayNotHasKey('@laravel_partition := `pivot_user_id`', $users[0]->roles[0]);
    }

    public function testEagerLoadingWithOffset()
    {
        $users = User::with('rolesWithOffset')->get();

        $this->assertEquals([2, 1], $users[0]->rolesWithOffset->pluck('id')->all());
        $this->assertEquals([5, 4], $users[1]->rolesWithOffset->pluck('id')->all());
    }

    public function testLazyEagerLoading()
    {
        $users = User::all()->load('roles');

        $this->assertEquals([3, 2], $users[0]->roles->pluck('id')->all());
        $this->assertEquals([6, 5], $users[1]->roles->pluck('id')->all());
        $this->assertArrayNotHasKey('@laravel_partition := `pivot_user_id`', $users[0]->roles[0]);
    }

    public function testDistinct()
    {
        $users = User::with('cities')->get();

        $this->assertEquals(2, $users[0]->cities->count());
        $this->assertEquals(1, $users[1]->cities->count());
    }
}
