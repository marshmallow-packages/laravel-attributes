<?php

declare(strict_types=1);

namespace Marshmallow\Attributes\Console\Commands;

use Illuminate\Console\Command;

class RollbackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marshmallow:rollback:attributes {--f|force : Force the operation to run when in production.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback Marshmallow Attributes Tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $path = config('marshmallow-attributes.autoload_migrations') ?
            'vendor/marshmallow/laravel-attributes/database/migrations' :
            'database/migrations/marshmallow/laravel-attributes';

        if (file_exists($path)) {
            $this->call('migrate:reset', [
                '--path' => $path,
                '--force' => $this->option('force'),
            ]);
        } else {
            $this->warn('No migrations found! Consider publish them first: <fg=green>php artisan marshmallow:publish:attributes</>');
        }

        $this->line('');
    }
}
