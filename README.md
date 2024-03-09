# Eloquent Eager Limit

[![CI](https://github.com/staudenmeir/eloquent-eager-limit/actions/workflows/ci.yml/badge.svg)](https://github.com/staudenmeir/eloquent-eager-limit/actions/workflows/ci.yml)
[![Code Coverage](https://codecov.io/gh/staudenmeir/eloquent-eager-limit/graph/badge.svg?token=J8ysbd1r80)](https://codecov.io/gh/staudenmeir/eloquent-eager-limit)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/staudenmeir/eloquent-eager-limit/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/staudenmeir/eloquent-eager-limit/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/staudenmeir/eloquent-eager-limit/v/stable)](https://packagist.org/packages/staudenmeir/eloquent-eager-limit)
[![Total Downloads](https://poser.pugx.org/staudenmeir/eloquent-eager-limit/downloads)](https://packagist.org/packages/staudenmeir/eloquent-eager-limit/stats)
[![License](https://poser.pugx.org/staudenmeir/eloquent-eager-limit/license)](https://github.com/staudenmeir/eloquent-eager-limit/blob/master/LICENSE)

> [!IMPORTANT]
> The package's code has been merged into Laravel 11+ and eager loading limits are now supported natively.

This Laravel Eloquent extension allows limiting the number of eager loading results per parent
using [window functions](https://en.wikipedia.org/wiki/Select_(SQL)#Limiting_result_rows).

Supports Laravel 5.5–10.

## Compatibility

- **MySQL 5.7+**
- **MySQL 5.5~5.6**: Due to a bug in MySQL, the package only works with strict mode disabled.  
  In your `config/database.php` file, set `'strict' => false,` for the MySQL connection.
- **MariaDB 10.2+**
- **PostgreSQL 9.3+**
- **SQLite 3.25+**: The limit is ignored on older versions of SQLite. This way, your application tests still work.
- **SQL Server 2008+**

## Installation

    composer require staudenmeir/eloquent-eager-limit:"^1.0"

Use this command if you are in PowerShell on Windows (e.g. in VS Code):

    composer require staudenmeir/eloquent-eager-limit:"^^^^1.0"

## Versions

| Laravel | Package |
|:--------|:--------|
| 10.x    | 1.8     |
| 9.x     | 1.7     |
| 8.x     | 1.6     |
| 7.x     | 1.5     |
| 6.x     | 1.4     |
| 5.8     | 1.3     |
| 5.5–5.7 | 1.2     |

## Usage

Use the `HasEagerLimit` trait in both the parent and the related model and apply `limit()/take()` to your relationship:

```php
class User extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}

class Post extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
}

$users = User::with(['posts' => function ($query) {
    $query->latest()->limit(10);
}])->get();
```

Improve the performance of `HasOne`/`HasOneThrough`/`MorphOne` relationships by applying `limit(1)`:

```php
class User extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public function latestPost()
    {
        return $this->hasOne('App\Post')->latest()->limit(1);
    }
}

class Post extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
}

$users = User::with('latestPost')->get();
```

You can also apply `offset()/skip()` to your relationship:

```php
class User extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

    public function posts()
    {
        return $this->hasMany('App\Post');
    }
}

class Post extends Model
{
    use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;
}

$users = User::with(['posts' => function ($query) {
    $query->latest()->offset(5)->limit(10);
}])->get();
```

### Package Conflicts

- `staudenmeir/laravel-adjacency-list`: Replace both packages
  with [staudenmeir/eloquent-eager-limit-x-laravel-adjacency-list](https://github.com/staudenmeir/eloquent-eager-limit-x-laravel-adjacency-list)
  to use them on the same model.
- `staudenmeir/laravel-cte`: Replace both packages
  with [staudenmeir/eloquent-eager-limit-x-laravel-cte](https://github.com/staudenmeir/eloquent-eager-limit-x-laravel-cte)
  to use them on the same model.
- `topclaudy/compoships`: Replace both packages
  with [mpyw/compoships-eager-limit](https://github.com/mpyw/compoships-eager-limit)
  to use them on the same model.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE OF CONDUCT](.github/CODE_OF_CONDUCT.md) for details.
