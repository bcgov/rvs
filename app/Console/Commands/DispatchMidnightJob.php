<?php

namespace App\Console\Commands;

use App\Jobs\MidnightJob;
use Illuminate\Console\Command;

class DispatchMidnightJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:dispatch-midnight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch the MidnightJob to the midnight queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        MidnightJob::dispatch()->onQueue('midnight');
        $this->info('MidnightJob has been dispatched to the midnight queue.');
    }
}
