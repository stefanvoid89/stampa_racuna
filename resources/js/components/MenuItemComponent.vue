<template>
  <div
    class="button_primary"
    style="display:block"
    :class="display_item"
    :style="{top:top_distance + 'px',left:left_distance + 'px',zIndex:depth}"
    @mouseenter="toggle_display"
    @mouseleave="toggle_display"
  >
    <template v-if="nodes.length">
      {{label}}
      <menu-item-component
        v-for="(node,index) in nodes"
        :key="index"
        :nodes="node.nodes"
        :label="node.label"
        :depth="depth_level + 1"
        :index="index"
        :show_me="display_child_item"
        :link="node.link"
      ></menu-item-component>
    </template>
    <template v-else>
      <a style="display:block" :href="link">{{label}}</a>
    </template>
  </div>
</template>


<script>
export default {
  props: {
    nodes: {
      type: Array,
      default: () => []
    },
    label: String,
    depth: Number,
    show_me: Boolean,
    index: Number,
    link: {
      type: String,
      default: () => "#"
    }
  },
  data: function() {
    return {
      display_child_item: false,
      depth_level: this.depth,
      top_constant: 0,
      left_constant: 0
    };
  },
  computed: {
    display_item: function() {
      if (this.depth > 0) {
        return [this.show_me ? "display-me" : "hide-me", "child-menu-item"];
      } else return "root-menu-item";
    },

    top_distance: function() {
      if (this.depth > 0) {
        if (this.depth == 1) return (this.index + 1) * this.top_constant - 1;
        else return this.index * this.top_constant;
      }
    },
    left_distance: function() {
      if (this.depth > 0) {
        if (this.depth == 1) return 0;
        else return this.left_constant;
      }
    }
  },
  methods: {
    toggle_display() {
      this.display_child_item = !this.display_child_item;
    }
  },
  mounted: function() {
    this.top_constant = this.$parent.$el.offsetHeight;
    this.left_constant = this.$parent.$el.offsetWidth;
  }
};
</script>


<style scoped>
.root-menu-item {
  position: relative;
  text-align: center;
}
.child-menu-item {
  position: absolute;
  text-align: LEFT;
  min-height: 10px;
  min-width: 150px;
}
.display-me {
  visibility: visible;
}
.hide-me {
  visibility: hidden;
}
</style>
