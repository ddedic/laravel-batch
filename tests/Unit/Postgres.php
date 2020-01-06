<?php

namespace WF\Batch\Tests\Unit;

use WF\Batch\Tests\Models\Car;
use WF\Batch\Tests\TestCase;

class Postgres extends TestCase
{
    use SaveTests {
        formatCar as saveTestsFormatCar;
    }
    use DeleteTests;

    public function setUp() : void
    {
        $this->supportsTimezones = false;
        parent::setUp();
    }

    protected function databaseDriver() : array
    {
        return [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'password'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ];
    }

    protected function formatCar(Car $car) : array
    {
        $attributes = $this->saveTestsFormatCar($car);
        $attributes['binary'] = $car->wasRecentlyCreated ? 'a' : 'b'; // fuck it - fixme...
        $attributes['char'] = trim($attributes['char']);
        return $attributes;
    }
}
