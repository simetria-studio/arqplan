<template>
  <span :class=currentClass>
    {{formatMoney(amount, locale, currencyCode, 
      subunitsValue,
      subunitsToUnit,
      hideSubunits,
      supplementalPrecision)}}
  </span>
</template>

<script>
import MoneyFormat from 'vue-money-format'
export default {
    extends: MoneyFormat,
    props: {
      colored: {
        type: Boolean,
        default: true
      },
      ignoreSignal: {
        type: Boolean,
        default: false
      }, 
      locale: {
        type: String,
        default: 'pt-BR'
      },
      currencyCode: {
        type: String,
        default: 'BRL'
      },
      supplementalPrecision: {
        type: Number,
        default: 0
      },
      subunitsValue: {
        type: Boolean,
        default: false
      },
      subunitsToUnit: {
        type: Number,
        default: 1
      },
      hideSubunits: {
        type: Boolean,
        default: false
      },
    },
    data() {
        return {
            currentClass: 'money_format',
        };
    },
    computed: {
      amount() {
        return this.ignoreSignal ? Math.abs(this.value) : this.value;
      }
    },
    mounted: function () {
      

      if(this.colored){
        this.currentClass = this.value < 0 ? 'money_format negative' : 'money_format positive'
      }
    },

}
</script>