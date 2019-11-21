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
                  <td></td>
                </tr>
              </tbody>
            </table>
          </form>
          <div style="padding: 5px; background-color: rgb(255, 242, 187); margin-bottom: 10px;">
            <button
              class="btn btn-warning border border-dark"
              @click="filter_visible = !filter_visible"
            >Pretraga</button>
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
                <th>Naziv</th>
                <th>Vrednost</th>

                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="invoice in invoices.data" :key="invoice.anRBr">
                <td>{{ invoice.AnoFactura}}</td>
                <td>{{ invoice.Factura }}</td>
                <td>{{ invoice.FechaFactura }}</td>
                <td>{{ invoice.numot }}</td>
                <td>{{ invoice.AnoOT }}</td>
                <td>{{ invoice.Recepcionista }}</td>
                <td>{{ invoice.Chasis }}</td>
                <td>{{ invoice.Matric }}</td>
                <td>{{ invoice.Cliente }}</td>
                <td>{{ invoice.ClienteNombre }}</td>
                <td>{{ invoice.ImpFactura }}</td>

                <td>
                  <button
                    class="btn btn-warning border border-dark"
                    @click="print_invoice(invoice.id)"
                  >Stampaj</button>
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
      invoices: this.$props.prop_data.invoices
    };
  },
  methods: {
    build_query_string: function() {
      var query_string_object = {};
      //  if (this.brand != 0) query_string_object["brand"] = this.brand;

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
      if (query_string.length) window.location.href = `//?${query_string}`;
      else window.location.href = "/";
    },

    onPageChange: function(page_id) {
      var query_string = this.build_query_string();
      if (query_string)
        window.location.href = `/?${query_string}&page=${page_id}`;
      window.location.href = `/?page=${page_id}`;
    },

    stop_backpace: function(event) {
      if (event.keyCode == 8) {
        event.preventDefault();
      }
    },
    print_invoice: function(id) {
      window.open(`/print/${id}`);
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
