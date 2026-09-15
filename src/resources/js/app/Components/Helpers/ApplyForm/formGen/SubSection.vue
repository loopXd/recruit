<template>
    <div class="d-flex align-items-center justify-content-between mb-1">
        <div class="d-flex align-items-center">
            <div class="width-30 height-30 text-white rounded d-inline-flex align-items-center justify-content-center mr-2 bg-primary">
                <app-icon name="list" class="size-16" />
            </div>
            <div class="text-muted">{{ item.fields.map(i => `${i.title}${i.required ? '*' : ''}`).join(', ') }}</div>
        </div>
        <div>
            <a v-if="item.actions.edit" href="#" class="text-muted text-left default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                @click.prevent="editItem(item)">
                <app-icon name="edit" class="size-14" />
            </a>
            <a v-if="item.actions.delete" href="#" class="text-muted text-left default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center ml-2"
                @click.prevent="deleteItem(item)">
                <app-icon name="trash-2" class="size-14" />
            </a>
        </div>
    </div>
</template>
<script>
export default {
    props: ['item'],
    data() {
        return {}
    },
    methods: {
        editItem(item){
            this.$emit('edit', item)
        },
        deleteItem(item){
            this.$emit('delete', item)
        },
    }
}
</script>