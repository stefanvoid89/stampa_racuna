<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreFilesService
{

    private  $dir_path;

    public function __construct()
    {
        $this->dir_path =  storage_path('app/invoices');
    }

    public function storeFile($file)
    {

        $filename = $file->id . '.pdf';
        $content = $file->content;


        if (!File::exists($this->dir_path)) File::makeDirectory($this->dir_path);

        $path = $this->dir_path . '/' . $filename;


        try {
            if (!File::exists($path))  File::put($path, $content);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
    }
    public function fetchFile($id)
    {


        $client = new Client();

        $hash = collect(DB::connection("sqlsrv")->select("SELECT top 1 report_hash from sys_params"))->first()->report_hash;

        $baseUrl = env("REPORT_ENGINE_BASE_URL");

        $url = "$baseUrl/pdf?hash=$hash&params[id]=$id&report=parts_invoice";


        $_response = $client->get($url);
        $content = $_response->getBody()->getContents();

        return (object)['id' => $id, 'content' => $content];
    }



    public function fetchAndStoreInvoices($date)
    {
        $all_invoices = $this->getInvoiceList($date)->pluck('NumIntMostrador')->toArray();
        $stored_invoices = $this->getFiles();
        $invoices = array_diff($all_invoices, $stored_invoices);
        //  dd($invoices);
        foreach ($invoices as $invoice) {
            $file = $this->fetchFile($invoice);
            $this->storeFile($file);
        }
    }

    public function getInvoiceList($date)
    {
        return collect(DB::connection("icar")->select("SELECT  nal.NumIntMostrador
        ,nal.Cliente,ltrim(rtrim(isnull(nal.Nombre,'')+' '+isnull(nal.apellido1,''))) as ImeKupca 
        ,ltrim(rtrim(cast(nal.AnoDocum as char))) + '/' + ltrim(rtrim(cast(nal.Documento as char)))  as BrojRacuna
        from  taMostrador nal
           inner join tgcliente k on k.codigo =  nal.Cliente
           where 1=1
           and OpcionMos in (SELECT OpcionMos FROM [dbo].[taMostradorOpcion] where facturaDirecta=1 )
        and Documento <>'0'
        and nal.fechadocumento  = ?", [$date]));
    }

    public function getFiles()
    {
        return collect(File::allFiles($this->dir_path))->map(function ($file, $key) {
            return pathinfo($file->getBasename(), PATHINFO_FILENAME);
        })->toArray();
    }
}
