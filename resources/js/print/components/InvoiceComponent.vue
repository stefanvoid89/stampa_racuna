<template ></template>
<script>
/** UPUTSTVO ZA KORISCENJE ENGINA */
/**   za svaki ispis prave se 3 odvojena view-a -> html_content je content strane , page_content je header i footer, render je view koji sadrzi engine componentu
 *    render blade strana je univerzalna strana za sve ispise (moze da bude ako se od ispisa do ispisa ne menja format strane)
 *    u controlleru se tim view-ima predaju variable i oni se renderovani predaju enginu kao html variable
 *    u created metodi se html_content parsira kao html nodovi i pakuje u nodes array
 *    page_content treba da ima  section tag sa tabelom koja ima 3 reda sledecih id vrednosti -> header_row, content_row i footer_row
 *    u content_row se pakuje content koji engine uzima iz html_content-a, tj iz nodes array-a
 */
export default {
  props: { prop_data: Object, title: String },
  data: function() {
    return {
      html_content: "",
      page_content: "",
      page_height: 0,
      nodes: [],
      elements: []
    };
  },

  methods: {
    get_element_height: function(id) {
      var base_element = this.nodes[id];
      var element = base_element.cloneNode(true);

      var page_for_measure = this.page_content
        .getElementById("page")
        .cloneNode(true);
      this.$el.appendChild(page_for_measure);
      var content_node_for_measure = page_for_measure.querySelector(
        "#content_row"
      );

      var dom_element = content_node_for_measure.appendChild(element);

      // var dom_element = document.body.appendChild(element);
      var height = dom_element.offsetHeight;

      height += parseInt(
        window.getComputedStyle(dom_element).getPropertyValue("margin-top")
      );
      height += parseInt(
        window.getComputedStyle(dom_element).getPropertyValue("margin-bottom")
      );
      //   document.body.removeChild(dom_element);
      this.$el.removeChild(page_for_measure);
      return height;
    },

    test: function(id) {
      var base_element = this.nodes[id];
      var element = base_element.cloneNode(true);

      var page_for_measure = this.page_content
        .getElementById("page")
        .cloneNode(true);
      this.$el.appendChild(page_for_measure);
      var content_node_for_measure = page_for_measure.querySelector(
        "#content_row"
      );

      var dom_element = content_node_for_measure.appendChild(element);

      var top = window
        .getComputedStyle(dom_element)
        .getPropertyValue("margin-top");
      var bottom = window
        .getComputedStyle(dom_element)
        .getPropertyValue("margin-bottom");
      console.log("margine height", top);
      console.log("margine height", bottom);

      // var dom_element = document.body.appendChild(element);
      var height = dom_element.offsetHeight;

      height += parseInt(
        window.getComputedStyle(dom_element).getPropertyValue("margin-top")
      );
      height += parseInt(
        window.getComputedStyle(dom_element).getPropertyValue("margin-bottom")
      );

      var height_from_loop = Array.from(element.rows)
        .map((row, map_index) => {
          return row.offsetHeight;
        })
        .reduce((a, b) => a + b, 0);
      return height;
    },

    get_page_height: function() {
      var base_element = this.page_content.getElementById("page");
      var element = base_element.cloneNode(true);
      var dom_element = document.body.appendChild(element);
      var style = window.getComputedStyle(dom_element, null);
      var height = Math.floor(parseFloat(style.getPropertyValue("height"))); // dom_element.clientHeight;

      console.log("visina strane " + height);

      var padding =
        Math.ceil(parseFloat(style.getPropertyValue("padding-top"))) +
        Math.ceil(parseFloat(style.getPropertyValue("padding-bottom")));

      console.log("padding strane " + padding);

      height -= padding;

      var header = document.querySelector("#header_row");
      var header_height = document.querySelector("#header_row").clientHeight;

      console.log("header height pre margina " + header_height);

      let header_margina_top = parseInt(
        window.getComputedStyle(header).getPropertyValue("margin-top")
      );

      console.log("header margina top " + header_margina_top);
      header_height += header_margina_top;

      let header_margina_bottom = parseInt(
        window.getComputedStyle(header).getPropertyValue("margin-bottom")
      );

      console.log("header margina bottom " + header_margina_bottom);

      header_height += header_margina_bottom;

      var footer = dom_element.querySelector("#footer_row");
      var footer_height = document.querySelector("#footer_row").clientHeight;

      console.log("footer " + footer_height);

      let footer_margin_top = parseInt(
        window.getComputedStyle(footer).getPropertyValue("margin-top")
      );

      console.log("footer margin top " + footer_margin_top);

      footer_height += footer_margin_top;

      let footer_margin_bottom = parseInt(
        window.getComputedStyle(footer).getPropertyValue("margin-bottom")
      );

      console.log("footer margin bottom " + footer_margin_bottom);

      footer_height += footer_margin_bottom;

      height = height - header_height - footer_height;

      console.log("final height is ", height);
      document.body.removeChild(dom_element);

      return height;
    },

    get_all_content: function() {
      return Array.from(
        this.html_content
          .getElementById("main_table")
          .querySelectorAll("table.parent")
      );
    },
    mount_nodes: function() {
      var remained_page_height = this.page_height;
      var page;
      var page_counter = 1;
      var elements = [];
      var _new_element_first_row = 0;

      // prvi deo punjenje arraya elements koji se posle renderuje na page-ove

      for (var index = 0; index < this.nodes.length; index++) {
        var node_height = this.get_element_height(index);
        // svaki element je tabela sa klasom parent koja moze da bude renderovana iscela ili podeljena na vise strana u okvirima headera i footera
        var element = this.nodes[index].cloneNode(true);
        console.log(
          "for index " +
            index +
            " node height is  " +
            node_height +
            "px and remained page height is " +
            remained_page_height
        );

        if (node_height > this.page_height) {
          console.log("Renderovanje se ne moze nastaviti!!!!!!!!!!!!!!!!");
          console.log(
            `page height je ${this.page_height} a node je visint ${node_height}`
          );
          return;
        }

        if (element.classList.contains("footer")) {
          if (remained_page_height > node_height) {
            this.nodes[index].style.marginTop =
              remained_page_height - node_height + "px";
            elements.push({ node: this.nodes[index], page: page_counter });
            page_counter++;
            remained_page_height = this.page_height;
          } else {
            this.nodes[index].style.marginTop =
              this.page_height - node_height + "px";
            page_counter++;
            elements.push({ node: this.nodes[index], page: page_counter });
            page_counter++;
            remained_page_height = this.page_height;
          }
        } else {
          if (remained_page_height > node_height) {
            elements.push({ node: this.nodes[index], page: page_counter });
            remained_page_height -= node_height;
          } else {
            // sa new_element se "skidaju" redovi i dodaju na  _new_element ; prvo se _new_element brise do headera i onda se puni redovima iz new_element
            var new_element = element.cloneNode(true);
            var _new_element = element.cloneNode(true);

            // _new_element_first_row je uveden jer chrome dodaje thead u tabele pa se ne zna da li tabela stvarno ima header
            if (_new_element.tHead.rows)
              _new_element_first_row = _new_element.tHead.rows.length || 0;

            for (
              var i = _new_element.rows.length - 1;
              i >= _new_element_first_row && i >= 0;
              i--
            ) {
              _new_element.deleteRow(i);
            }

            // page_for_measure sluzi za pakovanje contenta kako bi se videlo koliko mesta je ostalo na strani
            var page_for_measure = this.page_content
              .getElementById("page")
              .cloneNode(true);
            this.$el.appendChild(page_for_measure);
            var content_node_for_measure = page_for_measure.querySelector(
              "#content_row"
            );

            var dom_element = content_node_for_measure.appendChild(new_element);

            for (var i = new_element.rows.length - 1; i >= 0; i--) {
              var height = Array.from(new_element.rows)
                .map(row => row.offsetHeight)
                .reduce((a, b) => a + b, 0);

              if (height > remained_page_height) {
                var row = new_element.rows[i].cloneNode(true);
                _new_element
                  .getElementsByTagName("tbody")[0]
                  .insertBefore(row, _new_element.rows[_new_element_first_row]);
                new_element.deleteRow(i);
              } else {
                break;
              }
            }

            this.$el.removeChild(page_for_measure);

            // ovo je glavna caka -- this.nodes je lista svih parent nodova; ovde se "umece" novi node koji u ovoj istoj petlji dolazi naredni na obradu
            // i tako fakticki rekurzivno dok se ne "potrosi"
            this.nodes.splice(index + 1, 0, _new_element);
            elements.push({ node: new_element, page: page_counter });
            page_counter++;
            remained_page_height = this.page_height;
          }
        }
      }

      //drugi deo renderovanje nodova

      //var uveden zbog footer reda
      var page_count = elements
        .map(el => el.page)
        .reduce((a, b) => Math.max(a, b));

      for (var i = 1; i <= page_count; i++) {
        var _elements = elements.filter(element => element.page == i);
        page = this.page_content.getElementById("page").cloneNode(true);
        page.innerHTML = page.innerHTML.replace("#strana#", i);
        var content_node = page.querySelector("#content_row");
        _elements.map(element => {
          // broj strane
          element.node.innerHTML = element.node.innerHTML.replace(
            "#strana#",
            i
          );
          console.log(_new_element);
          content_node.appendChild(element.node);
        });
        this.$el.appendChild(page);
      }

      this.elements = elements;
    },
    mount_page: function() {
      var page = this.page_content.getElementById("page").cloneNode(true);
      this.$el.appendChild(page);
    }
  },

  created: function() {
    var dom_parser = new DOMParser();
    this.html_content = dom_parser.parseFromString(
      this.prop_data.html_prop,
      "text/html"
    );
    this.page_content = dom_parser.parseFromString(
      this.prop_data.page,
      "text/html"
    );
    this.nodes = this.get_all_content();
    this.page_height = this.get_page_height();
  },
  mounted: function() {
    this.mount_nodes();
    //this.mount_page();
  }
};
</script>




