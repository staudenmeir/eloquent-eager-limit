<?php

namespace Tests;

use Illuminate\Database\Connection;
use Illuminate\Database\Query\Processors\Processor;
use PDO;
use Staudenmeir\EloquentEagerLimit\Builder;

class BuilderTest extends TestCase
{
    public function testGroupLimitMySql()
    {
        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('8.0.11');
        $builder->from('posts')->groupLimit(10, 'user_id');
        $expected = 'select * from (select *, row_number() over (partition by `user_id`) as laravel_row from `posts`) as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('8.0.11');
        $builder->select('id', 'user_id')->from('posts')->latest()->groupLimit(10, 'user_id');
        $expected = 'select * from (select `id`, `user_id`, row_number() over (partition by `user_id` order by `created_at` desc) as laravel_row from `posts`) as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('8.0.11');
        $builder->from('posts')->groupLimit(10, 'user_id')->offset(1);
        $expected = 'select * from (select *, row_number() over (partition by `user_id`) as laravel_row from `posts`) as laravel_table where laravel_row <= 11 and laravel_row > 1 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('5.7.9');
        $builder->from('posts')->groupLimit(10, 'user_id');
        $expected = 'select laravel_table.*, @laravel_row := if(@laravel_partition = `user_id`, @laravel_row + 1, 1) as laravel_row, @laravel_partition := `user_id` from (select @laravel_row := 0, @laravel_partition := 0) as laravel_vars, (select * from `posts` order by `user_id` asc) as laravel_table having laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('5.7.9');
        $builder->select('id', 'user_id')->from('posts')->latest()->groupLimit(10, 'posts.user_id');
        $expected = 'select laravel_table.*, @laravel_row := if(@laravel_partition = `user_id`, @laravel_row + 1, 1) as laravel_row, @laravel_partition := `user_id` from (select @laravel_row := 0, @laravel_partition := 0) as laravel_vars, (select `id`, `user_id` from `posts` order by `posts`.`user_id` asc, `created_at` desc) as laravel_table having laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('5.7.9');
        $builder->from('posts')->groupLimit(10, 'user_id')->offset(1);
        $expected = 'select laravel_table.*, @laravel_row := if(@laravel_partition = `user_id`, @laravel_row + 1, 1) as laravel_row, @laravel_partition := `user_id` from (select @laravel_row := 0, @laravel_partition := 0) as laravel_vars, (select * from `posts` order by `user_id` asc) as laravel_table having laravel_row <= 11 and laravel_row > 1 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('5.7.9');
        $builder->select('roles.*, role_user.user_id as pivot_user_id')->from('posts')->join('role_user', 'roles.id', '=', 'role_user.role_id')->latest()->groupLimit(10, 'role_user.user_id');
        $expected = 'select laravel_table.*, @laravel_row := if(@laravel_partition = `pivot_user_id`, @laravel_row + 1, 1) as laravel_row, @laravel_partition := `pivot_user_id` from (select @laravel_row := 0, @laravel_partition := 0) as laravel_vars, (select `roles`.`*, role_user`.`user_id` as `pivot_user_id` from `posts` inner join `role_user` on `roles`.`id` = `role_user`.`role_id` order by `role_user`.`user_id` asc, `created_at` desc) as laravel_table having laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());
    }

    public function testGroupLimitMariaDb()
    {
        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('5.5.5-10.3.9-MariaDB');
        $builder->from('posts')->groupLimit(10, 'user_id');
        $expected = 'select * from (select *, row_number() over (partition by `user_id`) as laravel_row from `posts`) as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('5.5.5-10.3.9-MariaDB');
        $builder->select('id', 'user_id')->from('posts')->latest()->groupLimit(10, 'user_id');
        $expected = 'select * from (select `id`, `user_id`, row_number() over (partition by `user_id` order by `created_at` desc) as laravel_row from `posts`) as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('MySql');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('5.5.5-10.3.9-MariaDB');
        $builder->from('posts')->groupLimit(10, 'user_id')->offset(1);
        $expected = 'select * from (select *, row_number() over (partition by `user_id`) as laravel_row from `posts`) as laravel_table where laravel_row <= 11 and laravel_row > 1 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());
    }

    public function testGroupLimitPostgres()
    {
        $builder = $this->getBuilder('Postgres');
        $builder->from('posts')->groupLimit(10, 'user_id');
        $expected = 'select * from (select *, row_number() over (partition by "user_id") as laravel_row from "posts") as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('Postgres');
        $builder->select('id', 'user_id')->from('posts')->latest()->groupLimit(10, 'user_id');
        $expected = 'select * from (select "id", "user_id", row_number() over (partition by "user_id" order by "created_at" desc) as laravel_row from "posts") as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('Postgres');
        $builder->from('posts')->groupLimit(10, 'user_id')->offset(1);
        $expected = 'select * from (select *, row_number() over (partition by "user_id") as laravel_row from "posts") as laravel_table where laravel_row <= 11 and laravel_row > 1 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());
    }

    public function testGroupLimitSQLite()
    {
        $builder = $this->getBuilder('SQLite');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('3.25.0');
        $builder->from('posts')->groupLimit(10, 'user_id');
        $expected = 'select * from (select *, row_number() over (partition by "user_id") as laravel_row from "posts") as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('SQLite');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('3.25.0');
        $builder->select('id', 'user_id')->from('posts')->latest()->groupLimit(10, 'user_id');
        $expected = 'select * from (select "id", "user_id", row_number() over (partition by "user_id" order by "created_at" desc) as laravel_row from "posts") as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('SQLite');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('3.25.0');
        $builder->from('posts')->groupLimit(10, 'user_id')->offset(1);
        $expected = 'select * from (select *, row_number() over (partition by "user_id") as laravel_row from "posts") as laravel_table where laravel_row <= 11 and laravel_row > 1 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('SQLite');
        $builder->getConnection()->getPdo()->method('getAttribute')->willReturn('3.24.0');
        $builder->from('posts')->groupLimit(10, 'user_id');
        $expected = 'select * from "posts"';
        $this->assertEquals($expected, $builder->toSql());
    }

    public function testGroupLimitSqlServer()
    {
        $builder = $this->getBuilder('SqlServer');
        $builder->from('posts')->groupLimit(10, 'user_id');
        $expected = 'select * from (select *, row_number() over (partition by [user_id] order by (select 0)) as laravel_row from [posts]) as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('SqlServer');
        $builder->select('id', 'user_id')->from('posts')->latest()->groupLimit(10, 'user_id');
        $expected = 'select * from (select [id], [user_id], row_number() over (partition by [user_id] order by [created_at] desc) as laravel_row from [posts]) as laravel_table where laravel_row <= 10 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());

        $builder = $this->getBuilder('SqlServer');
        $builder->from('posts')->groupLimit(10, 'user_id')->offset(1);
        $expected = 'select * from (select *, row_number() over (partition by [user_id] order by (select 0)) as laravel_row from [posts]) as laravel_table where laravel_row <= 11 and laravel_row > 1 order by laravel_row';
        $this->assertEquals($expected, $builder->toSql());
    }

    protected function getBuilder($database)
    {
        $connection = $this->createMock(Connection::class);
        $connection->method('getPdo')->willReturn($this->createMock(PDO::class));
        $grammar = 'Staudenmeir\EloquentEagerLimit\Grammars\\'.$database.'Grammar';
        $processor = $this->createMock(Processor::class);

        return new Builder($connection, new $grammar, $processor);
    }
}
