<template>
  <div class="vue-query-builder">
    <slot v-bind="vqbProps">
      <query-builder-group
        v-bind="vqbProps"
        :query.sync="query"
      />
    </slot>
  </div>
</template>

<script>
/* eslint-disable vue/require-default-prop */
import QueryBuilderGroup from './Group.vue';
import deepClone from './utilities.js';

var defaultLabels = {
  matchType: "Condición",
  matchTypes: [
    {"id": "all", "label": "Y"},
    {"id": "any", "label": "O"},
  ],
  addRule: "Agregar regla",
  removeRule: "&times;",
  addGroup: "Agregar Grupo",
  removeGroup: "&times;",
  textInputPlaceholder: "valor",
};

export default {
  name: 'VueQueryBuilder',

  components: {
    QueryBuilderGroup
  },

  props: {
    rules: Array,
    labels: {
      type: Object,
      default () {
        return defaultLabels;
      }
    },
    maxDepth: {
      type: Number,
      default: 3,
      validator: function (value) {
        return value >= 1
      }
    },
    value: Object
  },

  data () {
    return {
      query: {
        logicalOperator: this.labels.matchTypes[0].id,
        children: []
      },
      ruleTypes: {
        "text": {
          operators: ['equals','does not equal','contains','does not contain','is empty','is not empty','is null','is no null','begins with','ends with'],
          inputType: "text",
          id: "text-field"
        },
        "numeric": {
          operators: ['=','<>','<','<=','>','>='],
          inputType: "number",
          id: "number-field"
        },
        "custom": {
          operators: [],
          inputType: "text",
          id: "custom-field"
        },
        "radio": {
          operators: [],
          choices: [],
          inputType: "radio",
          id: "radio-field"
        },
        "checkbox": {
          operators: [],
          choices: [],
          inputType: "checkbox",
          id: "checkbox-field"
        },
        "select": {
          operators: ['='],
          choices: [],
          inputType: "select",
          id: "select-field"
        },
        "multi-select": {
          operators: ['='],
          choices: [],
          inputType: "select",
          id: "multi-select-field"
        },
        "date": {
          operators: ['=','<=','>='],
          choices: [],
          inputType: "date",
          id: "date-filed"
        },
      }
    }
  },

  computed: {
    mergedLabels () {
      return Object.assign({}, defaultLabels, this.labels);
    },

    mergedRules () {
      var mergedRules = [];
      var vm = this;

      vm.rules.forEach(function(rule){
        if ( typeof vm.ruleTypes[rule.type] !== "undefined" ) {
          mergedRules.push( Object.assign({}, vm.ruleTypes[rule.type], rule) );
        } else {
          mergedRules.push( rule );
        }
      });

      return mergedRules;
    },

    vqbProps () {
      return {
        index: 0,
        depth: 1,
        maxDepth: this.maxDepth,
        ruleTypes: this.ruleTypes,
        rules: this.mergedRules,
        labels: this.mergedLabels
      }
    }
  },

  mounted () {
    this.$watch(
      'query',
      newQuery => {
        if (JSON.stringify(newQuery) !== JSON.stringify(this.value)) {
          this.$emit('input', deepClone(newQuery));
        }
      }, {
      deep: true
    });

    this.$watch(
      'value',
      newValue => {
        if (JSON.stringify(newValue) !== JSON.stringify(this.query)) {
          this.query = deepClone(newValue);
        }
      }, {
      deep: true
    });

    if ( typeof this.$options.propsData.value !== "undefined" ) {
      this.query = Object.assign(this.query, this.$options.propsData.value);
    }
  }
}
</script>