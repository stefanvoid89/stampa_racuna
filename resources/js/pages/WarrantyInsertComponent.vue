<template>
  <div style="border:1px solid #D2D2D2; background-color:#FFFFFF; padding:10px;">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
      <tbody>
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
            <table style="width: 100%;" v-if="errors.length > 0">
              <tr>
                <td>
                  <div class="alert alert-danger" style="min-width: 100%;">
                    <div class="multi_columns">
                      <div
                        style="min-width:300px;margin-top:2px;margin-bottom:2px;"
                        v-for="_error in errors"
                        :key="_error"
                      >{{_error}}</div>
                    </div>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td>
            <table style="width:100%">
              <tbody>
                <tr>
                  <td colspan="3">
                    <table class="insert">
                      <thead>
                        <tr>
                          <th colspan="4">Podaci o podnosiocu reklamacije</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="label">Ime i prezime</td>
                          <td>
                            <input class="width" type="text" name="subject" v-model="subject" />
                          </td>
                          <td class="label">Adresa</td>
                          <td>
                            <input
                              class="width"
                              type="text"
                              name="address"
                              v-model="address"
                              id="address"
                            />
                          </td>
                        </tr>

                        <tr>
                          <td class="label">Telefon</td>
                          <td>
                            <input class="width" type="text" v-model="phone" />
                          </td>
                          <td class="label">E-mail</td>
                          <td>
                            <input class="width" type="text" v-model="email" />
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <br />
                    <table class="insert">
                      <thead>
                        <tr>
                          <th colspan="3">Podaci o robi - identifikacija proizvoda</th>
                          <th style="background:white;text-align: center;">
                            <button
                              class="button_primary"
                              @click="fetch_data"
                              style="padding-top:0px; padding-bottom:0px"
                            >POKUPI PODATKE SA ICAR-A</button>
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="label">Broj racuna</td>
                          <td>
                            <input class="width" type="text" name="subject" v-model="invoice" />
                          </td>
                          <td class="label">Datum racuna</td>
                          <td>
                            <input class="width" type="date" v-model="invoice_date" />
                          </td>
                        </tr>

                        <tr>
                          <td class="label">Naziv</td>
                          <td>
                            <input class="width" type="text" v-model="car" />
                          </td>
                          <td class="label">Broj sasije</td>
                          <td>
                            <input class="width" type="text" v-model="chasis" />
                          </td>
                        </tr>
                      </tbody>
                    </table>

                    <br />
                    <table class="insert">
                      <thead>
                        <tr>
                          <th colspan="4">Opis reklamacije</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td colspan="4" class="label">
                            <textarea v-model="comment" rows="5" style=";width: 100%;"></textarea>
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Tip reklamacije</td>
                          <td>
                            <select v-model="type_id">
                              <option
                                v-for="_type in warranty_types"
                                :key="_type.id"
                                :value="_type.id"
                              >{{_type.type}}</option>
                            </select>
                          </td>

                          <td class="label">Datum</td>
                          <td>
                            <input class="width" type="date" v-model="date" />
                          </td>
                        </tr>
                        <tr>
                          <td class="label">Broj</td>
                          <td>
                            <input class="width" type="text" readonly v-model="id" />
                          </td>

                          <td class="label">Korisnik</td>
                          <td>
                            <input class="width" type="text" v-model="clerk" />
                          </td>
                        </tr>
                      </tbody>
                    </table>

                     <br />
                    <table  style="width:100%" class="obradjen">
                      <thead>
                                  
                        <tr>
                          <th colspan="6" >Opis potvrde</th>  </tr>
                       
                
                      </thead>
                     <tbody>  
                        <tr>
                          <td colspan="6"  class="label">
                            <textarea v-model="comment_approved"  rows="5" style=";width: 100%;"></textarea>
                          </td>
                        </tr>
   
                      </tbody>
    
                        <tr>
                          <td class="label">Status:</td>
                          <td>
                            <input class="width" type="text" v-model="approved_text" />
                          </td>
                            <td class="label">Reklamacija obradjena:</td>
                            <input type="checkbox" id="checkbox" v-model="approved"  true-value="1" false-value="0">
                             <label for="checkbox">{{ approved }}</label>

                          <td class="label">Datum potvrde</td>
                          <td>
                            <input class="width" type="date" v-model="date_approved" />
         
                          </td>
                         
                        </tr>
  
                    </table>
                  </td>
                </tr>

                <tr>
                  <td colspan="3">&nbsp;</td>
                </tr>
              </tbody>
            </table>

            <table style="width:100%">
              <tr>
                <td style="width:40%"></td>

                <td style="width:20%">
                  <div style="width:100%;display:flex;justify-content:space-around">
                    <button class="button_primary" @click.prevent="go_back ">Nazad</button>
                    <button class="button_primary" @click.prevent="store_data">Sacuvaj</button>
                    <button class="button_primary" @click.prevent="print_rec">Print Zapisnik</button>
                    <button class="button_primary" @click.prevent="print_dec">Print odluka</button>
                  </div>
                </td>

                <td style="width:40%"></td>
              </tr>
            </table>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>


<script>
export default {
  props: { prop_data: Object, title: String, _id: Number },
  data: function() {
    return {
      errors: [],
      id: this.$props._id,
      warranty_types: this.$props.prop_data.warranty_types,
      subject: this.$props.prop_data.prop_values.subject,
      phone: this.$props.prop_data.prop_values.phone,
      address: this.$props.prop_data.prop_values.address,
      email: this.$props.prop_data.prop_values.email,
      invoice: this.$props.prop_data.prop_values.invoice,
      invoice_date: this.$props.prop_data.prop_values.invoice_date,
      car: this.$props.prop_data.prop_values.car,
      chasis: this.$props.prop_data.prop_values.chasis,
      comment: this.$props.prop_data.prop_values.comment,
      type_id: this.$props.prop_data.prop_values.type_id,
      date: this.$props.prop_data.prop_values.date,
      clerk: this.$props.prop_data.prop_values.clerk,
      comment_approved: this.$props.prop_data.prop_values.comment_approved,
      approved: this.$props.prop_data.prop_values.approved,
      date_approved: this.$props.prop_data.prop_values.date_approved,
      approved_text: this.$props.prop_data.prop_values.approved_text
    };
  },

  watch: {},
  methods: {
    store_data: function() {
      let method = "POST";
      let url = "/api/warranty";
      let data = {
        warranty_types: this.warranty_types,
        subject: this.subject,
        phone: this.phone,
        address: this.address,
        email: this.email,
        invoice: this.invoice,
        invoice_date: this.invoice_date,
        car: this.car,
        chasis: this.chasis,
        comment: this.comment,
        type_id: this.type_id,
        date: this.date,
        clerk: this.clerk,
        approved: this.approved,
        date_approved: this.date_approved,
        approved_text: this.approved_text,
        comment_approved: this.comment_approved
      };
      if (this.$data.id !== 0) {
        method = "PUT";
        url = `/api/warranty/${this.$data.id}`;
      }

      axios({
        method,
        url,
        data
      })
        .then(response => {
          if (response.data.errors) {
            if (response.data.errors.length > 0) {
              this.errors = response.data.errors;
            } else {
             window.location.href = "/warranty";
            }
          }
        })
        .catch(err => {
          console.log(err);
        });
    },

    fetch_data: function() {
      let method = "POST";
      let url = "/api/fetch_icar_warranty_data";
      let data = {
        invoice: this.invoice,
        invoice_date: this.invoice_date
      };

      axios({
        method,
        url,
        data
      })
        .then(response => {
          if (response.data.errors.length > 0) {
            this.errors = response.data.errors;
          } else {
            this.subject = response.data.invoice.subject;

            this.phone = response.data.invoice.phone;
            this.address = response.data.invoice.address;
            this.car = response.data.invoice.car;
            this.chasis = response.data.invoice.chasis;
            this.email = response.data.invoice.email;
            this.invoice_date = response.data.invoice.invoice_date;
            this.type_id = response.data.invoice.type_id;
            this.clerk = response.data.invoice.clerk;
            this.approved = response.data.invoice.approved;
            this.approved_text = response.data.invoice.approved_text;
            this.date_approved = response.data.invoice.date_approved;
            this.comment_approved = response.data.invoice.comment_approved;
            this.errors = response.data.errors;
          }
        })
        .catch(err => {
          console.log(err);
        });
    },
    go_back: function() {
      window.location.href = "/warranty";
    }
  },
  
    print_rec: function(id) {
      window.open(`/warranty/print_rec/${id}`);
    },
  
   print_dec:  function(id) {
      window.open(`/warranty/print_dec/${id}`);
    },

  computed: {},
  created: function() {}
};
</script>




<style scoped>
.width {
  width: 90%;
}
</style>
