<?php

declare(strict_types=1);

namespace Marshmallow\Attributes\Traits;

use Illuminate\Support\Str;

trait ConsoleTools
{
    /**
     * Publish package migrations.
     *
     * @return void
     */
    protected function publishesMigrations(string $package, bool $isModule = false): void
    {
        if (!$this->publishesResources()) {
            return;
        }

        $namespace = str_replace('laravel-', '', $package);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/' . $package);

        if (file_exists($path = $basePath . '/database/migrations')) {
            $stubs = $this->app['files']->glob($path . '/*.php');
            $existing = $this->app['files']->glob($this->app->databasePath('migrations/' . $package . '/*.php'));

            $migrations = collect($stubs)->flatMap(function ($migration) use ($existing, $package) {
                $sequence = mb_substr(basename($migration), 0, 17);
                $match = collect($existing)->first(function ($item, $key) use ($migration, $sequence) {
                    return mb_strpos($item, str_replace($sequence, '', basename($migration))) !== false;
                });

                return [$migration => $this->app->databasePath('migrations/' . $package . '/' . ($match ? basename($match) : date('Y_m_d_His', time() + mb_substr($sequence, -6)) . str_replace($sequence, '', basename($migration))))];
            })->toArray();

            $this->publishes($migrations, $namespace . '::migrations');
        }
    }

    /**
     * Publish package config.
     *
     * @return void
     */
    protected function publishesConfig(string $package, bool $isModule = false): void
    {
        if (!$this->publishesResources()) {
            return;
        }

        $namespace = str_replace('laravel-', '', $package);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/' . $package);

        if (file_exists($path = $basePath . '/config/config.php')) {
            $this->publishes([$path => $this->app->configPath(str_replace('/', '.', $namespace) . '.php')], $namespace . '::config');
        }
    }

    /**
     * Publish package views.
     *
     * @return void
     */
    protected function publishesViews(string $package, bool $isModule = false): void
    {
        if (!$this->publishesResources()) {
            return;
        }

        $namespace = str_replace('laravel-', '', $package);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/' . $package);

        if (file_exists($path = $basePath . '/resources/views')) {
            $this->publishes([$path => $this->app->resourcePath('views/vendor/' . $package)], $namespace . '::views');
        }
    }

    /**
     * Publish package lang.
     *
     * @return void
     */
    protected function publishesLang(string $package, bool $isModule = false): void
    {
        if (!$this->publishesResources()) {
            return;
        }

        $namespace = str_replace('laravel-', '', $package);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/' . $package);

        if (file_exists($path = $basePath . '/resources/lang')) {
            $this->publishes([$path => $this->app->resourcePath('lang/vendor/' . $package)], $namespace . '::lang');
        }
    }

    /**
     * Determine if the application is running in the console.
     *
     * @TODO: Implement this method to detect if we're in active dev zone or not!
     *        Ex: running inside cortex/console action
     *
     * @return bool
     */
    public function runningInDevzone()
    {
        return true;
    }

    /**
     * Register console commands.
     *
     * @param array $commands
     *
     * @return void
     */
    protected function registerCommands(array $commands): void
    {
        if (!$this->app->runningInConsole() && !$this->runningInDevzone()) {
            return;
        }

        foreach ($commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($commands));
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

    /**
     * Can publish resources.
     *
     * @return bool
     */
    protected function publishesResources(): bool
    {
        return !$this->app->environment('production') || $this->app->runningInConsole() || $this->runningInDevzone();
    }

    /**
     * Can autoload migrations.
     *
     * @param string $module
     *
     * @return bool
     */
    protected function autoloadMigrations(string $module): bool
    {
        return $this->publishesResources() && $this->app['config'][str_replace(['laravel-', '/'], ['', '.'], $module) . '.autoload_migrations'];
    }
}
