<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Services\StoreFilesService as Service;

class StoreFiles extends Facade
{

    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}
