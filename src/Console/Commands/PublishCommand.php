<?php

namespace MarJose123\Ninshiki\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'ninshiki:publish')]
class PublishCommand extends Command
{
    protected $signature = 'ninshiki:publish';

    protected $description = 'Publish all of the Ninshiki resources';

    public function handle(): void
    {
        //        $this->call('vendor:publish', [
        //            '--tag' => 'ninshiki-config',
        //            '--force' => $this->hasOption('force'),
        //        ]);

        $this->call('vendor:publish', [
            '--tag' => 'ninshiki-assets',
            '--force' => true,
        ]);

        $this->call('view:clear');

    }

    /**
     * Get the console command options.
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Overwrite any existing files'],
        ];
    }
}
