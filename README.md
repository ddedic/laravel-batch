# Laravel Batch
## Save and update your eloquent models in batches

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wfeller/laravel-batch.svg?style=flat-square)](https://packagist.org/packages/wfeller/laravel-batch)
[![Total Downloads](https://img.shields.io/packagist/dt/wfeller/laravel-batch.svg?style=flat-square)](https://packagist.org/packages/wfeller/laravel-batch)

This package allows you to save and update models in batch, meaning you can save or
update many models at the same time, and still fire your events as single saves or
updates do.

## Installation

You can install the package via composer:

```bash
composer require wfeller/laravel-batch
```

## Usage

Just add this trait to your models:
``` php
class Car extends \Illuminate\Database\Eloquent\Model
{
    use \WF\Batch\Traits\Batchable;
    
    // ...
}
```

### Creating And Updating Models

``` php
$cars = [
    ['brand' => 'Audi', 'model' => 'A6'],
    ['brand' => 'Ford', 'model' => 'Mustang'],
    $myCar // an existing or new car instance
];

$carIds = Car::batch($cars)->save()->now();
// in a queue
Car::batch($cars)->save()->dispatch();
// Car::batch($cars)->save()->onQueue('other queue')->dispatch();
```

For the updates, there will be one DB query per updated column. For the saves, there will
only be one query per set of columns.

### Deleting Models

``` php
$cars = [
    1, // a car id
    $car, // a car instance
    ... // many more cars
];

$deletedIds = Car::batch($cars)->delete()->now();
// in a queue
Car::batch($cars)->delete()->dispatch();
// Car::batch($cars)->delete()->onQueue('other queue')->dispatch();
```

You'll have 1 query to delete your models. If you're passing model IDs, the models will be loaded from the DB to fire the deletion model events.

### Why have I made this package?

I needed to import models from an excel file, and I happened to have about 10 000 models
to import (mix of saves and updates).

For the saving part, Laravel's Model::insert() could have inserted my models in batch, but
it wasn't calling model events, so that wasn't a solution for my needs.

For the updating part, well... correct me if I'm wrong but I don't think Laravel allows
updating multiple models at once easily if they all have different data ^^'

### Some kind of benchmarks

**These benchmarks are not accurate, but they give some kind of rough idea of the potential performance improvement or usefulness of this package.**

The results vary a lot based on the DB driver, but basically that's what you get:
1. Laravel's bulk insert (this one doesn't fire model events though, the others do)
2. This package's batchSave (1.3 to 3 times slower than #1)
3. Laravel foreach create (8 to 50 times slower than #1)


* Laravel's bulk insert is the fastest, but doesn't fire model events.
``` php
User::insert([$userA, $userB, $userC]);
```

* This package takes up to 3 times as long as Laravel's bulk insert, but your model events get fired
``` php
User::batchSave([$userA, $userB, $userC]);
```

* 'Foreach create' is the slowest, taking at least 3 times longer than batchSave()
``` php
$users = [$userA, $userB, $userC];
foreach ($users as $user) 
{
    User::create($user);
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email me instead of using the issue tracker.

## Credits

- [William](https://github.com/wfeller)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
