<?php

namespace Marshmallow\Attributes\Tests\Feature;

use Marshmallow\Attributes\Models\Attribute;
use Marshmallow\Attributes\Tests\Stubs\User;
use Marshmallow\Attributes\Providers\AttributesServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate', ['--database' => 'testing']);
        $this->loadLaravelMigrations('testing');
        $this->withFactories(__DIR__ . '/Factories');

        // Registering the core type map
        Attribute::typeMap([
            'text' => \Marshmallow\Attributes\Models\Type\Text::class,
            'bool' => \Marshmallow\Attributes\Models\Type\Boolean::class,
            'integer' => \Marshmallow\Attributes\Models\Type\Integer::class,
            'varchar' => \Marshmallow\Attributes\Models\Type\Varchar::class,
            'datetime' => \Marshmallow\Attributes\Models\Type\Datetime::class,
        ]);

        // Push your entity fully qualified namespace
        app('marshmallow-attributes.entities')->push(User::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            AttributesServiceProvider::class,
        ];
    }
}
