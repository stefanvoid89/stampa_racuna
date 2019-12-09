<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;


class PrintController extends Controller
{


    public function index(Request $request)
    {
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


        $header = collect(DB::select("SELECT   top 1  nal.NumOT as BrojNaloga, nal.AnoOT as GodinaNaloga
        ,case when nal.taller=2 then 'motrio'
        else case when nal.marca='REN' then 'renault' when nal.marca='DAC' then 'dacia' when nal.marca='NIS' then 'nissan' else 'renault' end end as Marca
        , case when nal.taller=2 then 'vidikovac' else 'sajmiste' end  as Lokacija,
        ltrim(rtrim(convert(char,nal.FechaAperturaOT,103))) + ' ' + right('0'+ltrim(rtrim(cast(nal.EntregaRealHora as int))),2)
        + ':'+ right('0'+ltrim(rtrim(cast(nal.EntregaRealMinuto as int))),2) as DatumPrijema
        ,convert(char,isnull(nal.EntregaPrevFecha,fak.fechaFactura ),103) as PredvidjeniDatumIzdavanja,
        nal.ClienteFac as NalogoDavac,  nal.Recepcionista as Korisnik,
        (select top 1 nombre from [ttRecepcionista] r where nal.Recepcionista= r.Recepcionista and nal.taller=r.taller) as RecepcionisatName,
        ltrim(rtrim(isnull(nal.Nombre,'')+' '+isnull(nal.apellido1,''))) as ImeKupca, nal.direccion as Adresa, nal.CPostal as Posta, isnull(nal.cif,'') as Pib,
        nal.perJuridica as JelFirma, k.NIntraStat as Maticni,
        ltrim(rtrim(isnull(k.Nombre,'')+' '+isnull(k.apellido1,''))) as ImeKupcaSif, k.direccion as AdresaSif, k.CPostal as PostaSif, isnull(k.cif,'') as PibSif,
        k.perJuridica as JelFirmaSif, k.NIntraStat as MaticniSif,p.Descrip as MestoSif,
        case when fak.Abono = 1 then 'STORNO RAČUN' else 'RAČUN ' end as OPisDokumena, fak.anoFactura as GodinaRačuna, ltrim(rtrim(cast(fak.anoFactura as char))) + '/' + ltrim(rtrim(cast(fak.Factura as char)))  as BrojRacuna, convert(char,fak.fechaFactura,103) as DatumRacuna,
        kup.TallModoPago as NacinPalcanjaSifra,
        (select descrip  from tgModoPago where codigo=kup.TallModoPago) as NacinPlacanjaNaziv , kup.Cliente as KupacNaFakturi,
        (select numtel1 from tgcliente where codigo=kup.Cliente) as Telefon, convert(char,nal.FecMatricPrimera,103) as datumPrveReg,nal.km as Kilom, isnull(nal.numPrisma,'') as BrojPrijema,
        nal.Siniestro As brojOdobrenja, (select descrip from tgVersion  where Modelo =nal.modelo and marca =nal.marca and version=nal.version) As MarkaModel,
        nal.Matric as RegBroj, nal.Chasis as Sasija, nal.codigofabricacion as ProizvodniBroj, convert(char,fak.FechaFactura,103) as Datumprometa, '' as BIR
        from  ttotCab nal
        inner join ttotfac fak on nal.NumInterno=fak.numinterno
        inner join ttotCargo kup on kup.Numinterno=nal.NumInterno and kup.cargo=fak.NumIntCargo
        inner join tgcliente k on k.codigo =kup.Cliente
        left  join tgpobla p on id=k.pobla
        where 1=1 and fak.NumIntFac = :id", ['id' => $id]))->first();



        $positions =  collect(DB::select("SELECT * from (
            select distinct  i.intervencion ,0 as RBR, i.Referencia as Sifra,cast(i.Descrip as varchar(max)) as Opis , str(0.00,8,2) as Kolicina,
            str(i.TotalNetoIntervencion,8,2) as Cena,str(0.00,8,2) as Popust,str(0.00,8,2) as NetoCena ,
            str(0.00,8,2) as impneto,str(0.00,8,2) as UkupnoRadBruto,str(0.00,8,2) as ukupnoDeoBruto,str(0.00,8,2) as UkupnoOstaloBruto, str(0.00 ,8,2) as PopustUsluge,
            str(0.00,8,2) as PopustDeo,str(0.00,8,2) as PopustOstalo,str(0.00,8,2) as OsnovicaZAPdv,  str(0.00,8,2) as  UkupnoPDV, str(0.00,8,2)  as UkupnoRacun,
            str(0.00,8,2) as RadNeto,str(0.00,8,2) as DeoNeto,str(0.00,8,2)as NetoOstalo
            from ttotCab nal
            inner join ttotfac fak on nal.NumInterno=fak.numinterno
            inner join ttotCargo kup on kup.Numinterno=nal.NumInterno and kup.cargo=fak.NumIntCargo
            inner join ttOtIntervencion I on i.Numinterno = kup.Numinterno
            inner join ttOTCargoInt ki on ki.NumIntOT=nal.NumInterno and ki.NumIntCargo=fak.NumIntCargo and ki.NumIntIntervencion=i.Intervencion
           -- cross apply sys.objects o
            where 1=1
            --and  object_id	 < 30
            and fak.NumIntFac  = :id
            union all
            SELECT	i.Intervencion,row_number() over(order by i.intervencion, l.referencia) as RBR, l.Referencia As Sifra,l.Descrip as Opis, format(l.CantidadHoras * (1-fak.abono * 2),'N2') as Kolicina,
            format(l.PrecioUnitario,'N2') as Cena,format(l.Descuento,'N2')  as Popust, format(l.PrecioUnitario *(1-l.Descuento/100) ,'N2') as NetoCena ,
            format(l.impneto * (1-fak.abono * 2),'N2') ,format(kup.ImpBrutoMO * (1-fak.abono * 2),'N2')  as UkupnoRadBruto,format(kup.ImpBrutoRec * (1-fak.abono * 2),'N2')  as ukupnoDeoBruto,
            format(kup.ImpBrutoTraSub * (1-fak.abono * 2),'N2')  as UkupnoOstaloBruto, format(kup.ImpDtoMO * (1-fak.abono * 2),'N2') as PopustUsluge,
            format(kup.ImpDtoRec * (1-fak.abono * 2),'N2')  as PopustDeo,format(kup.ImpDtoTraSub * (1-fak.abono * 2),'N2')  as PopustOstalo,format((kup.ImpFactura - kup.impiva) * (1-fak.abono * 2),'N2')  as OsnovicaZAPdv,
            format(kup.impiva * (1-fak.abono * 2),'N2')  as UkupnoPDV, format(kup.ImpFactura * (1-fak.abono * 2),'N2')  as UkupnoRacun,
            format((kup.ImpBrutoMO-kup.ImpDtoMO) * (1-fak.abono * 2),'N2') as RadNeto,format((kup.ImpBrutoRec-kup.ImpDtoRec)* (1-fak.abono * 2),'N2') as DeoNeto,format((kup.ImpBrutoTraSub-kup.ImpDtoTraSub) * (1-fak.abono * 2),'N2') as NetoOstalo
            from ttotCab nal
            inner join ttotfac fak on nal.NumInterno=fak.numinterno
            inner join ttotCargo kup on kup.Numinterno=nal.NumInterno and kup.cargo=fak.NumIntCargo
            inner join ttOTCargoInt ki on ki.NumIntOT=nal.NumInterno and ki.NumIntCargo=fak.NumIntCargo
            inner join ttOTIntervencion i on i.intervencion=ki.NumIntIntervencion
            inner join ttOTLinea L on l.NumIntOT=ki.NumIntOT and l.NumIntIntervencion=ki.NumIntIntervencion
            -- cross apply sys.objects o
            where 1=1
            --  and  object_id	 < 30
            and l.CantidadHoras<>0
            and fak.NumIntFac = :id2
            )q order by Intervencion, rbr", ['id' => $id, 'id2' => $id]));

        $positions_sum = $positions->firstWhere('RBR', '1');

        //  dd($positions_sum);
        // $positions = DB::select("SELECT 1 as one ", ['id' => $id]);

        // $positions_sum = DB::select("SELECT 1 as one", ['id' => $id]);


        $var = "";

        $title = "Stampa ugovora";

        $marka = $header->Marca; // renault motrio dacia
        $location = $header->Lokacija; // sajmiste
        $kome_faktura = "vlasnik"; // platioc


        $page_html = view("print.layouts.page_invoice", ['marka' => $marka, 'location' => $location, 'title' => $title, 'header' => $header])->render();

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
}
