<?php

declare(strict_types=1);

namespace Marshmallow\Attributes\Providers;

use Illuminate\Support\ServiceProvider;
use Marshmallow\Attributes\Models\Attribute;
use Marshmallow\Attributes\Traits\ConsoleTools;
use Marshmallow\Attributes\Models\AttributeEntity;
use Marshmallow\Attributes\Console\Commands\MigrateCommand;
use Marshmallow\Attributes\Console\Commands\PublishCommand;
use Marshmallow\Attributes\Console\Commands\RollbackCommand;

class AttributesServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        MigrateCommand::class => 'command.marshmallow-attributes.migrate',
        PublishCommand::class => 'command.marshmallow-attributes.publish',
        RollbackCommand::class => 'command.marshmallow-attributes.rollback',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/config.php'), 'marshmallow-attributes');

        // Bind eloquent models to IoC container
        $this->registerModels([
            'marshmallow-attributes.attribute' => Attribute::class,
            'marshmallow-attributes.attribute_entity' => AttributeEntity::class,
        ]);

        // Register attributes entities
        $this->app->singleton('marshmallow-attributes.entities', function ($app) {
            return collect();
        });

        // Register console commands
        $this->registerCommands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish Resources
        $this->publishesConfig('marshmallow/laravel-attributes');
        $this->publishesMigrations('marshmallow/laravel-attributes');
        !$this->autoloadMigrations('marshmallow/laravel-attributes') || $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
    }
}
