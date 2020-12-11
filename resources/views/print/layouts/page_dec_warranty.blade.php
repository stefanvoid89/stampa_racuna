<section id="page" class="sheet padding-5mm">
    <table id="page_table" width="100%" style="height: 285mm">

        <tr>
            <td id="header_row" style="height: 40mm;">
                <table id="header"  border="0" cellspacing="0" cellpadding="0" width="100%">
                <tbody>
                    <tr>
                        <td align="left" ;width="50%">
                            <img id="logo-image" src="/images/hit-auto-og-logotip.png" ; style="height: 16mm;"/>
                        </td>
                
                        <td  style="padding-left:0mm;padding-top:0mm;padding-bottom: 0mm;font-size: 8pt;text-align: right;   ";width="100%"; ="height: 24mm;" > 
                            <span style="margin-bottom:0px;font-size:8pt;font-weight: bold;">HIT Auto D.O.O</span>

                            <br>Ovlašćeni servis i prodavac
                            <br />Šifra delatnosti : 4511, Matični broj: 17455613
                            <br />PIB: 102605020
                            <br />Staro sajmište 29, 11070 N.Beograd
                            <br />Tel: +381(0)11 20 18 011
                            <br />Fax: +381(0)11 20 18 013
                            <br />TR: 275-220013667-03, 160-255686-94
                        
                        </td>
                    </tr>
                    <tr height = 20px></tr>
                    <tr height = 20px></tr>
                    <tr height = 20px></tr>
            </tr>
          </tr>
                    <tr style="font-size: 20pt;font-weight: bold; ">
             
                        <td align="left" width="50%"; style="margin-bottom:0px;font-size: 14pt;font-weight: bold;">
                            <div style="text-align left; font-size: 14pt;font-weight: bold;background-color:powderblue" >RN odluka o rešenju prijave </div>
                        </td>
                        <td align="left" width="50%"; style="margin-bottom:0px;font-size: 14pt;font-weight: bold;background-color:powderblue ">
                        </td>
                       
                    </tr>
                      
                     <tr height = 20px></tr>
                     <tr height = 20px></tr>
                     <tr height = 20px></tr>
                    <tr>
                        <td align="left" width="50%"; style="margin-bottom:0px;font-size: 18pt;font-weight: bold; ">
                           <div style="text-align left; font-size: 14pt;font-weight: bold;">Redni broj reklamacije :{{$items->id}}  </div>
                           
                        </td>
                        <td align="left" width="50%"; style="margin-bottom:0px;font-size: 18pt;font-weight: bold; ">
                      
                            <div style="text-align left; font-size: 14pt;font-weight: bold;" >Datum prijave: {{$items->DateWarr}}</div>
                        </td>
                    </tr>
                    <tr height = 20px></tr>
        
                    </tbody>
                </table>

                <table width="100%" border="0" style="margin-top:1px" cellspacing="0" cellpadding="0">
                    <tr>

                        <td>
                      
                            <table id="customer_table" class="parent"   style="border-collapse: collapse;table-layout: fixed;width:100%" border="1">

                                <colgroup>
                                    <col style="width:50%">
                                    <col style="width:50%">
                              
                                </colgroup>
                                <tr>
                                    <div style ="text-align left; font-size: 14pt;font-weight: bold; background-color:powderblue;"> Podaci o podnosiocu reklamacija </div>
                                </tr>
                                <br/>
                                <tr   style="border-collapse: collapse;table-layout: fixed;width:100%" border="1">
                                    <td>
                                        <div style ="text-align left; font-size: 10pt ">Ime i prezime:</div>
                                        <div style="text-align:center">{{$items->subject}}</div>
                                    </td>
                                   
                                    <td>
                                        <div style="text-align:left">Adresa:</div>
                                        <div style="text-align:center"> {{$items->address}}</div>
                                    </td>
                                </tr>

                                <tr> 
                                  <td>
                                        <div style="text-align:left">Telefon:</div>
                                        <div style="text-align:center"> {{$items->phone}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Email:</div>
                                        <div style="text-align:center"> {{$items->email}}</div>
                                    </td>
                                </tr>
                               
                            </table>
                        </td>
                    </tr>

                    <tr height = 20px></tr>
                    <tr height = 20px></tr>
                    <tr>
                        <td>

                            <table id="product_table" class="parent"
                                style="font-size:8pt;border-collapse: collapse;    margin-top: -1px;table-layout: fixed;width:100%"
                                border="1" cellspacing="0">

                                <colgroup>
                                    <col style="width:50%">
                                    <col style="width:50%">
                                    
                                </colgroup>
                                <tr style="border-collapse: collapse;table-layout: fixed;width:100%" > 
                                     <td>
                                        <div style ="text-align left; font-size: 14pt;font-weight: bold; background-color:powderblue;">Podaci o robi - identifikaciji proizvoda </div>
                                
                                    </td> 
                                    <td>
                                        <div style ="text-align left; font-size: 14pt;font-weight: bold; background-color:powderblue;"> &nbsp </div>
                                       
                                    </td>
                                </tr>
                              
                                <br/>
                              

                                <tr   style="border-collapse: collapse;table-layout: fixed;width:100%" border="1">
                           
                                    <td>
                                        <div style="text-align:left">Broj racuna:  </div>
                                        <div style="text-align:center">{{$items->invoice}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Datum  računa:</div>
                                        <div style="text-align:center">{{$items->invoice_date}} </div>
                                    </td>
                                    <td>
                                 
                                </tr>


                                <tr>
                                    <td>
                                        <div style="text-align:left">Naziv:</div>
                                        <div style="text-align:center">{{$items->car}}</div>
                                    </td>
                                    <td>
                                        <div style="text-align:left">Broj šasije:</div>
                                        <div style="text-align:center">{{$items->chasis}}</div>
                                    </td>
                                   
                                </tr>

                            </table>
                        </td>


                    </tr>

                    <tr height = 20px></tr>
                    <tr height = 20px></tr>

                </table>
                
              
                <table id="type_table" class="parent"
                                style="font-size:8pt;border-collapse: collapse;    margin-top: -1px;table-layout: fixed;width:100%"
                                border="1" cellspacing="0">

                        <colgroup>
                                    <col style="width:50%">
                                    <col style="width:50%">
                                    
                                </colgroup>
                                <tr style="border-collapse: collapse;table-layout: fixed;width:100%" > 
                                <tr style="border-collapse: collapse;table-layout: fixed;width:100%" > 
                                     <td>
                                        <div style ="text-align left; font-size: 14pt;font-weight: bold; background-color:powderblue;">Tip Reklamacije </div>
                                
                                    </td> 
                                    <td>
                                        <div style ="text-align left; font-size: 14pt;font-weight: bold; background-color:powderblue;"> &nbsp </div>
                                       
                                    </td>
                                </tr>
                               <tr>
                                   <td>
                                        <div style="text-align:left">Vrsta reklamacije</div>
                                        <div style="text-align:center">{{$items->type}}</div>
                                    </td>
                                </tr>
            </table>

            </td>
        </tr>
        
        <tr height = 20px></tr>
                    <tr height = 20px></tr>
        <tr>
            <td id="content_row" style="vertical-align: top;">

            </td>
        </tr>

        <tr>
            <td id="footer_row" align="top" style="height: 10mm; font-size: 12pt;text-align:left; margin-top:20px">
                <div>
                    <table style=" font-size: 12pt;text-align:center;" width="100%">
                      
             
                        <tr>
                        <td>Reklamaciju predao </td>
                        <td>Reklamaciju primio </td>
                           
                        </tr>
                        <tr>
                          <td>____________________________________</td>
                          <td>____________________________________</td>
                        </tr>
                        

                    </table>
            </td>
        </tr>
    </table>
</section>
