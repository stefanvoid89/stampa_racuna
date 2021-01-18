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
                                    <td>Radni nalog:</td>
                                    <td>
                                        <input
                                            type="text"
                                            id="wo"
                                            v-model="wo"
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
                                    <td>Prijemni savetnik:</td>
                                    <td>
                                        <select
                                            id="korisnik"
                                            v-model="referent"
                                        >
                                            <option
                                                v-for="_referent in referents"
                                                :key="_referent"
                                                :value="_referent"
                                                >{{ _referent }}</option
                                            >
                                        </select>
                                    </td>

                                    <td>Sasija:</td>
                                    <td>
                                        <input type="text" v-model="chasis" />
                                    </td>
                                    <td colspan="2"></td>
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
                                    @click.prevent="export_excel"
                                >
                                    EXPORT
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
                                <th>Datum naloga</th>
                                <th>Broj naloga</th>
                                <th>Prijemni savetnik</th>
                                <th>Marka</th>
                                <th>Sasija</th>
                                <th>Vlasnik</th>
                                <th>Delovi</th>
                                <th>Rad</th>
                                <th>Tudji rad</th>
                                <th>Ucesce</th>
                                <th>Ukupno</th>

                                <th>Ukupno za placanje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="_wo in wos.data" :key="_wo.anid">
                                <td>{{ _wo.DatumNaloga }}</td>
                                <td>{{ _wo.brojnaloga }}</td>
                                <td>{{ _wo.prijemnisavetnik }}</td>
                                <td>{{ _wo.marca }}</td>
                                <td>{{ _wo.sasija }}</td>
                                <td>{{ _wo.vlasnik }}</td>
                                <td>{{ _wo.delovi }}</td>
                                <td>{{ _wo.rad }}</td>
                                <td>{{ _wo.tudjirad }}</td>
                                <td>{{ _wo.ucesce }}</td>
                                <td>{{ _wo.ukupno }}</td>

                                <td>{{ _wo.ukupnozaplacanje }}</td>
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
            marcas: this.$props.prop_data.marcas,
            referents: this.$props.prop_data.referents,
            date_from: this.$props.prop_data.prop_values.date_from || "",
            date_to: this.$props.prop_data.prop_values.date_to || "",
            marca: this.$props.prop_data.prop_values.marca || "",
            referent: this.$props.prop_data.prop_values.referent || "",
            wo: this.$props.prop_data.prop_values.wo || "",
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
        export_excel: function() {
            var query_string = this.build_query_string();
            window.location.href = `/open_wo/export?${query_string}`;
        },

        build_query_string: function() {
            var query_string_object = {};

            if (this.date_from.length > 0)
                query_string_object["date_from"] = this.date_from;

            if (this.date_to.length > 0)
                query_string_object["date_to"] = this.date_to;

            if (this.marca.length > 0)
                query_string_object["marca"] = this.marca;

            if (this.referent.length > 0 && this.referent !== "Svi")
                query_string_object["referent"] = this.referent;

            if (this.wo.length > 0) query_string_object["wo"] = this.wo;

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
                window.location.href = `open_wo/?${query_string}`;
            else window.location.href = "open_wo/";
        },

        onPageChange: function(page_id) {
            var query_string = this.build_query_string();
            if (query_string)
                window.location.href = `open_wo/?${query_string}&page=${page_id}`;
            else window.location.href = `open_wo/?page=${page_id}`;
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
