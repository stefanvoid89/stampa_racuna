require("./utils.js")

window.Vue = require("vue")

// automatska registracija svih komponenti
const files = require.context("./print", true, /\.vue$/i)
files.keys().map(key =>
    Vue.component(
        key
            .split("/")
            .pop()
            .split(".")[0],
        files(key).default
    )
)

Vue.prototype.csrf_token = document.head.querySelector(
    'meta[name="csrf-token"]'
).content

const app = new Vue({
    el: "#app"
})
