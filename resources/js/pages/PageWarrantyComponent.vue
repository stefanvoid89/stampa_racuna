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
                    <input type="text" v-model="invoice" />
                  </td>

                  <td>Vozilo</td>
                  <td>
                    <input type="text" id="wo" v-model="car" />
                  </td>
                  <td>subject</td>
                  <td>
                    <input type="text" v-model="subject" />
                  </td>

                  <!-- <td class="col2">Klijent:</td>
                  <td class="col1">
                    <div>
                      <input
                        list="subjects_list"
                        id="subjects_list_id"
                        :value="items.subject"
                        @input.stop="collect_subjects($event)"
                        @paste="collect_subjects($event)"
                        autocomplete="off"
                        contenteditable
                      />
                      <datalist id="subjects_list">
                        <option v-for="(subject,idx) in items" :key="idx">{{items.subject}}</option>
                      </datalist>
                    </div>
                  </td>-->

                  <td>Type:</td>
                  <td>
                    <input type="text" name="type" id="type" v-model="type" />
                  </td>
                </tr>

                <tr>
                  <td>Sasija:</td>
                  <td>
                    <input type="text" v-model="chasis" />
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
                >Stranica {{items.current_page}}/{{items.last_page}} : Podataka {{items.total}}</td>

                <td style="font-weight:bold; height:20px;" align="right">
                  <pagination-component
                    :total-pages="items.last_page"
                    :total="items.total"
                    :per-page="items.per_page"
                    :current-page="items.current_page"
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
                <th>ID</th>
                <th>Faktura</th>
                <th>Datum fakture</th>
                <th>Subjekt</th>
                <th>car</th>
                <th>Sasija</th>
                <th>Tip</th>

                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="items in items.data" :key="items.id">
                <td>{{ items.id }}</td>
                <td>{{ items.invoice }}</td>
                <td>{{ items.invoice_date }}</td>
                <td>{{ items.subject }}</td>
                <td>{{ items.car }}</td>
                <td>{{ items.chasis }}</td>
                <td>{{ items.type }}</td>

                <td>
                  <button class="button_primary" @click="view_warranty(items.id)">Pregled</button>
                  <button class="button_primary" @click="print_warranty(items.id)">Print</button>
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
      items: this.$props.prop_data.items,
      id: this.$props.prop_data.prop_values.id,
      invoice: this.$props.prop_data.prop_values.invoice,
      invoice_date: this.$props.prop_data.prop_values.invoice_date,
      subject: this.$props.prop_data.prop_values.subject,
      car: this.$props.prop_data.prop_values.car,
      chasis: this.$props.prop_data.prop_values.chasis,
      type: this.$props.prop_data.prop_values.type
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
    },
    build_query_string: function() {
      var query_string_object = {};

      if (this.subject.length > 0)
        query_string_object["subject"] = this.subject;

      // if (this.invoice != 0) query_string_object["invoice"] = this.invoice;

      // if (this.car != 0) query_string_object["car"] = this.car;

      // if (this.chasis != 0) query_string_object["chasis"] = this.chasis;

      // if (this.type != 0) query_string_object["type"] = this.type;

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
      if (query_string.length)
        window.location.href = `/warranty/?${query_string}`;
      else window.location.href = "/warranty";
    },

    onPageChange: function(page_id) {
      var query_string = this.build_query_string();
      if (query_string)
        window.location.href = `/warranty/?${query_string}&page=${page_id}`;
      window.location.href = `/warranty/?page=${page_id}`;
    },

    stop_backpace: function(event) {
      if (event.keyCode == 8) {
        event.preventDefault();
      }
    },
    view_warranty: function(id) {
      window.location.href = `/warranty/edit/${id}`;
    },
    print_warranty: function(id) {
      window.open(`/warranty/print/${id}`);
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
