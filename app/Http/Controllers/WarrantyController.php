<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class WarrantyController extends Controller
{
    public function index(Request $request)
    {

        $subject = null;

        extract($request->all(), EXTR_OVERWRITE);


        // dd($subject);

        $items = DB::connection('icar')->select("SELECT w.id,w.subject,w.invoice,w.invoice_date, w.car,chasis,wt.type
        ,case when isnull(w.approved,0)=1 then 'DA' else 'NE' end as approved_text,isnull(w.approved,'0') as approved,
        comment_approved,date_approved
        from _Warranty w 
        inner join (SELECT codigo as id, descrip as type from ttOTStatus where descrip like '%reklamaci%') wt 
        on w.type_id = wt.id
        where 1=1
        and ( subject like  :_subject  or :subject is null )
        order by w.id desc ", [
            '_subject' => '%' . $subject . '%',    'subject' => $subject

        ]);

        $page = $request->input('page');


        $size = 20;
        $collect = collect($items);

        $paginationData = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page
        );


        $prop_data = [
            'items' => $paginationData,
            'prop_values' => collect([
                'items' => $items
                //  'subject' => $subject
            ])
        ];

        $title = "Pregled reklamacija";

        // dd($items);
        return view('warranty', ["title" => $title, "prop_data" => collect($prop_data)]);
    }

    public function create_edit(Request $request, $id = null)
    {


        $edit = ($request->is('warranty/create') ? false : true);

        //   dd($edit);

        $subject        = '';
        $phone          = '';
        $address        = '';
        $email          = '';
        $invoice        = '';
        $invoice_date  = date("Y-m-d");
        $car           = '';
        $chasis        = '';
        $comment       = '';
        $type_id       = '';
        $date          = date("Y-m-d");
        $clerk         = '';
        $approved          = '';
        $approved_text          = '';
        $date_approved     = date("Y-m-d");
        $comment_approved  = '';

        $warranty_types = collect(DB::select("SELECT ltrim(rtrim(codigo)) as id, ltrim(rtrim(descrip)) as type 
        from ttOTStatus where descrip like '%reklamaci%' order by id"));

        if ($edit) {
            $warranty = collect(DB::select("SELECT id	,subject 	,phone   	,address 	,email   	,invoice 	
            ,invoice_date	,car	,chasis 	,comment	,type_id	,date 	,clerk, isnull(approved,0)
            ,date_approved, isnull(comment_approved,''),case when isnull(approved,0)=1 then 'DA' else 'NE' end as approved_text
             from _Warranty 
            where id = :id", ['id' => $id]))->first();
            $warranty_array = (array) $warranty;

            extract($warranty_array, EXTR_OVERWRITE);
        }

        $id = $id ?? 0;

        // dd(get_defined_vars());

        //dd($subject );

        $title =  $edit ?  "Izmena reklamacije" : "Nova reklamacija";

        $prop_data = [
            'warranty_types' => $warranty_types,
            "prop_values" => collect([
                'subject' => $subject, 'phone' => $phone, 'address' => $address,
                'email' => $email, 'invoice' => $invoice, 'invoice_date' => $invoice_date,
                'car' => $car, 'chasis' => $chasis, 'comment' => $comment,
                'type_id' => $type_id, 'date' => $date, 'clerk' => $clerk, 'approved' => $approved,
                'date_approved' => $date_approved, 'comment_approved' => $comment_approved, 'approved_text' => $approved_text
            ])
        ];

        return  view(
            "warranty_create",
            ["title" => $title, "prop_data" => collect($prop_data), 'id' => $id]
        );
    }



    public function fetch_data(Request $request)
    {

        $invoice = $request->input('invoice');
        $invoice_date = $request->input('invoice_date');


        $errors = collect([]);

        if (!$invoice)  $errors->push('Morate uneti broj fakture');
        if (!$invoice_date)  $errors->push('Morate uneti datum fakture');

        $__invoice = null;
        $_invoice = null;

        if ($errors->count() == 0) {
            try {
                $__invoice = collect(DB::select("SELECT ltrim(rtrim(n.chasis)) as chasis,ltrim(rtrim(m.descrip)) as car,
                ltrim(rtrim(ltrim(isnull(kup.nombre,'')) +' '+kup.Apellido1 +' '+ltrim(isnull(kup.Apellido2,'')))) as subject,
                ltrim(rtrim(kup.NumGSM)) as phone,ltrim(rtrim(isnull(kup.EMail,''))) as email, ltrim(rtrim(kup.DireccionEditada))  as address, 
                cast(f.fechaFactura as date) as invoice_date,isnull(n.statusencurso,'11') as type_id,ltrim(rtrim(f.Usuario)) as clerk
        from ttOTFac f 
        inner join ttOTCargo c on f.Numinterno=c.Numinterno and f.NumIntCargo=c.Cargo
        inner join ttotcab n on f.Numinterno=n.numinterno 
        inner join tgmodelo m on n.modelo=m.modelo and n.marca=m.marca 
        inner join tgCliente kup on c.cliente =kup.codigo 
        where factura = :invoice and year(FechaFactura)=year(:invoice_date)", ['invoice' => $invoice, 'invoice_date' => $invoice_date]));
            } catch (\Exception $e) {
                $errors->push('Doslo je do greske: ' . $e->getMessage());
            }

            if ($__invoice) $_invoice = $__invoice->first();
        }




        return response()->json(['invoice' => $_invoice,  'errors' => $errors]);
    }
    public function store_update(Request $request, $id = null)
    {

        $subject        = null;
        $phone          = null;
        $address        = null;
        $email          = null;
        $invoice        = null;
        $invoice_date  = null;
        $car           = null;
        $chasis        = null;
        $comment       = null;
        $type_id       = null;
        $date            = null;
        $clerk           = null;
        $approved          = null;
        $date_approved     = null;
        $comment_approved  = null;
        $approved_text  = null;

        extract($request->all(), EXTR_OVERWRITE);


        $errors = collect([]);

        if (!isset($subject)) $errors->push('Morate uneti subjekta');
        if (!isset($phone)) $errors->push('Morate uneti telefon');
        if (!isset($address)) $errors->push('Morate uneti addresu');
        if (!isset($email)) $errors->push('Morate uneti email');
        if (!isset($invoice)) $errors->push('Morate uneti račun');
        if (!isset($invoice_date)) $errors->push('Morate uneti datum računa');
        if (!isset($car)) $errors->push('Morate uneti vozilo');
        if (!isset($chasis)) $errors->push('Morate uneti šasiju');
        if (!isset($comment)) $errors->push('Morate uneti komentar');
        if (!isset($type_id)) $errors->push('Morate uneti tip reklamacije');
        if (!isset($date)) $errors->push('Morate uneti datum reklamacije');
        if (!isset($clerk)) $errors->push('Morate uneti korisnika');
        //if (!isset($approved)) $errors->push('reklamacija nije potvrdjena');
        //if (!isset($comment_approved)) $errors->push('opis  potvrdjene reklamacije ');

        if ($errors->count() == 0) {
            if (($request->method() == "POST") && !isset($id)) {
                DB::insert(
                    "INSERT into 	_Warranty (subject 	,phone   	,address 	,email   	,invoice 	
                ,invoice_date	,car	,chasis 	,comment	,type_id	,date 	,clerk
                ,approved,  date_approved, comment_approved)
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)  ",
                    [
                        $subject, $phone, $address, $email, $invoice, $invoice_date, $car, $chasis, $comment, $type_id, $date, $clerk, $approved, $date_approved, $comment_approved
                    ]
                );
            }

            if (($request->method() == "PUT") && isset($id)) {
                DB::update(
                    "UPDATE	_Warranty  set subject = isnull(?,subject)	,phone = isnull(?,phone)  	,address = isnull(?,address),
                email = isnull(?,email)  	,invoice = isnull(?,invoice) ,invoice_date = isnull(?,invoice_date)	,car = isnull(?,car),
                chasis = isnull(?,chasis)	,comment = isnull(?,comment)	,type_id = isnull(?,type_id)	,date = isnull(?,date),
                clerk = isnull(?,clerk), approved = isnull(?,approved), date_approved=isnull(?,date_approved), comment_approved=isnull(?, comment_approved) 
                 where id = ?",
                    [
                        $subject, $phone, $address, $email, $invoice, $invoice_date, $car, $chasis, $comment, $type_id, $date, $clerk,
                        $approved, $date_approved, $comment_approved,
                        $id

                    ]

                );
            }
        }

        return response()->json(['errors' => $errors]);
    }
    public function print_warranty($id)
    {
        $items = collect(DB::select("SELECT w.id,w.subject,w.invoice,w.invoice_date, w.car,chasis,wt.type,phone ,
            address, email, comment,type_id, w.date as DateWarr, clerk
        from _Warranty w 
        inner join (SELECT codigo as id, descrip as type from ttOTStatus where descrip like '%reklamaci%') wt 
        on w.type_id = wt.id
        where 1=1
        and ( w.id= :id)
        order by w.id desc ", ['id' => $id]))->first();

        $var = "";

        $title = "Stampa reklamacije - potvrda o reklamaciji";

        // $marka = $header->Marca; // renault motrio dacia
        // $location = $header->Lokacija; // sajmiste
        // $kome_faktura = "vlasnik"; // platioc
        // $mesto_prometa= $header->Mesto;


        $page_html = view("print.layouts.page_warranty", ['title' => $title, 'items' => $items])->render();
        $html_to_props = view("print.content.warranty_print", [
            'title' => $title, 'items' => $items
            //, 'positions' => $items, 'note' => $items,'customer' => $items
        ])->render();

        return view("print.render.render", [
            'title' => $title, 'prop_data' => collect(['html_prop' => $html_to_props, 'page' => $page_html])
        ]);
    }
}
