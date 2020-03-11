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
                  <td>Faktura:</td>
                  <td>
                    <input type="text" id="invoice" v-model="Factura" />
                  </td>

                  <td class="col2">Godina fakture:</td>
                  <td class="col1">
                    <input id="year_invoice" v-model="AnoFactura" />
                  </td>

                  <td>Radni nalog:</td>
                  <td>
                    <input type="text" id="wo" v-model="numot" />
                  </td>

                  <td class="col2">Godina radnog naloga:</td>
                  <td class="col1">
                    <input id="year_wo" v-model="AnoOT" />
                  </td>
                </tr>

                <tr>
                  <td>Period fakture od:</td>
                  <td>
                    <input
                      type="date"
                      name="date_from"
                      min="2000-01-01"
                      max="2100-12-31"
                      @keydown.stop="stop_backpace"
                      :value="FechaFacturaFrom"
                      @input="FechaFacturaFrom = $event.target.value"
                    />
                  </td>
                  <td>Period fakture do:</td>
                  <td>
                    <input
                      type="date"
                      name="date_to"
                      min="2000-01-01"
                      max="2100-12-31"
                      @keydown.stop="stop_backpace"
                      :value="FechaFacturaTo"
                      @input="FechaFacturaTo = $event.target.value"
                    />
                  </td>

                  <td class="col2">Klijent:</td>
                  <td class="col1">
                    <div>
                      <input
                        list="subjects_list"
                        id="subjects_list_id"
                        :value="Cliente.acSubject"
                        @input.stop="collect_subjects($event)"
                        @paste="collect_subjects($event)"
                        autocomplete="off"
                        contenteditable
                      />
                      <datalist id="subjects_list">
                        <option
                          v-for="_Cliente in Clientes"
                          :key="_Cliente.anId"
                        >{{_Cliente.acSubject}}</option>
                      </datalist>
                    </div>
                  </td>

                  <td>Prijemni savetnik:</td>
                  <td>
                    <input type="text" name="user" id="user" v-model="Recepcionista" />
                  </td>
                </tr>

                <tr>
                  <td>Sasija:</td>
                  <td>
                    <input type="text" v-model="Chasis" />
                  </td>
                  <td>Registracija:</td>
                  <td>
                    <input type="text" v-model="Matric" />
                  </td>
                  <td>Lokacija:</td>
                  <td>
                    <select id="lokacija" v-model="taller">
                      <option
                        v-for="_taller in tallers"
                        :key="_taller.anId"
                        :value="_taller.anId"
                      >{{_taller.acSubject}}</option>
                    </select>
                  </td>
                  <td colspan="2" align="right">
                    <button
                      class="button_primary"
                      id="search"
                      @click.prevent="refresh_page_with_filter"
                    >OK</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
          <div
            style="padding: 5px; background: rgb(181, 212, 236,0.3) none repeat scroll 0% 0%; border:solid 1px rgb(181, 212, 236,0.3) ;margin-bottom: 10px;box-shadow: 2px 2px 1px 0px #81b3df6e;"
          >
            <button class="button_primary" @click="filter_visible = !filter_visible">Pretraga</button>
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
                  align="left"
                >Stranica {{invoices.current_page}}/{{invoices.last_page}} : Podataka {{invoices.total}}</td>

                <td style="font-weight:bold; height:20px;" align="right">
                  <pagination-component
                    :total-pages="invoices.last_page"
                    :total="invoices.total"
                    :per-page="invoices.per_page"
                    :current-page="invoices.current_page"
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
                <th>Godina</th>
                <th>Faktura</th>
                <th>Datum fakture</th>
                <th>Radni nalog</th>
                <th>Godina RN</th>
                <th>Prijemni savetnik</th>
                <th>Sasija</th>
                <th>Registarski broj</th>
                <th>Klijent</th>

                <th>Vrednost</th>

                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="invoice in invoices.data" :key="invoice.anRBr">
                <td>{{ invoice.AnoFactura}}</td>
                <td>{{ invoice.Factura }}</td>
                <td>{{ invoice._FechaFactura }}</td>
                <td>{{ invoice.numot }}</td>
                <td>{{ invoice.AnoOT }}</td>
                <td>{{ invoice.Recepcionista }}</td>
                <td>{{ invoice.Chasis }}</td>
                <td>{{ invoice.Matric }}</td>
                <td>{{ invoice.Cliente }}</td>

                <td>{{ invoice.ImpFactura }}</td>

                <td>
                  <button class="button_primary" @click="print_invoice(invoice.id)">Stampaj</button>
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
      invoices: this.$props.prop_data.invoices,
      Clientes: this.$props.prop_data.Clientes,
      tallers: this.$props.prop_data.tallers,
      AnoFactura: this.$props.prop_data.prop_values.AnnoFactura || "",

      Factura: this.$props.prop_data.prop_values.Facutra || "",
      numot: this.$props.prop_data.prop_values.numot || "",

      AnoOT: this.$props.prop_data.prop_values.AnoOT || "",
      Recepcionista: this.$props.prop_data.prop_values.Recepcionista || "",
      Chasis: this.$props.prop_data.prop_values.Chasis || "",

      Matric: this.$props.prop_data.prop_values.Matric || "",
      Cliente: this.$props.prop_data.prop_values.Clientes || "",
      taller: this.$props.prop_data.prop_values.taller || "",

      ID: this.$props.prop_data.prop_values.id || "",
      subject: this.$props.prop_data.prop_values.subject || {},
      FechaFacturaFrom: this.$props.prop_data.prop_values.invoce_date || "",
      FechaFacturaTo: this.$props.prop_data.prop_values.invoce_date || "",
      car: this.$props.prop_data.prop_values.car,
      Chasis: this.$props.prop_data.prop_values.Chasis || "",
      type: this.$props.prop_data.prop_values.type || ""
    };
  },
  methods: {
    collect_subjects: function(event) {
      var type = event.type;
      var search =
        type == "paste"
          ? event.clipboardData.getData("text/plain")
          : event.target.value;
      if (search.length == 3 || (search.length >= 3 && type == "paste")) {
        axios
          .get("api/collect_subjects", {
            params: {
              search_term: search.toUpperCase().trim()
            }
          })
          .then(response => {
            this.Clientes = response.data;
            if (type == "paste") this.set_subject(search);
          })
          .catch(err => {
            console.log("ovo je greska", err.response);
          });
      }
      this.set_subject(search);
    },

    set_subject: function(sub) {
      var subject = this.Clientes.find(subject => subject.acSubject == sub);

      this.$data.Cliente = subject ? subject : { anId: null, acSubject: sub };
    },

    build_query_string: function() {
      var query_string_object = {};

      if (this.Cliente.anId) query_string_object["Cliente"] = this.Cliente.anId;

      if (this.AnoFactura.length > 0)
        query_string_object["AnoFactura"] = this.AnoFactura;

      if (this.Factura.length > 0)
        query_string_object["Factura"] = this.Factura;

      if (this.Factura.length > 0)
        query_string_object["Factura"] = this.Factura;

      if (this.FechaFacturaFrom.length > 0)
        query_string_object["FechaFacturaFrom"] = this.FechaFacturaFrom;

      if (this.FechaFacturaTo.length > 0)
        query_string_object["FechaFacturaTo"] = this.FechaFacturaTo;

      if (this.numot.length > 0) query_string_object["numot"] = this.numot;

      if (this.AnoOT.length > 0) query_string_object["AnoOT"] = this.AnoOT;

      if (this.Recepcionista.length > 0)
        query_string_object["Recepcionista"] = this.Recepcionista;

      if (this.Chasis.length > 0) query_string_object["Chasis"] = this.Chasis;

      if (this.Matric.length > 0) query_string_object["Matric"] = this.Matric;

      if (this.taller != 0) query_string_object["taller"] = this.taller;

      var query_string = Object.keys(query_string_object)
        .map(key => {
          return (
            encodeURIComponent(key) +
            "=" +
            encodeURIComponent(query_string_object[key])
          );
        })
        .join("&");
      return query_string;
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
    print_invoice: function(id) {
      window.open(`/print/print/${id}`);
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
