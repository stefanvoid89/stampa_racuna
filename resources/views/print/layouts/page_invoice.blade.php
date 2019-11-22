<section id="page" class="sheet padding-5mm">
    <table id="page_table" width="100%" style="height: 285mm">
        <tr>
            <td id="header_row" style="height: 40mm">
                <table id="header" width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="left" width="30%">
                            @if($marka == 'nissan' )
                            <img id="logo-image" src="/images/nissan-logo.jpg" style="height: 35mm;" />
                            @endif

                            @if($marka == 'renault' )
                            <img id="logo-image" src="/images/renault-logo.jpg" style="height: 35mm;" />
                            @endif

                            @if($marka == 'dacia' )
                            <img id="logo-image" src="/images/dacia-logo.png" style="height: 35mm;" />
                            @endif

                            @if($marka == 'motrio' )
                            <img id="logo-image" src="/images/motrio-logo.jpg" style="height: 35mm;" />
                            @endif

                        </td>
                        <td
                            style="padding-left:4mm;padding-top: 2mm;padding-bottom: 2mm;font-size: 8pt;text-align: right;   ">
                            <span style="margin-bottom:0px;font-size: 17pt;font-weight: bold;">HIT Auto D.O.O</span>

                            @if($location == "sajmiste")
                            <br>Ovlašćeni servis i prodavac
                            <br />Šifra delatnosti : 4511, Matični broj: 17455613
                            <br />PIB: 102605020
                            <br />Staro sajmište 29, 11070 N.Beograd
                            <br />Tel: +381(0)11 20 18 011
                            <br />Fax: +381(0)11 20 18 013
                            <br />TR: 275-220013667-03, 160-255686-94
                            @endif



                            @if($location == "vidikovac")
                            <br>Ovlašćeni Motrio servis
                            <br />Šifra delatnosti : 4511, Matični broj: 17455613
                            <br />PIB: 102605020
                            <br />Prvoboraca 41,Vidikovac 11000 Beograd
                            <br />Tel: +381(0)11 63 41 333
                            <br />Fax: +381(0)11 63 41 323
                            <br />TR: 275-220013667-03, 160-255686-94
                            @endif


                        </td>
                    </tr>


                </table>

                <table width="100%" border="0" cellspacing="0" cellpadding="0">


                    <tr>

                        <td>
                            <table id="prijem_table" class="parent"
                                style="border-collapse: collapse;table-layout: fixed;width:100%" border="1">

                                <colgroup>
                                    <col style="width:10%">
                                    <col style="width:14%">
                                    <col style="width:14%">
                                    <col style="width:17%">
                                    <col style="width:45%">
                                </colgroup>


                                <tr>
                                    <td>
                                        <div style="text-align:left">Broj naloga:</div>
                                        <div style="text-align:center">{{$header->BrojNaloga}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Datum prijema:</div>
                                        <div style="text-align:center"> {{$header->DatumPrijema}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Nalogodavac:</div>
                                        <div style="text-align:center"> {{$header->NalogoDavac}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Predviđeno preuzimanje:</div>
                                        <div style="text-align:center"> {{$header->PredvidjeniDatumIzdavanja}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Poslužio vas je:</div>
                                        <div style="text-align:center"> {{$header->RecepcionisatName}}</div>
                                    </td>

                                </tr>

                            </table>
                        </td>
                    </tr>


                    <tr>
                        <td>

                            <table id="kupac_table" class="parent"
                                style="font-size:8pt;border-collapse: collapse;    margin-top: -1px;table-layout: fixed;width:100%"
                                border="1" cellspacing="0">

                                <colgroup>
                                    <col style="width:55%">
                                    <col style="width:14%">
                                    <col style="width:15%">
                                    <col style="width:8%">
                                    <col style="width:8%">
                                </colgroup>

                                <tr>

                                    <td rowspan="3" width="55%" style="vertical-align: top;border-bottom:none">

                                        <table id="klijent_table"
                                            style="font-size:8pt;border-collapse: collapse; width=:100% ">
                                            <tr>
                                                <td> {{$header->ImeKupcaSif}}</td>
                                            </tr>
                                            <tr>
                                                <td> {{$header->AdresaSif}} &nbsp; {{$header->PostaSif}} &nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>{{$header->MestoSif}}</td>
                                            </tr>
                                            <tr>
                                                <td> PIB: {{$header->PibSif}}</td>
                                            </tr>

                                            <tr>
                                                <td> PIB: Matični broj: {{$header->MaticniSif}}</td>
                                            </tr>
                                        </table>


                                    </td>

                                    <td colspan="2" style="font-size:16pt;">RAČUN</td>
                                    <td colspan="3">{{$header->BrojRacuna}}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="text-align:left">Broj dokumenta:</div>
                                        <div style="text-align:center">{{$header->BrojRacuna}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Datum i mesto računa:</div>
                                        <div style="text-align:center">{{$header->DatumRacuna}} Beograd</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Uslov plaćanja:</div>
                                        <div style="text-align:center">{{$header->NacinPlacanjaNaziv}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Stranica:</div>
                                        <div style="text-align:center">#strana#</div>
                                    </td>
                                </tr>


                                <tr>
                                    <td>
                                        <div style="text-align:left">Šifra kupca:</div>
                                        <div style="text-align:center">{{$header->KupacNaFakturi}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Broj telefona:</div>
                                        <div style="text-align:center">{{$header->Telefon}}</div>
                                    </td>
                                    <td colspan="2">
                                        <div style="text-align:left">Način plaćanja:</div>
                                        <div style="text-align:center">{{$header->NacinPlacanjaNaziv}}</div>
                                    </td>

                                </tr>

                                <tr>
                                    <td style="vertical-align: bottom;border-top: none;">
                                        Broj odobrenja: {{$header->brojOdobrenja}}
                                    </td>
                                    <td>
                                        <div style="text-align:left">Datum 1. registracije:</div>
                                        <div style="text-align:center">{{$header->datumPrveReg}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Stanje KM:</div>
                                        <div style="text-align:center">{{$header->Kilom}}</div>
                                    </td>
                                    <td colspan="2">
                                        <div style="text-align:left">Prijemni broj:</div>
                                        <div style="text-align:center">{{$header->BrojPrijema}}</div>
                                    </td>

                                </tr>
                            </table>
                        </td>


                    </tr>

                    <tr>
                        <td>
                            <table id="vozilo_table" class="parent"
                                style="font-size:8pt;border-collapse: collapse;    margin-top: -1px;" width="100%"
                                border="1" cellspacing="0">


                                <tr>

                                    <td width="30%">
                                        <div style="text-align:left">Marka i model:</div>
                                        <div style="text-align:center">{{$header->MarkaModel}}</div>
                                    </td>
                                    <td width="20%">
                                        <div style="text-align:left">Registarska oznaka:</div>
                                        <div style="text-align:center">{{$header->RegBroj}}</div>
                                    </td>
                                    <td width="30%">
                                        <div style="text-align:left">Šasija broj:</div>
                                        <div style="text-align:center">{{$header->Sasija}}</div>
                                    </td>
                                    <td width="20%">
                                        <div style="text-align:left">Proizvodni broj:</div>
                                        <div style="text-align:center">{{$header->ProizvodniBroj}}</div>
                                    </td>
                                </tr>

                            </table>
                        </td>
                    </tr>

                </table>


            </td>
        </tr>
        <tr>
            <td id="content_row" style="vertical-align: top;">

            </td>
        </tr>

        <tr>
            <td id="footer_row" align="center" style="height: 10mm; font-size: 8pt;text-align:left; margin-top:20px">
                <div>
                    <table style=" font-size: 8pt;text-align:left;" width="100%">
                        <tr>
                            <td>Račun izdao: </td>
                            <td>Odgovorno lice:</td>
                            <td>Račun primio:</td>
                        </tr>
                        <tr>
                            <td>____________________________________</td>
                            <td>____________________________________</td>
                            <td>____________________________________</td>
                        </tr>

                        <tr>
                            <td colspan="3"> <br> </td>
                        </tr>
                        <tr>
                            <td colspan="3"> Garantni rok za sve originalne delove i rad iznosi 12 meseci od datuma
                                izdavanja računa. </td>
                        </tr>
                        <tr>
                            <td colspan="3">Vlasnik vozila preuzima obavezu plaćanja računa za popravak vozila po
                                zapisniku osiguranja
                                ukoliko
                                osiguravajuće društvo iz bilo kog razloga ospori </td>
                        </tr>
                        <tr>
                            <td colspan="3">plaćanje štete odnosno izdavanje garantnog pisma. </td>
                        </tr>
                        <tr>
                            <td style="text-align:center" colspan="3"><b>T.R:273-220013667-03;
                                    250-1190000011030-05;330-4013477-74</b> </td>
                        </tr>
                        <tr>
                            <td style="text-align:center" colspan="3"><b>www.hitauto.rs</b> </td>
                        </tr>

                    </table>
            </td>
        </tr>
    </table>
</section>
