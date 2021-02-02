<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client;
use GuzzleHttp;
use Illuminate\Support\Facades\File;
use Spatie\ArrayToXml\ArrayToXml;


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


        $invoices = DB::connection('icar')->select("SELECT   top 100 f.NumIntFac as id,
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



        $Clientes = collect(DB::connection('icar')->select("SELECT  codigo as anId, rtrim(ltrim(cast(codigo as char)))  + ' - ' +  rtrim(isnull(nombre,' '))+' '+isnull(Apellido1,'') as acSubject
        from tgcliente where 1=1 and codigo = :_cliente", ['_cliente' => $_Cliente]));

        $Cliente = $Clientes->first();

        $tallers = collect(DB::connection('icar')->select("SELECT 0 as anId, 'Svi' as acSubject union   all
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
                'Matric' => $Matric, 'Cliente' => $Cliente,  'taller' => $taller
            ])
        ];

        $title = "Stampa faktura";


        return view('print_index', ["title" => $title, "prop_data" => collect($prop_data)]);
    }


    public function print($id)
    {
        $currency = request()->input('currency');
        $client = new Client();

        $hash = collect(DB::connection("sqlsrv")->select("SELECT top 1 report_hash from sys_params"))->first()->report_hash;

        $baseUrl = env("REPORT_ENGINE_BASE_URL");

        $url = "$baseUrl/pdf?hash=$hash&params[id]=$id&report=invoice&params[currency]=$currency";

        //    dd($url);


        $_response = $client->get($url);
        $content = $_response->getBody()->getContents();
        $response = response()->make($content, 200);
        $response->header('Content-Type', 'application/pdf'); // change this to the download content type.
        return $response;
    }


    public function printEur($id)
    {
        $header = collect(DB::select("EXEC _MiServiceHeader :id", ['id' => $id]))->first();

        $positions =  collect(DB::select("EXEC _MiServicePositionsEUR :id", ['id' => $id]));

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


        $page_html = view("print.layouts.page_invoiceEur", ['marka' => $marka, 'location' => $location, 'mesto_prometa' => $mesto_prometa, 'title' => $title, 'header' => $header])->render();
        $html_to_props = view("print.content.invoiceEUR_print", [
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

        $id = $request->input("id");
        $file = $request->input("file");
        $mail = $request->input("mail");

        $client = new Client();

        $hash = collect(DB::connection("sqlsrv")->select("SELECT top 1 report_hash from sys_params"))->first()->report_hash;

        $baseUrl = env("REPORT_ENGINE_BASE_URL");

        $url = "$baseUrl/pdf?hash=$hash&params[id]=$id&report=invoice";


        $_response = $client->get($url);
        $content = $_response->getBody()->getContents();
        // $response = response()->make($content, 200);
        // $response->header('Content-Type', 'application/pdf'); // change this to the download content type.
        // return $response;



        try {
            Mail::raw("Postovani,\r\nu prilogu faktura" . $file . "\r\nLp", function ($message)  use ($content, $mail, $file) {
                //   $message->from('us@example.com', 'Laravel');

                $message->subject("Faktura " . $file);
                $message->to($mail);
                $message->attachData($content, $file, ["mime" => 'application/pdf']);
            });
        } catch (\Exception $ex) {
            $response    = $ex->getMessage();
            return response()->json(['error' => $response]);
        }


        return response()->json(['response' => $_response]);
    }


    public function sendMailXML(Request $request)
    {

        $client = '9955';
        $date = '2021-02-02';
        // $date = $request->input('date');
        //  $client = $request->input('client');
        //$emails =  ['stefan.milosavljevic@hitauto.rs', 'persida.pandurovic@hitauto.rs'];
        $emails =  ['stefan.milosavljevic@hitauto.rs'];


        $xml = $this->generateXML($client, $date);


        $pdfs = [];

        $invoices = DB::connection("icar")->select("SELECT nal.NumIntMostrador
        ,nal.Cliente,ltrim(rtrim(isnull(nal.Nombre,'')+' '+isnull(nal.apellido1,''))) as ImeKupca 
        ,ltrim(rtrim(cast(nal.AnoDocum as char))) + '/' + ltrim(rtrim(cast(nal.Factura as char)))  as BrojRacuna
        from  taMostrador nal
           inner join tgcliente k on k.codigo =  nal.Cliente
           where nal.fechadocumento  = cast(getdate() as date)
           and Cliente = '9955'
           and Cliente not in ('99999','501')
           order by Cliente");


        $client = new Client();

        $hash = collect(DB::connection("sqlsrv")->select("SELECT top 1 report_hash from sys_params"))->first()->report_hash;
        $baseUrl = env("REPORT_ENGINE_BASE_URL");


        foreach ($invoices as $invoice) {

            $id = $invoice->NumIntMostrador;
            $url = "$baseUrl/pdf?hash=$hash&params[id]=$id&report=parts_invoice";

            $_response = $client->get($url);
            $content = $_response->getBody()->getContents();

            $pdfs[$id . 'pdf'] =  $content;
        }

        //  dd($invoices);



        try {
            Mail::raw("Postovani,\r\nu prilogu XML sa fakturama na dan " . $date . "\r\nLp", function ($message)
            use ($xml, $emails, $pdfs, $date) {
                //   $message->from('us@example.com', 'Laravel');

                $message->subject("XML " . $date);
                $message->to($emails);
                $message->attachData($xml, $date . 'xml', ["mime" => 'application/xml ']);
                foreach ($pdfs as $key => $pdf) {
                    $message->attachData($pdf, $key, ["mime" => 'application/pdf ']);
                }
            });
        } catch (\Exception $ex) {
            $response    = $ex->getMessage();
            return response()->json(['error' => $response]);
        }
        return 1;
    }




    public function generateXML($client, $date)
    {
        // $date = $request->input('date');
        // $client = $request->input('client');

        $sql_invoices = "SELECT NumIntMostrador, cliente, Documento as BrojDokumenta,convert(varchar(20),FechaDocumento,104)  as DatumDokumenta
        , convert(varchar(20),FechaDocumento,104)   as DatumPrometa,convert(varchar(20), FechaDocumento+(select top 1 VctoDiasPrimer from tgModoPago where codigo=AlmModPago ),104)  as DatumValute
        , rtrim(isnull(Apellido1,'')+' '+isnull(Apellido2,'')+' '+isnull(Nombre,'')) as NazivKupca, DireccionEditada as AdresaKupca,(select descrip from tgpobla where id=Pobla) as MestoKupca, CIF as PIBKupca,
        (select top 1 comentario  from [taMostradorComentarios] where NumIntMostrador=m.NumIntMostrador) as Napomena
         from taMostrador m 
         where AnoDocum=2021  
         and (FechaDocumento = :date or :_date is null)
         and Cliente = :client ";

        $sql_positions = "SELECT ml.NumIntMostrador,  replace(replace(replace(replace(ml.descrip,'Å','Č'),'Ñ','Ć') ,'¯','Š'),'ã','Ž') as NazivArtikla, (select top 1 descrip from tgMarca where marca= ml.marca) as Proizvodjac
        , ml.ReferenciaEditada as KataloskiBroj,ml.Referencia as SifraArtikla,ml.Referencia as BarKod, ml.Referencia as OEBroj,'KOM' as JedinicaMere, CdadServida as Kolicina,PrecioUnitarioBruto as BrutoCena
        , ml.ImpBrutoLinea as BrutoVrednost, Dctoaplicar as Rabat,ml.PrecioUnitarioNeto as NetoCena, ImpNetoLinea as NetoVrednost, (select top 1 Porcen from tgIvaPor where codigo=ml.IVA   order by codigo,FechaInicio desc) as StopaPDV 
        , ImpNetoIVAInc-ImpNetoLinea as IznosPDV, ImpNetoIVAInc as IznosUkupno
        from taMostrador m join taMostradorLineas ml on m.NumIntMostrador=ml.NumIntMostrador 
        where AnoDocum=2021  and Cliente=37138
        and (FechaDocumento = :date or :_date is null)
        and Cliente = :client";

        $stmt_invoices = DB::connection('icar')->getPdo()->prepare($sql_invoices);
        $stmt_invoices->execute(['date' => $date, '_date' => $date, 'client' => $client]);
        $stmt_positions = DB::connection('icar')->getPdo()->prepare($sql_positions);
        $stmt_positions->execute(['date' => $date, '_date' => $date, 'client' => $client]);

        $invoices = $stmt_invoices->fetchAll(\PDO::FETCH_ASSOC);
        $positions = $stmt_positions->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($invoices as &$invoice) {

            // echo '<br>from loop subject_id  ';
            // print_r($subject["id"]);
            // echo '<br>';


            $temp = array_filter($positions, function ($pos) use ($invoice) {

                return $pos['NumIntMostrador'] == $invoice['NumIntMostrador'];
            });

            foreach ($temp as &$t) {
                unset($t['NumIntMostrador']);
            }


            if (count($temp) > 0) $invoice['Stavke']['Stavka'][] = $temp;

            unset($invoice['NumIntMostrador']);
            unset($invoice['cliente']);
        }

        $invoices = ["Dokument" => $invoices];

        $arrayToXml = new ArrayToXml($invoices, 'Dokumenti', true, 'UTF-8');

        $result = $arrayToXml->prettify()->toXml();

        //dd(gettype($result));
        return $result;
    }
}
