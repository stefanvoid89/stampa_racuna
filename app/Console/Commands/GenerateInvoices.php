<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facades\StoreFiles;
use Illuminate\Support\Facades\Log;

class GenerateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate ICAR part invoices and store them on local disk';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = date('Y-m-d');
        try {
            StoreFiles::fetchAndStoreInvoices($date);
            Log::info("Generate ICAR part invoices - Time: " . date('Y-m-d H:i:s'));
        } catch (\Exception $ex) {
            Log::error($ex);
        }
    }
}
