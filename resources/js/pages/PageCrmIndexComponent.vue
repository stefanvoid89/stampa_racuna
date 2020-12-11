<template>
  <div style="border:1px solid #D2D2D2; background-color:#FFFFFF; padding:10px;">
    <table width="100%" cellspacing="0" cellpadding="3">
      <tr>
        <td class="naslov">{{title}}</td>
      </tr>
      <tr>
        <td>
          <hr size="1" color="#D2D2D2" />
        </td>
      </tr>
     
   
      <tr>
        <td>
          <form action :style="{display:display_filter}" @keydown.enter.prevent>
            <table id="data" width="100%" cellspacing="2">
              <tbody>
                <tr>
                  <td>Tip kupca:</td>
                  <td>
                    <input type="text" id="acTypeCustomer" v-model="acTypeCustomer" />
                  </td>

                  <td class="col2">PIB:</td>
                  <td class="col1">
                    <input id="acTaxNumber" v-model="acTaxNumber" />
                  </td>

                  <td>Maticni Broj:</td>
                  <td>
                    <input type="text" id="acIDNumber" v-model="acIDNumber" />
                  </td>

                  <td class="col2">Pol:</td>
                  <td class="col1">
                    <input id="__acGender" v-model="acGender" />
                  </td>
                </tr>

                <tr>
                

                  <td class="col2">Naziv klijenta/Prezime:</td>
                  <td class="col1">
                    <input type="text"name="acName" id="__acName" v-model="acName" />
                  </td>

                   <td class="col2">Ime klijenta:</td>
                  <td class="col1">
                    <input type="text"name="acFirstName" id="acFirstName" v-model="acFirstName" />
                  </td>

                   <td class="col2" >Izvor kontakta:</td>
                  <td class="col1">
                    <input type="text"  name="acSourceType" id="__acSourceType" v-model="acSourceType" />
                  </td>
                
                 
                  <td>Mesto:</td>
                  <td class="col1">
                    <input type="text" name="acPostName"  id="__Mesto" v-model="acPostName" />
                  </td>
                 

                 
                </tr>
 
                <tr>
                 <td>Koriscenje podataka:</td>
                  <td>
                    <input type="text" name="acProtectData" id="__acProtectData" v-model="acProtectData" />
                  </td>

                  <td>Dozvoljen mail:</td>
                  <td>
                    <input type="text" name="acProtectMail" id="__acProtectMail" v-model="acProtectMail"/>
                  </td>
                  <td>Dozvoljen telefon :</td>
                  <td>
                    <input type="text" name="acProtectNumGSM" id="acProtectNumGSM" v-model="acProtectNumGSM"/>
                  </td>
                
                  <td>Dozvoljen telefon za sms :</td>
                  <td> 
                    <input type="text" name="acProtectSms" id="acProtectSms" v-model="acProtectSms"/>
                  </td>
                
                </tr>
                 
              </tbody>
            </table>
          </form>
        
            <div style="padding: 5px; background: rgb(181, 212, 236,0.3) none repeat scroll 0% 0%;   border:solid 1px rgb(181, 212, 236,0.3) ;margin-bottom: 10px;box-shadow: 2px 2px 1px 0px #81b3df6e;">
              <div style="display:flex">
                 <div style="display:flex;justify-content:space-between">
                      <button class="button_primary" @click="filter_visible = !filter_visible" >Pretraga</button>
                      <button class="button_primary" style="justify-content:space-around; width:90px;margin-left:20px" @click.prevent="new_customer" >  Novi  </button>
                      <button class="button_primary" id="search" style="width:90px;margin-right:5px; margin-left:700px"  @click.prevent="salji_sms" >SMS</button>
                      <button class="button_primary" id="search" style="width:90px;margin-right:10px; margin-left:20px" @click.prevent="send_mail(0,0)" >EMAIL</button>
                      <button class="button_primary" id="search" style="width:90px;margin-right:10px; margin-left:20px" @click.prevent="export_excel"> EXPORT </button>
                      <button class="button_primary" id="search" style="width:90px;margin-right:0px; margin-left:20px" @click.prevent="import_excel"> IMPORT </button>
              </div>
              </div>
                              <!--
                             
              
              
              <button class="button_primary" id="search" style="width:150px;margin-right:10px" @click.prevent="export_excel" >EXPORT excel</button>
           -->
                 </div>
         
            
        </td>
    
      </tr>


      <tr>
        <td>
          <table width="100%" cellspacing="0" cellpadding="3" border="0">
            <tbody>
              <tr>
                <td
                  style="font-weight:bold; height:20px;"
                  align="left">Stranica {{customers.current_page}}/{{customers.last_page}} : Podataka {{customers.total}}</td>

                <td style="font-weight:bold; height:20px;" align="right">
                  <pagination-component :total-pages="customers.last_page"
                    :total="customers.total"
                    :per-page="customers.per_page"
                    :current-page="customers.current_page"
                    @pagechanged="onPageChange"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>

      <tr>
        <td>
          <table id="data" width="100%" cellspacing="0" cellpadding="3" border="1">
            <thead>
       
              <tr>
                <th>Tip</th>
                <th>Pib</th>
                <th>Maticni broj</th>
                <th>Naziv/Prezime</th>
                <th>Ime</th>
                <th>Mesto</th>
                <th>Adresa</th>
                <th>NV</th>
                <th>Zast svi</th>
                <th>Mail</th>
                <th>Telefon</th>
                <th>Sms</th>
                <th>Izvor </th>
                <th></th>
              </tr>
            </thead>

            <tbody>
              
              <tr v-for="customer in customers.data" :key="customer.anID">
                <td>{{ customer.acTypeCustomer }}</td>
                <td>{{ customer.acTaxNumber }}</td>
                <td>{{ customer.acIDNumber }}</td>
                <td>{{ customer.acName }}</td>
                <td>{{ customer.acFirstName }}</td>
                <td>{{ customer.acPostName }}</td>
                <td>{{ customer.acAddress }}</td>
                <td>{{ customer.acIsNewCars }}</td>
                <td>{{ customer.acProtectData }}</td>
                <td>{{ customer.acProtectMail }}</td>
                <td>{{ customer.acProtectNumGSM }}</td>
                <td>{{ customer.acProtectSms }}</td>
                <td>{{ customer.acSourceType }}</td>

                <td>
                  <button class="button_primary" @click="edit_customers(customer.id)" >Detalji</button>
                   <button class="button_primary" @click="select_customers(customer.id)" >Izbaci</button>
                   <button class="button_primary" @click="select_customers(customer.id)" >Brisi</button>
                  <button  class="button_primary" @click.prevent="send_mail(customer.Id,customer.anId)" >mail </button>
                  <button  class="button_primary" @click.prevent="send_sms(customer.Id)" >sms </button>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </table>
  </div>
</template>
<script>
 export default {
  props: { prop_data: Object, title: String },
  data: function() {
    return {
      filter_visible: false,
      customers: this.$props.prop_data.customers,
      view_send_mail_buttons: false,
      mail: "persida.pandurovic@hitauto.rs"
    };
  },
  methods: {
   send_mail: function(customer_id, _customer) {
      const url = "/api/send_mail";
      const method = "POST";
      const baseUrl = window.axios.defaults.baseURL;
      var data = {
        url: `${baseUrl}/print/print/${customer_id}`,
        file: `${_customer}.pdf`,
        mail: this.mail
      };

      axios({
        method,
        url,
        data
      })
        .then(response => {
          console.log(response);
        })
        .catch(err => {
          console.log("ovo je greska", err.response);
        });
    },

    new_customer: function() {
        return {}
      },
       salji_sms: function(customers_id, customers) {
         return {}
      },
      export_excel: function() {
            var query_string = this.build_query_string();
            if (this.invoice) window.location.href = `/invoice/export?${query_string}`;
            else window.location.href = `/proforma/export?${query_string}`;
        },
  
    import_excel: function() {
            var query_string = this.build_query_string();
            if (this.invoice) window.location.href = `/invoice/export?${query_string}`;
            else window.location.href = `/proforma/export?${query_string}`;
        },
   
    refresh_page_with_filter: function() {
      var query_string = this.build_query_string();
      if (query_string.length) window.location.href = `print/?${query_string}`;
      else window.location.href = "print/";
    },

    onPageChange: function(page_id) {
      var query_string = this.build_query_string();
      if (query_string)
        window.location.href = `print/?${query_string}&page=${page_id}`;
      window.location.href = `print/?page=${page_id}`;
    },

    stop_backpace: function(event) {
      if (event.keyCode == 8) {
        event.preventDefault();
      }
    },
    
    edit_customers: function(id) {
      window.open(`/crm/edit/${id}`);
    },
    select_customers: function(id) {
      console.log(id);
    }
  },
  computed: {
    display_filter: function() {
      return this.filter_visible ? "block" : "none";
    }
  }
};
</script>




<style>
</style>
