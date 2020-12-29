<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Invoice;

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

        $sql_subjects = "SELECT * FROM subjects";
        $sql_items = "SELECT * FROM invoices";
        $subjects = DB::connection('sqlsrv2')->getPdo()->query($sql_subjects)->fetchAll(\PDO::FETCH_ASSOC);
       $invoices = DB::connection('sqlsrv2')->getPdo()->query($sql_items)->fetchAll(\PDO::FETCH_ASSOC);

    //   dd($invoices);

       foreach($subjects as &$subject){

        // echo '<br>from loop subject_id  ';
        // print_r($subject["id"]);
        // echo '<br>';


        $temp = array_filter($invoices,function($inv) use($subject){
           // print_r($inv);
            // echo '<br>inv subject_id';
            // print_r($inv['subject_id'] );
            // echo '<br>';
            // echo   $inv['subject_id'] == $subject['id'];
            // echo '<br>';
            return $inv['subject_id'] == $subject['id'];
        });
//  echo '<br>';
//         print_r($temp);
//         echo '<br>';
//         print_r(count($temp));

        if(count($temp) > 0) $subject['items']= $temp;
        // echo '<br>';
        // print_r($subject);
        // echo '<br>';

       }
       $subjects = ['subjects'=>$subjects];
       echo '<br>';
       var_dump($subjects);
       echo '<br>';

               $array = [
            "good guys" => [
                'Good guy' => [
                    [
                        'name' => 'Luke Skywalker',
                        'weapon' => 'Lightsaber'
                    ],
                    [
                        'name' => 'Luke sdfdsf',
                        'weapon' => 'sdfdsf'
                    ],
                    [
                        'name' => 'Luke nesto nesto',
                        'weapon' => 'Lightsfsdfaber'
                    ],
                ]
                ]];

        echo '<br>';
        var_dump($array);
        echo '<br>';

      die();

       //  $items = DB::select($sql);

        // $arr = [1,2,3];
        // print_r($arr);
        // echo '<br/>';
        // print_r($items);


        // dd(array_map('is_int', array_keys($items)));

        // dd(array_unique(array_map('is_int', array_keys($items))) === [true]);



        //   $result = ArrayToXml::convert(['__numeric' => $items]);

        // $result = ArrayToXml::convert($items);
        // dd($result);
        // die();


        //  dd($items);

        $arrayToXml = new ArrayToXml($subjects);
        $result = $arrayToXml->prettify()->toXml();

        dd($result);
        //   /  dd($array);
    }

    public function testModel(Request $request){

       // $invoices = Subject::find(1)->invoices()->get();

        $invoices = Subject::with('invoices')->get();

        $items = $invoices->toArray();


        // $arrayToXml = new ArrayToXml($items);
        // $result = $arrayToXml->prettify()->toXml();

        // dd($result);




        print_r($invoices->toArray());
        dd($invoices);



        foreach($invoices as $invoice){
            dd($invoice->id);
        }


      //  dd($invoices->first());
    }
}
