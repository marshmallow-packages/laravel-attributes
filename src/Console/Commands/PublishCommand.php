<?php

namespace Marshmallow\Attributes\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PublishCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'marshmallow-attributes:install {--f|force : Overwrite any existing files.} {--r|resource=* : Specify which resources to publish.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Marshmallow Attributes Resources.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->alert($this->description);

        $this->artisanCall(
            'vendor:publish --provider="Marshmallow\Attributes\Providers\AttributesServiceProvider"',
            'Assets are published.'
        );

        $this->artisanCall(
            'migrate',
            'Database has been migrated.'
        );
    }

    protected function artisanCall($command, $info = null)
    {
        Artisan::call($command);

        if ($info) {
            $this->info($info);
        }
    }
}
