<table id="main_table" ref="main_table" width="100%">


    <tr>
        <td>
            <table id="pozicije_table" class="parent"
                style="font-size:8pt;border-collapse: collapse; margin-top: -1px;table-layout: fixed; text-align:center;width:100%">


                <colgroup>
                    <col style="width:120px">
                    <col style="width:50px">
                    <col>
                    <col style="width:100px">
                    <col style="width:50px">
                    <col style="width:100px">
                    <col style="width:100px">
                </colgroup>


                <thead style="border:1px solid black;">


                    <tr>
                        <th colspan="3" style="text-align:left">Datum prometa dobara i usluga: {{$header->Datumprometa}}
                        </th>
                        <th colspan="4" style="width:350px">Broj fiskalnog isečka: {{$header->BIR}}</th>
                    </tr>

                    <tr>


                        <th style="width:120px">Šifra</th>
                        <th style="width:50px">Količina</th>
                        <th>Opis</th>
                        <th style="width:100px">Jedinična cena EUR</th>
                        <th style="width:50px">Pop%</th>
                        <th style="width:100px">Neto cena EUR</th>
                        <th style="width:100px">Ukupna vrednost EUR </th>

                    </tr>

                </thead>
                <tbody>


                    @foreach($positions as $position)
                    <tr>
                        <td style="width:120px;text-align:left"
                            class=" {{ $position->RBR == 0 ? 'border_bottom' : '' }}">{{$position->Sifra}}</td>
                        <td style="width:50px;padding-right:15px" align="right"
                            class=" {{ $position->RBR == 0 ? 'border_bottom' : '' }}"> @if($position->RBR != 0)
                            {{$position->Kolicina}} @endif </td>
                        <td style="padding-left:20px;text-align:left"
                            class=" {{ $position->RBR == 0 ? 'border_bottom' : '' }}">{{$position->Opis}}
                        </td>
                        <td style="width:100px;padding-right:15px" align="right">@if($position->RBR !=
                            0){{$position->Cena}}@endif
                        </td>
                        <td style="width:50px;padding-right:15px" align="right">@if($position->RBR !=
                            0){{$position->Popust}}@endif
                        </td>
                        <td style="width:100px;padding-right:15px   " align="right">@if($position->RBR !=
                            0){{$position->NetoCena}}@endif
                        </td>
                        <td style="width:100px;padding-right:15px" align="right">@if($position->RBR !=
                            0){{$position->impneto }} @endif </td>

                    </tr>
                    @endforeach



                </tbody>
            </table>
        </td>
    </tr>


    <tr>
        <td>
            <table id="pozicije_sum_table" class="parent footer"
                style="font-size:8pt;border-collapse: collapse;margin-top: -1px;margin-left:auto;margin-right:auto;margin-bottom:10pt; "
                width="90%" cellspacing="0">


                <tr>


                    <td width="30%"> </td>

                    <td colspan="3" width="70%" style="border-bottom:1px solid black;">
                        <span style="margin-right:200px">Ukupno zahvat </span> {{$positions_sum->OsnovicaZAPdv }} </pre>
                    </td>


                </tr>

                <tr style="border-bottom:1px solid black;">

                    <td width="30%"> Kategorija </td>
                    <td width="30%" style="text-align:right;padding-right:80px;"> Bruto iznos </td>
                    <td width="15%" style="text-align:right;padding-right:80px;"> Rabat </td>
                    <td width="25%" style="text-align:right;padding-right:80px;"> Neto </td>


                </tr>
                <tr>

                    {{--
                        UkupnoRadBruto,as ukupnoDeoBruto,as UkupnoOstaloBruto,  as PopustUsluge,
                        as PopustDeo,as PopustOstalo,as OsnovicaZAPdv,  as  UkupnoPDV,  as UkupnoRacun
                        str(0.00,8,2) as RadNeto,str(0.00,8,2) as DeoNeto,str(0.00,8,2)as NetoOstalo
                        --}}

                    <td> Uk. usluga </td>
                    <td style="text-align:right;padding-right:80px;">{{$positions_sum->UkupnoRadBruto }} </td>
                    <td style="text-align:right;padding-right:80px;">{{$positions_sum->PopustUsluge }} </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->RadNeto }} </td>


                </tr>
                <tr>

                    <td> Uk. artikli </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->ukupnoDeoBruto }} </td>
                    <td style="text-align:right;padding-right:80px;">{{$positions_sum->PopustDeo }} </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->DeoNeto }} </td>


                </tr>
                <tr style="border-bottom:1px solid black;">

                    <td> Ostale usluge </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->UkupnoOstaloBruto }} </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->PopustOstalo }} </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->NetoOstalo }} </td>


                </tr>
                <tr>

                    <td> Osnova za PDV </td>
                    <td> </td>
                    <td> </td>
                    <td style="text-align:right;padding-right:80px;">{{$positions_sum->OsnovicaZAPdv }} </td>


                </tr>
                <tr>

                    <td> Osnovica PDV-PDV 20% </td>
                    <td> </td>
                    <td> </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->UkupnoPDV }}</td>


                </tr>
                <tr>

                    <td> UKUPNO ZA NAPLATU EUR </td>
                    <td> </td>
                    <td> </td>
                    <td style="text-align:right;padding-right:80px;"> {{$positions_sum->UkupnoRacun }}</td>


                </tr>
            </table>
        </td>
    </tr>


</table>
