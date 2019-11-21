<table id="main_table" ref="main_table" width="100%">


    <tr>
        <td>
            <table id="pozicije_table" class="parent"
                style="font-size:8pt;border-collapse: collapse; margin-top: -1px;table-layout: fixed; text-align:left;width:100%">


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
                        <th colspan="5">Datum prometa dobara i usluga: {{$header->Datumprometa}}
                        </th>
                        <th colspan="2" style="width:200px">Broj fiskalnog isečka: {{$header->BIR}}</th>
                    </tr>

                    <tr>


                        <th style="width:120px">Šifra</th>
                        <th style="width:50px">Količina</th>
                        <th>Opis</th>
                        <th style="width:100px">Jedinićna cena</th>
                        <th style="width:50px">Pop%</th>
                        <th style="width:100px">Ukupna vrednost </th>
                        <th style="width:100px">Poreska osn. </th>

                    </tr>

                </thead>
                <tbody>


                    @foreach($positions as $position)
                    <tr>
                        <td style="width:120px">{{$position->Sifra}}</td>
                        <td style="width:50px" align="right">{{$position->Kolicina}}</td>
                        <td style="padding-left:20px;">{{$position->Opis}}
                        </td>
                        <td style="width:100px" align="right">{{$position->Cena}}
                        </td>
                        <td style="width:50px" align="right">{{$position->Popust}}
                        </td>
                        <td style="width:100px" align="right">{{$position->impneto}}
                        </td>
                        <td style="width:100px;padding-left:10px" align="center">/</td>

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
                        <span style="margin-right:200px">Ukupno zahvat </span> 150000</pre>
                    </td>


                </tr>

                <tr style="border-bottom:1px solid black;">

                    <td width="30%"> Kategorija </td>
                    <td width="30%" style="text-align:center"> Bruto iznos </td>
                    <td width="15%" style="text-align:center"> Rabat </td>
                    <td width="25%" style="text-align:center"> Neto </td>


                </tr>
                <tr>

                    <td> Uk. usluga </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>


                </tr>
                <tr>

                    <td> Uk. artikli </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>


                </tr>
                <tr style="border-bottom:1px solid black;">

                    <td> Ostale usluge </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>


                </tr>
                <tr>

                    <td> Osnova za PDV </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>


                </tr>
                <tr>

                    <td> Osnovica PDV-PDV 20% </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>


                </tr>
                <tr>

                    <td> UKUPNO ZA NAPLATU </td>
                    <td> </td>
                    <td> </td>
                    <td> </td>


                </tr>
            </table>
        </td>
    </tr>


</table>
