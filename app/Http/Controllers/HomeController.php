<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class HomeController extends Controller
{


    public function index()
    {

        $title = "Hit auto miservice pocetna ";


        return view('master', ["title" => $title]);
        //dd(storage_path('app'));
    }
}
