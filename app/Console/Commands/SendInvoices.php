<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Facades\SendMails;
use Illuminate\Support\Facades\Log;

class SendInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send ICAR part invoices by mail';

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
            SendMails::sendMails($date);
            Log::info("Send ICAR part invoices - Time: " . date('Y-m-d H:i:s'));
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
}
