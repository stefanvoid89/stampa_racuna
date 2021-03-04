<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;


class RegularMaintController extends Controller
{
    public function index(Request $request)
    {
        $date_from = $date_to =   $client  = $chasis = null;
        extract($request->all(), EXTR_OVERWRITE);

        //   dd($request->all());

        $fxrate = 119;
        $date_from = $date_from ?? date('Y-m-01');
        $date_to = $date_to ?? date('Y-m-t');


        $wos = DB::connection("icar")->select("
        SET NOCOUNT ON
        declare @Kurs decimal(10,2),@datefrom date, @dateto date
        select  @Kurs= :fxrate , @datefrom = :date_from , @dateto = :date_to

        SELECT c.Apellido1 as client
        ,[reg plate] as regno
        ,vv.chasis
        ,convert(varchar,cast([DATE OF INVOICE] as date),104) as date
        ,[duration in months] as duration
        ,[value of regular maintance with VAT] as RDO_Contract  
        ,format(isnull(rdo.IznFactEur,0),'N2') as NewRDO
        ,format(isnull(isnull([regular maintenance],0)+rdo.IznFactEur,0),'N2') as Total_RDO
        ,format( isnull([value of regular maintance with VAT]-(isnull([regular maintenance],0)+rdo.IznFactEur),0),'N2')  as RDO_rest
        ,format(isnull(gts.IznFactEur,0),'N2')  as New_GTS
        ,format(isnull(ost.IznFactEur,0),'N2')  as Total_ostalo
        ,format(isnull(gar.IznFactEur,0),'N2')  as Total_Garancije
        from _redovno_odrzavanje vv  
        inner join tgCliente c on c.Codigo = vv.client
        left join
        -- RDO 
        (select sum(cr.ImpFactura) as IznFactRSD,sum(cr.ImpFactura/(isnull(KursRSD,@kurs))) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='16' 
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k  on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto   group by v.chasis) rdo
        on vv.chasis=rdo.chasis
        left join
        -- GTS 
        (select sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@kurs)),2)) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v 
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='17' 
        inner join ttotFac f  on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto group by v.chasis) gts  
        on vv.chasis=gts.chasis
        left join
        -- ostalo  
        (select sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@kurs)),2)) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v 
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='01'  
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto  group by v.chasis) ost
        on vv.chasis=ost.chasis
        left join
        -- GAR
        (select sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@kurs)),2)) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v 
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='03' join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k on  k.Datum=f.fechafactura where c.Status=40 and AnoFactura>2020  group by v.chasis) gar
        on vv.chasis=gar.chasis
        where 1=1
        and (vv.chasis like :chasis or :_chasis is null)
        and (c.Apellido1 like :client or :_client is null)
        ", [
            'fxrate' => $fxrate,
            'date_from' => $date_from, 'date_to' => $date_to,
            'client' => '%' . $client . '%', '_client' => $client,
            'chasis' => '%' . $chasis . '%', '_chasis' => $chasis,
        ]);

        // $marcas = DB::connection("icar")->select("SELECT marca from tgMarca order by marca");
        // $referents = collect(DB::connection("icar")->select("SELECT 'Svi' as prijemniSavetnik union all
        // SELECT distinct ltrim(rtrim(prijemniSavetnik)) as prijemniSavetnik 
        // FROM  dbo.NezatvoreniNalozi"))->pluck('prijemniSavetnik');
        // $referent = $referent ?? 'Svi';


        $page = $request->input('page');


        $size = 20;
        $collect = collect($wos);

        $paginationData = new LengthAwarePaginator(
            $collect->forPage($page, $size),
            $collect->count(),
            $size,
            $page
        );


        $prop_data = [
            "wos" => collect($paginationData),
            //"marcas" => collect($marcas), "referents" => collect($referents),
            "prop_values" => collect([
                "date_from" => $date_from, "date_to" => $date_to, "client" => $client, "chasis" => $chasis
            ])
        ];


        $title = "Pregled redovnog odrzavanja";

        return  view(
            "regular_maint",
            ["title" => $title, "prop_data" => collect($prop_data)]
        );
    }


    public function export(Request $request)
    {

        $date_from = $date_to =   $client  = $chasis = null;
        extract($request->all(), EXTR_OVERWRITE);

        //   dd($request->all());

        $fxrate = 119;
        $date_from = $date_from ?? date('Y-m-01');
        $date_to = $date_to ?? date('Y-m-t');


        $wos = DB::connection("icar")->select("
        SET NOCOUNT ON
        declare @Kurs decimal(10,2),@datefrom date, @dateto date
        select  @Kurs= :fxrate , @datefrom = :date_from , @dateto = :date_to

        SELECT c.Apellido1 as client
        ,[reg plate] as regno
        ,vv.chasis
        ,convert(varchar,cast([DATE OF INVOICE] as date),104) as date
        ,[duration in months] as duration
        ,[value of regular maintance with VAT] as RDO_Contract  
        ,format(isnull(rdo.IznFactEur,0),'N2') as NewRDO
        ,format(isnull(isnull([regular maintenance],0)+rdo.IznFactEur,0),'N2') as Total_RDO
        ,format( isnull([value of regular maintance with VAT]-(isnull([regular maintenance],0)+rdo.IznFactEur),0),'N2')  as RDO_rest
        ,format(isnull(gts.IznFactEur,0),'N2')  as New_GTS
        ,format(isnull(ost.IznFactEur,0),'N2')  as Total_ostalo
        ,format(isnull(gar.IznFactEur,0),'N2')  as Total_Garancije
        from _redovno_odrzavanje vv  
        inner join tgCliente c on c.Codigo = vv.client
        left join
        -- RDO 
        (select sum(cr.ImpFactura) as IznFactRSD,sum(cr.ImpFactura/(isnull(KursRSD,@kurs))) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='16' 
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k  on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto   group by v.chasis) rdo
        on vv.chasis=rdo.chasis
        left join
        -- GTS 
        (select sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@kurs)),2)) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v 
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='17' 
        inner join ttotFac f  on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto group by v.chasis) gts  
        on vv.chasis=gts.chasis
        left join
        -- ostalo  
        (select sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@kurs)),2)) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v 
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='01'  
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto  group by v.chasis) ost
        on vv.chasis=ost.chasis
        left join
        -- GAR
        (select sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@kurs)),2)) As IznFactEur,v.chasis 
        from _redovno_odrzavanje v 
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='03' join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k on  k.Datum=f.fechafactura where c.Status=40 and AnoFactura>2020  group by v.chasis) gar
        on vv.chasis=gar.chasis
        where 1=1
        and (vv.chasis like :chasis or :_chasis is null)
        and (c.Apellido1 like :client or :_client is null)
        ", [
            'fxrate' => $fxrate,
            'date_from' => $date_from, 'date_to' => $date_to,
            'client' => '%' . $client . '%', '_client' => $client,
            'chasis' => '%' . $chasis . '%', '_chasis' => $chasis,
        ]);



        $export = new Export(collect($wos));

        // dd(array_keys((array)($reservations->first())));

        return Excel::download($export, 'redovno_odrzavanje_' . date("d_m_Y") . '.xlsx');
    }


    public function export_detail(Request $request)
    {
        $date_from = $date_to =   $client  = $chasis = null;
        extract($request->all(), EXTR_OVERWRITE);

        //   dd($request->all());

        $fxrate = 119;
        $date_from = $date_from ?? date('Y-m-01');
        $date_to = $date_to ?? date('Y-m-t');

        $wos = DB::connection("icar")->select("
        SET NOCOUNT ON
        declare @Kurs decimal(10,2),@datefrom date, @dateto date
        select  @Kurs= :fxrate , @datefrom = :date_from , @dateto = :date_to

        select client, anofactura as year, factura, fechafactura as date, chasis, km
        ,iznfactrsd as value_rsd, iznfacteur as value_eur 
        from (
        select c.Apellido1 as client,f.AnoFactura,f.Factura,f.FechaFactura,v.chasis,c.km
        ,sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@Kurs)),2)) As IznFactEur 
        from _redovno_odrzavanje v
        inner join tgCliente cc on cc.Codigo = v.client
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='16' 
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k  on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto   
        group by f.AnoFactura,f.Factura,f.FechaFactura,v.chasis ,c.km,c.Apellido1

        union
        select c.Apellido1 as client,f.AnoFactura,f.Factura,f.FechaFactura,v.chasis,c.km
        ,sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@Kurs)),2)) As IznFactEur 
        from _redovno_odrzavanje v 
        inner join tgCliente cc on cc.Codigo = v.client
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='17' 
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto 
        group by f.AnoFactura,f.Factura,f.FechaFactura,v.chasis,c.km,c.Apellido1

        union
        -- ostalo  
        select c.Apellido1 as client,f.AnoFactura,f.Factura,f.FechaFactura,v.chasis,c.km
        ,sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@Kurs)),2)) As IznFactEur 
        from _redovno_odrzavanje v 
        left join  ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='01'  
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k  on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020 and f.fechafactura between @datefrom and @dateto    
        group by f.AnoFactura,f.Factura,f.FechaFactura,v.chasis,c.km,c.Apellido1
        union
        -- GAR
        select c.Apellido1 as client,f.AnoFactura,f.Factura,f.FechaFactura,v.chasis,c.km
        ,sum(cr.ImpFactura) as IznFactRSD,sum(round(cr.ImpFactura/(isnull(KursRSD,@Kurs)),2)) As IznFactEur  
        from _redovno_odrzavanje v 
        left join ttotcab c on c.Chasis=v.chasis 
        inner join ttotcargo cr on cr.Numinterno=c.NumInterno and cr.TipoFacturacion='03' 
        inner join ttotFac f on f.NumIntCargo=cr.cargo 
        left join _ri_KursHRK k  on  k.Datum=f.fechafactura 
        where c.Status=40 and AnoFactura>2020  
        group by f.AnoFactura,f.Factura,f.FechaFactura,v.chasis, c.km,c.Apellido1)q
        where 1=1
        and (chasis like :chasis or :_chasis is null)
        and (client like :client or :_client is null)
        ", [
            'fxrate' => $fxrate,
            'date_from' => $date_from, 'date_to' => $date_to,
            'client' => '%' . $client . '%', '_client' => $client,
            'chasis' => '%' . $chasis . '%', '_chasis' => $chasis,
        ]);

        $export = new Export(collect($wos));

        // dd(array_keys((array)($reservations->first())));

        return Excel::download($export, 'redovno_odrzavanje_detaljno_' . date("d_m_Y") . '.xlsx');
    }
}
