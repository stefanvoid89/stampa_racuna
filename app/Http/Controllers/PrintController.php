<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client;
use GuzzleHttp;
use Illuminate\Support\Facades\File;


class PrintController extends Controller
{


    public function index(Request $request)
    {
        // $path = env("MAIL_ATTACH_DIR") . '281882.pdf';
        // // dd($path);


        // dd(File::exists($path));

        $_AnoFactura = $request->input('AnoFactura');
        $_Factura = $request->input('Factura');
        $_FechaFacturaFrom = $request->input('FechaFacturaFrom');
        $_FechaFacturaTo = $request->input('FechaFacturaTo');
        $_numot = $request->input('numot');
        $_AnoOT = $request->input('AnoOT');
        $_Recepcionista = $request->input('Recepcionista');
        $_Chasis = $request->input('Chasis');
        $_Matric = $request->input('Matric');
        $_Cliente = $request->input('Cliente');
        $_taller = $request->input('taller');


        $date_from = null;
        $date_to = null;


        if (\Datetime::createFromFormat("Y-m-d",  $_FechaFacturaFrom)) {
            $date_from = $_FechaFacturaFrom;
        }

        if (\Datetime::createFromFormat("Y-m-d",  $_FechaFacturaTo)) {
            $date_to = $_FechaFacturaTo;
        }


        $invoices = DB::select("SELECT   top 100 f.NumIntFac as id,
        AnoFactura,Factura,convert(char,FechaFactura,104) as _FechaFactura,n.numot, n.AnoOT,n.Recepcionista,n.Chasis,n.Matric,
        rtrim(ltrim(cast(c.Cliente as char)))  + ' - ' +  rtrim(isnull(k.nombre,' '))+' '+isnull(k.Apellido1,'') as Cliente
        ,c.ImpFactura
        from ttOTFac f join ttotcab n on f.Numinterno=n.NumInterno
        inner join ttotCargo C on c.Cargo= f.NumIntCargo
        inner join tgCliente k on k.codigo = c.cliente
        where 1=1
        and ( AnoFactura = :_AnoFactura or :AnoFactura is null )
        and ( Factura = :_Factura or :Factura is null )
        and (FechaFactura >=  :_date_from or :date_from is null)
        and (FechaFactura <=    :_date_to or :date_to is null)
        and ( n.numot = :_numot or :numot is null )
        and ( n.AnoOT = :_AnoOT or :AnoOT is null )
        and ( n.Recepcionista = :_Recepcionista or :Recepcionista is null )
        and ( n.Chasis = :_Chasis or :Chasis is null )
        and ( n.Matric = :_Matric or :Matric is null )
        and ( c.Cliente = :_Cliente or :Cliente is null )
        and ( n.taller = :_taller or :taller is null )
        order by FechaFactura desc, NumOT desc  ", [
            '_AnoFactura' => $_AnoFactura,    'AnoFactura' => $_AnoFactura,
            '_Factura' => $_Factura,    'Factura' => $_Factura,
            '_date_from' => $date_from, 'date_from' => $date_from,
            '_date_to' => $date_to, 'date_to' => $date_to,
            '_numot' => $_numot,    'numot' => $_numot,
            '_AnoOT' => $_AnoOT,    'AnoOT' => $_AnoOT,
            '_Recepcionista' => $_Recepcionista,    'Recepcionista' => $_Recepcionista,
            '_Chasis' => $_Chasis,    'Chasis' => $_Chasis,
            '_Matric' => $_Matric,    'Matric' => $_Matric,
            '_Cliente' => $_Cliente,    'Cliente' => $_Cliente,
            '_taller' => $_taller,    'taller' => $_taller,

        ]);

        // dd($invoices);


        $AnoFactura          = is_null($_AnoFactura) ? "" : $_AnoFactura;
        $Factura          = is_null($_Factura) ? "" : $_Factura;
        $FechaFacturaFrom          = is_null($_FechaFacturaFrom) ? "" : $_FechaFacturaFrom;
        $FechaFacturaTo          = is_null($_FechaFacturaTo) ? "" : $_FechaFacturaTo;
        $numot          = is_null($_numot) ? "" : $_numot;
        $AnoOT          = is_null($_AnoOT) ? "" : $_AnoOT;
        $Recepcionista          = is_null($_Recepcionista) ? "" : $_Recepcionista;
        $Chasis          = is_null($_Chasis) ? "" : $_Chasis;
        $Matric          = is_null($_Matric) ? "" : $_Matric;
        $taller          = is_null($_taller) ? 0 : $_taller;



        $Clientes = collect(DB::select("SELECT  codigo as anId, rtrim(ltrim(cast(codigo as char)))  + ' - ' +  rtrim(isnull(nombre,' '))+' '+isnull(Apellido1,'') as acSubject
        from tgcliente where 1=1 and codigo = :_cliente", ['_cliente' => $_Cliente]));

        $Cliente = $Clientes->first();

        $tallers = collect(DB::select("SELECT 0 as anId, 'Svi' as acSubject union   all
        SELECT Taller as anId, Descrip as acSubject from tgTaller where Taller in (1,2) and Emp = '001'"));



        $page = $request->input('page');


        $size = 20;
        $collect = collect($invoices);

        $paginationData = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page
        );


        $prop_data = [
            'invoices' => $paginationData, 'Clientes' => $Clientes, 'tallers' => $tallers,
            'prop_values' => collect([
                'AnoFactura' => $AnoFactura, 'Factura' => $Factura, 'FechaFacturaFrom' => $FechaFacturaFrom, 'FechaFacturaTo' => $FechaFacturaTo, 'numot' => $numot,
                'AnoOT' => $AnoOT, 'Recepcionista' => $Recepcionista, 'Chasis' => $Chasis,
                'Matric' => $Matric,            'Cliente' => $Cliente,  'taller' => $taller
            ])
        ];

        $title = "Stampa faktura";


        return view('print_index', ["title" => $title, "prop_data" => collect($prop_data)]);
    }


    public function print($id)
    {
        $header = collect(DB::select("EXEC _MiServiceHeader :id", ['id' => $id]))->first();

        $positions =  collect(DB::select("EXEC _MiServicePositions :id", ['id' => $id]));

        $positions_sum = $positions->firstWhere('RBR', '1');

        //  dd($positions_sum);
        // $positions = DB::select("SELECT 1 as one ", ['id' => $id]);

        // $positions_sum = DB::select("SELECT 1 as one", ['id' => $id]);


        $var = "";

        $title = "Stampa ugovora";

        $marka = $header->Marca; // renault motrio dacia
        $location = $header->Lokacija; // sajmiste
        $kome_faktura = "vlasnik"; // platioc
        $mesto_prometa = $header->Mesto;


        $page_html = view("print.layouts.page_invoice", ['marka' => $marka, 'location' => $location, 'mesto_prometa' => $mesto_prometa, 'title' => $title, 'header' => $header])->render();
        $html_to_props = view("print.content.invoice_print", [
            'title' => $title, 'header' => $header, 'positions' => $positions, 'positions_sum' => $positions_sum
        ])->render();

        return view("print.render.render", [
            'title' => $title, 'prop_data' => collect(['html_prop' => $html_to_props, 'page' => $page_html])
        ]);
    }



    public function fetch_subjects(Request $request)
    {

        $serch_term =  "%" . $request->input('search_term') . "%";

        $subjects  = DB::select("SELECT  codigo as anId, rtrim(ltrim(cast(codigo as char)))  + ' - ' +  ltrim(rtrim(isnull(nombre,' ')))+' '+ltrim(rtrim(isnull(Apellido1,''))) as acSubject
        from tgcliente where rtrim(ltrim(cast(codigo as char)))  + ' - ' +  ltrim(rtrim(isnull(nombre,' ')))+' '+ltrim(rtrim(isnull(Apellido1,'')))  like ? ", [$serch_term]);

        return response()->json($subjects);
    }


    public function send_mail(Request $request)
    {
        // {"url":"http://miservice.hitauto/print/print/281810","file":"281810.pdf"}

        $response = "OK";

        $url = $request->input("url");
        $file = $request->input("file");

        $node_pdf_url = env("MAIL_NODE_PDF_SERVER_URL");
        $path = env("MAIL_ATTACH_DIR") . $file;

        // $path = env("MAIL_ATTACH_DIR") . '281882.pdf';
        // return response()->json(['response' => $path]);
        // return response()->json(['url' => $url, 'file' => $file, "url_pdf" => $node_pdf_url]);

        $client = new Client();


        $response = $client->post(
            $node_pdf_url,
            [
                'json' =>
                ['url' => $url, 'file' => $file]
            ],
            ['Content-Type' => 'application/json']
        );

        $response = json_decode($response->getBody(), true);


        // return response()->json(['response' => $response]);


        if (File::exists($path)) {

            try {
                Mail::raw('Faktura ' . $file, function ($message)  use ($path) {
                    //   $message->from('us@example.com', 'Laravel');

                    $message->to('smilosavljevic15@gmail.com');
                    $message->attach($path);
                });
            } catch (\Exception $ex) {
                $response    = $ex->getMessage();
            }
        } else $response = "Fajl nije kreiran, mail nece biti poslan";

        return response()->json(['response' => $response]);
    }
}
