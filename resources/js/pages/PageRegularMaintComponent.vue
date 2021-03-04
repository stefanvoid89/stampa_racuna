<template>
    <div
        style="border:1px solid #D2D2D2; background-color:#FFFFFF; padding:10px;"
    >
        <table width="100%" cellspacing="0" cellpadding="3">
            <tr>
                <td class="naslov">{{ title }}</td>
            </tr>

            <tr>
                <td>
                    <hr size="1" color="#D2D2D2" />
                </td>
            </tr>
            <tr>
                <td>
                    <form
                        action
                        :style="{ display: display_filter }"
                        @keydown.enter.prevent
                    >
                        <table id="data" width="100%" cellspacing="2">
                            <tbody>
                                <tr>
                                    <td>Klijent:</td>
                                    <td>
                                        <input
                                            type="text"
                                            id="client"
                                            v-model="client"
                                        />
                                    </td>

                                    <td>Period od:</td>
                                    <td>
                                        <input
                                            type="date"
                                            name="date_from"
                                            min="2000-01-01"
                                            max="2100-12-31"
                                            @keydown.stop="stop_backpace"
                                            :value="date_from"
                                            @input="
                                                date_from = $event.target.value
                                            "
                                        />
                                    </td>
                                    <td>Period do:</td>
                                    <td>
                                        <input
                                            type="date"
                                            name="date_to"
                                            min="2000-01-01"
                                            max="2100-12-31"
                                            @keydown.stop="stop_backpace"
                                            :value="date_to"
                                            @input="
                                                date_to = $event.target.value
                                            "
                                        />
                                    </td>
                                </tr>

                                <tr>
                                    <td>Sasija:</td>
                                    <td>
                                        <input type="text" v-model="chasis" />
                                    </td>
                                    <td colspan="4"></td>
                                </tr>

                                <tr>
                                    <td colspan="4"></td>
                                    <td colspan="2" align="right">
                                        <button
                                            class="button_primary"
                                            id="search"
                                            style="width:150px;margin-right:10px;margin-bottom:10px"
                                            @click.prevent="
                                                refresh_page_with_filter
                                            "
                                        >
                                            OK
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                    <div
                        style="padding: 5px; background: rgb(181, 212, 236,0.3) none repeat scroll 0% 0%; border:solid 1px rgb(181, 212, 236,0.3) ;margin-bottom: 10px;box-shadow: 2px 2px 1px 0px #81b3df6e;"
                    >
                        <div style="display:flex;justify-content:space-between">
                            <div style="display:flex">
                                <button
                                    class="button_primary"
                                    style="width:150px;margin-right:10px"
                                    @click="filter_visible = !filter_visible"
                                >
                                    Pretraga
                                </button>
                            </div>
                            <div style="display:flex">
                                <button
                                    class="button_primary"
                                    id="search"
                                    style="width:150px;margin-right:10px"
                                    @click.prevent="export_excel('regular')"
                                >
                                    EXPORT
                                </button>
                                <button
                                    class="button_primary"
                                    id="search"
                                    style="width:150px;margin-right:10px"
                                    @click.prevent="export_excel('detail')"
                                >
                                    EXPORT DETALJNO
                                </button>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <table
                        width="100%"
                        cellspacing="0"
                        cellpadding="3"
                        border="0"
                    >
                        <tbody>
                            <tr>
                                <td
                                    style="font-weight:bold; height:20px;"
                                    align="left"
                                >
                                    Stranica {{ wos.current_page }}/{{
                                        wos.last_page
                                    }}
                                    : Podataka {{ wos.total }}
                                </td>

                                <td
                                    style="font-weight:bold; height:20px;"
                                    align="right"
                                >
                                    <pagination-component
                                        :total-pages="wos.last_page"
                                        :total="wos.total"
                                        :per-page="wos.per_page"
                                        :current-page="wos.current_page"
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
                    <table
                        id="data"
                        width="100%"
                        cellspacing="0"
                        cellpadding="3"
                        border="1"
                    >
                        <thead>
                            <tr>
                                <th>Klijent</th>
                                <th>Reg. broj</th>
                                <th>Sasija</th>
                                <th>Datum</th>
                                <th>Trajanje</th>
                                <th>RDO ugovor</th>
                                <th>RDO iskorisceno</th>
                                <th>RDO ostatak</th>
                                <th>GTS</th>
                                <th>Total ostalo</th>
                                <th>Total garancije</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="_wo in wos.data" :key="_wo.anid">
                                <td>{{ _wo.client }}</td>
                                <td>{{ _wo.regno }}</td>
                                <td>{{ _wo.chasis }}</td>
                                <td>{{ _wo.date }}</td>
                                <td>{{ _wo.duration }}</td>
                                <td>{{ _wo.RDO_Contract }}</td>
                                <td>{{ _wo.Total_RDO }}</td>
                                <td>{{ _wo.RDO_rest }}</td>
                                <td>{{ _wo.New_GTS }}</td>
                                <td>{{ _wo.Total_ostalo }}</td>
                                <td>{{ _wo.Total_Garancije }}</td>
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
            wos: this.$props.prop_data.wos,
            date_from: this.$props.prop_data.prop_values.date_from || "",
            date_to: this.$props.prop_data.prop_values.date_to || "",
            client: this.$props.prop_data.prop_values.client || "",
            chasis: this.$props.prop_data.prop_values.chasis || ""
        };
    },
    methods: {
        hit_enter: function(e) {
            if (e.keyCode === 13) {
                if (this.filter_visible) {
                    console.log("enter");
                    this.refresh_page_with_filter();
                }
            }
            if (e.keyCode === 27) {
                if (this.filter_visible) {
                    this.filter_visible = false;
                } else {
                    this.filter_visible = true;
                }
            }
        },
        export_excel: function(type) {
            var query_string = this.build_query_string();
            if (type === "regular")
                window.location.href = `/regular_maint/export?${query_string}`;
            else
                window.location.href = `/regular_maint/export_detail?${query_string}`;
        },

        build_query_string: function() {
            var query_string_object = {};

            if (this.date_from.length > 0)
                query_string_object["date_from"] = this.date_from;

            if (this.date_to.length > 0)
                query_string_object["date_to"] = this.date_to;

            if (this.client.length > 0)
                query_string_object["client"] = this.client;

            if (this.chasis.length > 0)
                query_string_object["chasis"] = this.chasis;

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
                window.location.href = `regular_maint/?${query_string}`;
            else window.location.href = "regular_maint/";
        },

        onPageChange: function(page_id) {
            var query_string = this.build_query_string();
            if (query_string)
                window.location.href = `regular_maint/?${query_string}&page=${page_id}`;
            else window.location.href = `regular_maint/?page=${page_id}`;
        },

        stop_backpace: function(event) {
            if (event.keyCode == 8) {
                event.preventDefault();
            }
        }
    },
    computed: {
        display_filter: function() {
            return this.filter_visible ? "block" : "none";
        }
    },
    mounted: function() {
        window.addEventListener("keydown", e => {
            this.hit_enter(e);
        });
    }
};
</script>

<style></style>
