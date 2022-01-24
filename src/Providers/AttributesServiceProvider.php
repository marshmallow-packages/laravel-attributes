<?php

namespace Marshmallow\Attributes\Providers;

use Illuminate\Support\ServiceProvider;
use Marshmallow\Attributes\Models\Attribute;
use Marshmallow\Attributes\Models\AttributeEntity;
use Marshmallow\Attributes\Console\Commands\PublishCommand;

class AttributesServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/marshmallow-attributes.php',
            'marshmallow-attributes'
        );

        $this->commands([
            PublishCommand::class,
        ]);

        $this->configureAttributes();
    }

    protected function registerEntities()
    {
        app('marshmallow-attributes.entities')->push('entities');
    }

    protected function configureAttributes()
    {
        Attribute::typeMap([
            'varchar' => \Marshmallow\Attributes\Models\Type\Varchar::class,
            'text' => \Marshmallow\Attributes\Models\Type\Text::class,
            'boolean' => \Marshmallow\Attributes\Models\Type\Boolean::class,
            'datetime' => \Marshmallow\Attributes\Models\Type\DateTime::class,
            'integer' => \Marshmallow\Attributes\Models\Type\Integer::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->publishes([
            __DIR__ . '/../../config/marshmallow-attributes.php' => config_path('marshmallow-attributes.php'),
        ]);
    }
}
