<?php

namespace App\Services;

use Spatie\ArrayToXml\ArrayToXml;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Illuminate\Mail\Message;

class SendMailsService
{

    private  $dir_path;
    private $admin_emails;

    public function __construct()
    {
        $this->dir_path =  storage_path('app/invoices');
        $this->admin_emails = ['stefan.milosavljevic@hitauto.rs', 'persida.pandurovic@hitauto.rs'];
        //$this->admin_emails = ['stefan.milosavljevic@hitauto.rs'];
    }

    public function getEmails($client)
    {
        return ['stefan.milosavljevic@hitauto.rs', 'persida.pandurovic@hitauto.rs'];
        //  return ['stefan.milosavljevic@hitauto.rs'];
    }

    public function sendMails($date)
    {

        $successful = [];
        $unsuccessful = [];

        $this->storeClientList($date);

        $clients = $this->getClientListForSending($date);

        // dd($clients);

        foreach ($clients as $client) {
            try {
                $this->sendMail($client, $date);
                $successful[] = $client;
            } catch (\Throwable $ex) {
                $unsuccessful[] = $client;
                Log::error(__METHOD__ . ' ex: ' . $ex->getMessage());
            }
        }
        $this->updateStatus($successful);

        $this->sendConfirmationMail($successful, $unsuccessful);

        return 1;
    }

    public function updateStatus($successful)
    {
        foreach ($successful as $s) {
            try {
                DB::connection('sqlsrv')->update("UPDATE invoice_mail_log set status = 1 where id = ?", [$s->id]);
            } catch (\Exception $ex) {
                Log::error(__METHOD__ . ' ex: ' . $ex->getMessage());
            }
        }
    }

    public function sendConfirmationMail($successful, $unsuccessful)
    {
        if (count($successful) > 0 || count($unsuccessful) > 0)
            try {
                Mail::send(
                    'confirmation_mail',
                    ['successful' => $successful, 'unsuccessful' => $unsuccessful],
                    function ($message) {
                        $message->to($this->admin_emails);
                        $message->subject('Izvestaj o slanju faktura klijentima');
                    }
                );
            } catch (\Exception $ex) {
                Log::error(__METHOD__ . ' ex: ' . $ex->getMessage());
            }
    }

    public function sendMail($client, $date)
    {

        $clientId = $client->client;
        $clientName = $client->client_name;

        $emails = $this->getEmails($client);

        $xml = $this->generateXML($date, $clientId);

        $invoices = $this->getInvoiceList($date, $clientId);

        //dd($invoices);


        try {
            // $message = new Message();
            Mail::raw("Postovani,\r\nu prilogu fakture na dan " . $date . "\r\nLp", function ($message)
            use ($xml, $emails, $date, $invoices, $clientName) {
                $message->subject("Fakture za dan " . $date . ' - ' . $clientName);
                $message->to($emails);
                $message->attachData($xml, $date . '.xml', ["mime" => 'application/xml ']);

                foreach ($invoices as $invoice) {

                    $filename = $invoice->NumIntMostrador . '.pdf';
                    $path = $this->dir_path . '/' . $filename;
                    $name = $invoice->BrojRacuna . '.pdf';

                    if (!File::exists($path)) {
                        throw new \Exception('Ne postoji file ' . $filename);
                        break;
                    }

                    $message->attach($path,  ["as" => $name, "mime" => 'application/pdf']);
                }
            });
        } catch (\Throwable $ex) {
            Log::error(__METHOD__ . ' ex: ' . $ex->getMessage());
            throw $ex;
        }
    }


    public function getClientListForSending($date)
    {
        return  DB::connection('sqlsrv')->select("SELECT id,client, client_name from invoice_mail_log where date = ? and status = 0", [$date]);
    }

    public function storeClientList($date)
    {

        $clients =  DB::connection("icar")->select("SELECT  distinct  top 2 nal.Cliente as client
        ,ltrim(rtrim(isnull(nal.Nombre,'')+' '+isnull(nal.apellido1,'')))  as client_name
        from  taMostrador nal
           inner join tgcliente k on k.codigo =  nal.Cliente
           where 1=1
           and OpcionMos in (SELECT OpcionMos FROM [dbo].[taMostradorOpcion] where facturaDirecta=1 )
        and Documento <>'0'
        and nal.cif is not null
        and nal.fechadocumento  = ?", [$date]);

        $storedClients = DB::connection('sqlsrv')->select("SELECT client from invoice_mail_log where date = ?", [$date]);

        $storedClientsIds = array_column($storedClients, 'client');
        $clientsIds = array_column($clients, 'client');

        $idsToStore = array_diff($clientsIds, $storedClientsIds);

        //dd($idsToStore);

        if (collect($idsToStore)->count() > 0) {

            foreach ($clients as $_client) {
                $client = (object) ($_client);

                if ($client->client != 0 && in_array($client->client, $idsToStore)) {
                    DB::connection('sqlsrv')->insert("INSERT into invoice_mail_log ( date  , client , client_name ,status )
                    SELECT ?,?,?,?", [$date, $client->client, $client->client_name, 0]);
                }
            }
        }
    }


    public function getInvoiceList($date, $client)
    {

        return  collect(DB::connection("icar")->select("SELECT  nal.NumIntMostrador
        ,nal.Cliente,ltrim(rtrim(isnull(nal.Nombre,'')+' '+isnull(nal.apellido1,''))) as ImeKupca 
        ,ltrim(rtrim(cast(nal.AnoDocum as char))) + '/' + ltrim(rtrim(cast(nal.Documento as char)))  as BrojRacuna
        from  taMostrador nal
           inner join tgcliente k on k.codigo =  nal.Cliente
           where 1=1
           and OpcionMos in (SELECT OpcionMos FROM [dbo].[taMostradorOpcion] where facturaDirecta=1 )
        and Documento <>'0'
        and nal.fechadocumento  = ? 
        and nal.Cliente = ?", [$date, $client]));
    }


    public function generateXML($date, $client)
    {
        // $date = $request->input('date');
        // $client = $request->input('client');

        $sql_invoices = "SELECT NumIntMostrador, cliente, Documento as BrojDokumenta,convert(varchar(20),FechaDocumento,104)  as DatumDokumenta
        , convert(varchar(20),FechaDocumento,104)   as DatumPrometa,convert(varchar(20), FechaDocumento+(select top 1 VctoDiasPrimer from tgModoPago where codigo=AlmModPago ),104)  as DatumValute
        , rtrim(isnull(Apellido1,'')+' '+isnull(Apellido2,'')+' '+isnull(Nombre,'')) as NazivKupca, DireccionEditada as AdresaKupca,(select descrip from tgpobla where id=Pobla) as MestoKupca, CIF as PIBKupca,
        (select top 1 comentario  from [taMostradorComentarios] where NumIntMostrador=m.NumIntMostrador) as Napomena
         from taMostrador m 
         where AnoDocum >= 2021  
         and (FechaDocumento = :date or :_date is null)
         and Cliente = :client ";

        $sql_positions = "SELECT ml.NumIntMostrador,  replace(replace(replace(replace(ml.descrip,'Å','Č'),'Ñ','Ć') ,'¯','Š'),'ã','Ž') as NazivArtikla, (select top 1 descrip from tgMarca where marca= ml.marca) as Proizvodjac
        , ml.ReferenciaEditada as KataloskiBroj,ml.Referencia as SifraArtikla,ml.Referencia as BarKod, ml.Referencia as OEBroj,'KOM' as JedinicaMere, CdadServida as Kolicina,PrecioUnitarioBruto as BrutoCena
        , ml.ImpBrutoLinea as BrutoVrednost, Dctoaplicar as Rabat,ml.PrecioUnitarioNeto as NetoCena, ImpNetoLinea as NetoVrednost, (select top 1 Porcen from tgIvaPor where codigo=ml.IVA   order by codigo,FechaInicio desc) as StopaPDV 
        , ImpNetoIVAInc-ImpNetoLinea as IznosPDV, ImpNetoIVAInc as IznosUkupno
        from taMostrador m join taMostradorLineas ml on m.NumIntMostrador=ml.NumIntMostrador 
        where AnoDocum >= 2021 
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
