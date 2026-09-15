<template>
    <div class="single-filter text-filter">
        <input
            :id="filterId"
            type="text"
            class="form-control form-control-sm"
            :placeholder="placeholderText"
            v-model="value"
            @input="changed"
        />
    </div>
</template>

<script>
import {FilterMixin} from './mixins/FilterMixin';

export default {
    name: "TextFilter",
    mixins: [FilterMixin],
    props: {
        label: {
            type: String,
            default: ''
        },
        active: {}
    },
    computed: {
        placeholderText() {
            return this.label || this.$t('search');
        }
    },
    data() {
        return {
            value: this.active || '',
            initialActiveValue: this.active || ''
        }
    },
    watch: {
        active: {
            handler(value) {
                this.value = value || '';
            },
            immediate: true
        }
    },
    methods: {
        changed(event) {
            this.value = event.target.value;
            this.isApply = String(this.value).trim() !== '';
            this.returnValue(this.value);
        }
    },
    mounted() {
        this.$hub.$on('clearAllFilter-' + this.tableId, () => {
            this.value = this.initialActiveValue;
            this.isApply = String(this.value).trim() !== '';
        });
    }
}
</script>
