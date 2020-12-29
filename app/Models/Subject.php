<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $connection= 'sqlsrv2';

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

}


