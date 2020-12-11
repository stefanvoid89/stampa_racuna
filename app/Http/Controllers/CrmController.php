<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client;
use GuzzleHttp;
use Illuminate\Support\Facades\File;

class CrmController extends Controller
{


    public function index(Request $request)
    {
        
      //  $_acTypeCustomer = $request->input('acTypeCustomer');
      //  $_acTaxNumber = $request->input('acTaxNumber');
      //  $_acIDNumber = $request->input('acIDNumber');
      //  $_acName = $request->input('acName');
      //  $_acGender = $request->input('acGender');
      //  $_acProtectData = $request->input('acProtectData');
       // $_acProtectMail = $request->input('acProtectMail');
       // $_acProtectsms = $request->input('acProtectsms');
      //  $_acProtectNumGSM = $request->input('acProtectNumGSM');
       // $_acSourceType = $request->input('acSourceType');

        $customers = DB::connection('sqlsrv2')->select(" SELECT top 100 anId,acTypeCustomer,[acTaxNumber],[acIDNumber],[acName]
        ,[acFirstName],[acName2],[acGender],[adBirthDate],[anCodigo],case when [acProtectData]=0 then 'Da' else 'Ne' end acProtectData
        ,case when [acProtectMail]=0 then 'Da' else 'Ne' end acProtectMail,[acProtectNumFax],case when [acProtectNumTel1] =0 then 'Da' else 'Ne' end acProtectNumTel1
        ,[acProtectNumTel2],case when [acProtectsms]=0 then 'Da' else 'Ne' end acProtectSms,case when [acProtectNumGSM]=0 then 'Da' else 'Ne' end acProtectNumGSM,[acAddres],[acPostCode]
        ,[acPostName],acAddres as acAddress,[acEmailProf],[acEmail],[acnumFax],[acNumtel1],[acNumtel2],[acnumGSM],[acNote],case when [acIsNewCars]='T' then 'Da' else 'Ne' end acIsNewCars
        ,case when [acIsService]='T' then 'Da' else 'Ne' end acIsService
        ,[acIsUsedCars],[acIsSparePart], acSourceType
        FROM [Clients] "
        //,
        // [
         //   '_acTypeCustomer'   => $_acTypeCustomer,        'acTypeCustomer'        => $_acTypeCustomer,
        //    '_acTaxNumber'      => $_acTaxNumber,           'acTaxNumber'           => $_acTaxNumber,
         //   '_acIDNumber'       => $_acIDNumber,            'acIDNumber'            => $_acIDNumber,
        //    '_acName'           => $_acName,                'acName'                => $_acName,
         //   '_acGender'         => $_acGender,              'acGender'              => $_acGender,
         ////   '_acProtectData'    => $_acProtectData,         'acProtectData'         => $_acProtectData,
         //   '_acProtectMail'    => $_acProtectMail,         'acProtectMail'         => $_acProtectMail,
           // '_acProtectsms'     => $_acProtectsms,          'acProtectsms'          => $_acProtectsms,
         //   '_acProtectNumGSM'  => $_acProtectNumGSM,       'acProtectNumGSM'       => $_acProtectNumGSM,
         //   '_acSourceType'     => $_acSourceType,          'acSourceType'          => $_acSourceType,
            
      //  ]
        );
        $page = $request->input('page');


        $size = 20;
        $collect = collect($customers);

        $paginationData = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page
        );

     
        $prop_data = [
            'customers' => $paginationData,  'prop_values' => collect(
                [
                //'acTypeCustomer' => $acTypeCustomer, 'acTaxNumber' => $acTaxNumber,  'acIDNumbert' => $acIDNumbert,
                //'acName' => $acName, 'acGender' => $acGender, 'acProtectData' => $acProtectData,
                //'acProtectMail' => $acProtectMail,'acProtectNumSms' => $acProtectNumSms,  'acProtectNumGSM' => $acProtectNumGSM,
                //'acSourceType' => $acSourceType
            ]
            )
        ];

        $title = "Prikaz kupaca";

       // dd($customers);
        return view('crm_index', ["title" => $title, "prop_data" => collect($prop_data)]);

     //   return response()->json(['response' => $response]);
    }
    

    public function fetch_subjects_crm(Request $request)
    {

        $serch_term =  "%" . $request->input('search_term') . "%";

        $subjects  = DB::select("SELECT  codigo as anId, rtrim(ltrim(cast(codigo as char)))  + ' - ' +  ltrim(rtrim(isnull(nombre,' ')))+' '+ltrim(rtrim(isnull(Apellido1,''))) as acSubject
        from tgacOrotectNumSms where rtrim(ltrim(cast(codigo as char)))  + ' - ' +  ltrim(rtrim(isnull(nombre,' ')))+' '+ltrim(rtrim(isnull(Apellido1,'')))  like ? ", [$serch_term]);

        return response()->json($subjects);
    }


    public function send_mail_crm(Request $request)
    {
        // {"url":"http://miservice.hitauto/print/print/281810","file":"281810.pdf"}

        $response = "OK";

        $url = $request->input("url");
        $file = $request->input("file");
        $mail = $request->input("mail");

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
                Mail::raw("Postovani,\r\nu prilogu faktura" . $file . "\r\nLp", function ($message)  use ($path, $mail, $file) {
                    //   $message->from('us@example.com', 'Laravel');

                    $message->subject("Faktura " . $file);
                    $message->to($mail);
                    $message->attach($path);
                });
            } catch (\Exception $ex) {
                $response    = $ex->getMessage();
            }
        } else $response = "Fajl nije kreiran, mail nece biti poslan";

        return response()->json(['response' => $response]);
    }
}
