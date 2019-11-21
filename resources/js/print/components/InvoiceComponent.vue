<template ></template>
<script>
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

      console.log(
        "visina iz loopa je " + height_from_loop + "  dok je visina " + height
      );

      return height;
    },

    get_page_height: function() {
      var base_element = this.page_content.getElementById("page");
      var element = base_element.cloneNode(true);
      var dom_element = document.body.appendChild(element);
      var style = window.getComputedStyle(dom_element, null);
      var height = Math.floor(parseFloat(style.getPropertyValue("height"))); // dom_element.clientHeight;

      var padding =
        Math.ceil(parseFloat(style.getPropertyValue("padding-top"))) +
        Math.ceil(parseFloat(style.getPropertyValue("padding-bottom")));
      height -= padding;

      var header = document.querySelector("#header_row");
      var header_height = document.querySelector("#header_row").clientHeight;
      var header_style = window.getComputedStyle(header, null);
      var style_header_height = header_style.height;

      header_height += parseInt(
        window.getComputedStyle(header).getPropertyValue("margin-top")
      );

      header_height += parseInt(
        window.getComputedStyle(header).getPropertyValue("margin-bottom")
      );
      var footer = dom_element.querySelector("#footer_row");
      var footer_height = document.querySelector("#footer_row").clientHeight;

      footer_height += parseInt(
        window.getComputedStyle(footer).getPropertyValue("margin-top")
      );

      footer_height += parseInt(
        window.getComputedStyle(footer).getPropertyValue("margin-bottom")
      );

      height = height - header_height - footer_height - 10; // 10 pixela koje ne mogu da uhvatim, a i bolje da ima manje mesta kako ne bi guralo futer
      console.log("header height", header_height);
      console.log("footer height", footer_height);
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
        // for (var index = 0; index < 1; index++) {
        var node_height = this.get_element_height(index);
        var element = this.nodes[index].cloneNode(true);
        console.log(
          "for index " +
            index +
            " node height is  " +
            node_height +
            "px and remained page height is " +
            remained_page_height
        );

        if (element.classList.contains("footer")) {
          //   console.log("footer");
          //   console.log(node_height);
          //   console.log(remained_page_height);
          //   console.log(remained_page_height);
          //   console.log("footer");
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
            console.log(
              "remained page height is greater than node height and node is instered on page " +
                page_counter
            );
            elements.push({ node: this.nodes[index], page: page_counter });
            remained_page_height -= node_height;
          } else {
            var new_element = element.cloneNode(true);
            var _new_element = element.cloneNode(true);

            if (_new_element.tHead.rows)
              _new_element_first_row = _new_element.tHead.rows.length;

            for (
              var i = _new_element.rows.length - 1;
              i > _new_element_first_row - 1 && i > 0;
              i--
            ) {
              _new_element.deleteRow(i);
            }

            // _new_element_first_row = 1;

            // if (_new_element.querySelector("th") == null) {
            //   _new_element.deleteRow(0);
            //   _new_element_first_row = 0;
            // }

            var page_for_measure = this.page_content
              .getElementById("page")
              .cloneNode(true);
            this.$el.appendChild(page_for_measure);
            var content_node_for_measure = page_for_measure.querySelector(
              "#content_row"
            );

            var dom_element = content_node_for_measure.appendChild(new_element);

            //   console.log(
            //     "new element ima " +
            //       new_element.rows.length +
            //       " elemenata i visinu " +
            //       new_element.offsetHeight
            //   );

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




