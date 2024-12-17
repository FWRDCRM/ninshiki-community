<?php

namespace MarJose123\Ninshiki\Commands;

use Illuminate\Console\Command;

class NinshikiCommand extends Command
{
    public $signature = 'ninshiki';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
