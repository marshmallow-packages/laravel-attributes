<?php

namespace Marshmallow\Attributes\Providers;

use Illuminate\Support\Str;
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

        $this->registerModels([
            'marshmallow-attributes.attribute' => Attribute::class,
            'marshmallow-attributes.attribute_entity' => AttributeEntity::class,
        ]);

        $this->commands([
            PublishCommand::class,
        ]);

        $this->registerEntities();
        // $this->configureAttributes();
    }

    protected function registerEntities()
    {
        // Register attributes entities
        $this->app->singleton('marshmallow-attributes.entities', function ($app) {
            return collect();
        });
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

    /**
     * Register models into IoC.
     *
     * @param array $models
     *
     * @return void
     */
    protected function registerModels(array $models): void
    {
        foreach ($models as $service => $class) {
            $this->app->singleton($service, $model = $this->app['config'][Str::replaceLast('.', '.models.', $service)]);
            $model === $class || $this->app->alias($service, $class);
        }
    }
}
