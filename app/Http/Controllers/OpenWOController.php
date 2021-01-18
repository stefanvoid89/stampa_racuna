<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Exports\Export;
use Maatwebsite\Excel\Facades\Excel;


class OpenWOController extends Controller
{
    public function index(Request $request)
    {
        $date_from = $date_to = $marca =  $referent =  $wo = $chasis = null;

        extract($request->all(), EXTR_OVERWRITE);

        $wos = DB::connection("icar")->select("SELECT convert(varchar(20),DatumNaloga,104) as DatumNaloga,marca, prijemnisavetnik, brojnaloga,
        sasija,vlasnik,delovi,rad,tudjirad,ucesce,ukupno,ukupnozaplacanje ,anid
        FROM dbo.NezatvoreniNalozi where 1=1
        and (DatumNaloga >= :date_from or :_date_from is null)
        and (DatumNaloga <= :date_to or :_date_to is null)
        and (marca = :marca or :_marca is null)
        and (prijemnisavetnik = :referent or :_referent is null)
        and (brojnaloga = :wo or :_wo is null)
        and (sasija = :chasis or :_chasis is null)", [
            'date_from' => $date_from, '_date_from' => $date_from,
            'date_to' => $date_to, '_date_to' => $date_to,
            'marca' => $marca, '_marca' => $marca,
            'referent' => $referent, '_referent' => $referent,
            'wo' => $wo, '_wo' => $wo,
            'chasis' => $chasis, '_chasis' => $chasis,
        ]);

        $marcas = DB::connection("icar")->select("SELECT marca from tgMarca order by marca");
        $referents = collect(DB::connection("icar")->select("SELECT 'Svi' as prijemniSavetnik union all
        SELECT distinct ltrim(rtrim(prijemniSavetnik)) as prijemniSavetnik 
        FROM  dbo.NezatvoreniNalozi"))->pluck('prijemniSavetnik');
        $referent = $referent ?? 'Svi';


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
            "wos" => collect($paginationData), "marcas" => collect($marcas), "referents" => collect($referents),
            "prop_values" => collect([
                "date_from" => $date_from, "date_to" => $date_to, "marca" => $marca, "referent" => $referent,
                "wo" => $wo, "chasis" => $chasis
            ])
        ];

        $title = "Pregled otvorenih radnih naloga";

        return  view(
            "open_wo",
            ["title" => $title, "prop_data" => collect($prop_data)]
        );
    }

    public function export(Request $request)
    {

        $date_from = $date_to = $marca =  $referent =  $wo = $chasis = null;

        extract($request->all(), EXTR_OVERWRITE);

        $wos = collect(DB::connection("icar")->select("SELECT convert(varchar(20),DatumNaloga,104) as DatumNaloga,marca, prijemnisavetnik, brojnaloga,
        sasija,vlasnik,delovi,rad,tudjirad,ucesce,ukupno,ukupnozaplacanje 
        FROM dbo.NezatvoreniNalozi where 1=1
        and (DatumNaloga >= :date_from or :_date_from is null)
        and (DatumNaloga <= :date_to or :_date_to is null)
        and (marca = :marca or :_marca is null)
        and (prijemnisavetnik = :referent or :_referent is null)
        and (brojnaloga = :wo or :_wo is null)
        and (sasija = :chasis or :_chasis is null)", [
            'date_from' => $date_from, '_date_from' => $date_from,
            'date_to' => $date_to, '_date_to' => $date_to,
            'marca' => $marca, '_marca' => $marca,
            'referent' => $referent, '_referent' => $referent,
            'wo' => $wo, '_wo' => $wo,
            'chasis' => $chasis, '_chasis' => $chasis,
        ]));


        $export = new Export($wos);

        // dd(array_keys((array)($reservations->first())));

        return Excel::download($export, 'otvoreni_nalozi_' . date("d_m_Y") . '.xlsx');
    }
}
