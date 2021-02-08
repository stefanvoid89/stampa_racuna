<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\SendMailsService as Service;

class SendMails extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}
