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



    public function generateXML(Request $request)
    {

        $sql_invoices = "SELECT NumInterno as id,Factura as faktura,FechaFactura as datum,Cliente as subject_id,chasis 
        from icar_proc.[dbo].[ICAR_vAM_PrometServiceOut] where 1=1
        -- and numinterno in (288692,287662)";
        $sql_positions = "SELECT NumIntOT as invoice_id,anQty as qty,acIdent as ident,acName as name,PrecioUnitario as price 
        from icar_proc.[dbo].[ICAR_vAM_PrometServiceOutNo] where 1=1
        -- and numintot in (288692,287662)";
        $invoices = DB::connection('icar')->getPdo()->query($sql_invoices)->fetchAll(\PDO::FETCH_ASSOC);
        $positions = DB::connection('icar')->getPdo()->query($sql_positions)->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($invoices as &$invoice) {

            // echo '<br>from loop subject_id  ';
            // print_r($subject["id"]);
            // echo '<br>';


            $temp = array_filter($positions, function ($pos) use ($invoice) {

                return $pos['invoice_id'] == $invoice['id'];
            });


            if (count($temp) > 0) $invoice['Stavke']['Stavka'][] = $temp;
        }

        $invoices = ["Dokument" => $invoices];

        $arrayToXml = new ArrayToXml($invoices, 'Dokumenti', true, 'UTF-8');

        $result = $arrayToXml->prettify()->toXml();

        //dd(gettype($result));
        dd($result);
    }
}
