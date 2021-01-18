<template>
    <div class="menu-container" style="background: #B5D4EC; ">
        <menu-item-component
            v-for="(menu_item, index) in menu_items"
            :key="index"
            :nodes="menu_item.nodes"
            :label="menu_item.label"
            :depth="0"
            :index="0"
            :show_me="true"
            :link="menu_item.link"
        ></menu-item-component>
    </div>
</template>
<script>
export default {
    data: function() {
        return {
            menu_items: [
                {
                    label: "POCETNA",
                    link: "/"
                },
                {
                    label: "SERVIS",
                    nodes: [
                        { label: "STAMPA FAKTURA", link: "/print" },
                        { label: "NEZATVORENI NALOZI", link: "/open_wo" }
                    ]
                },

                {
                    label: "REKLAMACIJE",

                    link: "/warranty"
                },
                {
                    label: "CRM",

                    link: "/crm"
                }
            ]
        };
    },
    props: {},
    methods: {}
};
</script>

<style scoped>
.menu-container {
    display: flex;
}
</style>
