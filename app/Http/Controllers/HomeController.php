<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{


    public function index()
    {

        $title = "Hit auto miservice pocetna ";


        return view('master', ["title" => $title]);
        //dd(storage_path('app'));
    }



    public function generateXML()
    {

        $sql = "SELECT top 10 codigo as anId, rtrim(ltrim(cast(codigo as char)))  + ' - ' +  rtrim(isnull(nombre,' '))+' '+isnull(Apellido1,'') as acSubject
        from tgcliente where 1=1 ";
        $items = DB::connection()->getPdo()->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        //  $items = DB::select($sql);


        $items = ["item" => $items];

        // dd(array_map('is_int', array_keys($items)));

        // dd(array_unique(array_map('is_int', array_keys($items))) === [true]);



        //   $result = ArrayToXml::convert(['__numeric' => $items]);

        // $result = ArrayToXml::convert($items);
        // dd($result);
        // die();

        // $array = [
        //     "good guys" => [
        //         'Good guy' => [
        //             [
        //                 'name' => 'Luke Skywalker',
        //                 'weapon' => 'Lightsaber'
        //             ],
        //             [
        //                 'name' => 'Luke sdfdsf',
        //                 'weapon' => 'sdfdsf'
        //             ],
        //             [
        //                 'name' => 'Luke nesto nesto',
        //                 'weapon' => 'Lightsfsdfaber'
        //             ],
        //         ]
        //     ]


        // ];
        //  dd($items);

        $arrayToXml = new ArrayToXml($items);
        $result = $arrayToXml->prettify()->toXml();

        dd($result);
        //   /  dd($array);
    }
}
