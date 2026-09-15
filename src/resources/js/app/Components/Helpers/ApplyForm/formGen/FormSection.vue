<template>
    <form ref="form" data-url="" class="position-relative">
        <div :class="`rounded form-container border cursor-move mb-5 ${activateBorder ? 'form-wrapper' : ''}`"
             @mouseenter="handleMouseEnter"
             @mouseleave="handleMouseLeave"
             @mousedown="handleMouseLeave"
        >
            <div class="bg-off-light d-flex align-items-center justify-content-between p-4">
                <div class="d-flex align-items-center">
                    <app-input type="switch" v-model="section.is_visible" :disabled="!!section?.actions?.fixed"/>
                    <h6 class="mb-0">
                        <label class="mb-0">{{ section.title }}</label>
                    </h6>
                </div>
                <div>
                    <a href="#" v-if="section.actions.edit"
                       class="text-muted text-left default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                       @click.prevent="editSection(section)">
                        <app-icon name="edit" class="size-14"/>
                    </a>
                    <a href="#" v-if="section.actions.delete"
                       class="text-muted text-left default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center ml-2"
                       @click.prevent="deleteSection(section)">
                        <app-icon name="trash-2" class="size-14"/>
                    </a>
                </div>
            </div>

            <div v-if="section.is_visible" class="p-4">
                <sub-section v-for="(item, index) in section.items" :item="item" :key="`subsection-${index}`"
                             @edit="editItem" @delete="deleteItem"/>

                <button class="btn primary-text-color d-inline-flex align-items-center px-0" type="button"
                        @click="$emit('add-item-to-section', {section, item: null})">
                    <app-icon name="plus" class="size-14 mr-2"/>
                    {{ $t('add_item') }}
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import {FormMixin} from '../../../../../core/mixins/form/FormMixin';
import SubSection from './SubSection.vue';

export default {
    mixins: [FormMixin],
    props: ['section'],
    components: {SubSection},
    data() {
        return {
            seletedItem: null,
            activateBorder: false
        }
    },
    methods: {
        handleMouseEnter() {
            this.activateBorder = true
        },
        handleMouseLeave() {
            this.activateBorder = false
        },
        editSection(section) {
            this.$emit('edit', section)
        },
        deleteSection(section) {
            this.$emit('delete', section)
        },
        editItem(item) {
            this.$emit('edit-item', {section: this.section, item})
        },
        deleteItem(item) {
            this.$emit('delete-item', {section: this.section, item})
        },
    }
}
</script>

<style lang="scss">
.handle {
  left: 0;
  top: 0;
  height: 76px;
  width: 16px;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  padding: 15px 5px 15px 0;
  border-radius: 0 0 5px 5px;

  &__item {
    width: 100%;
    height: 3px;
    background-color: #afb1b6;
  }
}

.form-wrapper {
  border: 1px solid #e0dcdc !important;
}

.cursor-move {
  cursor: move !important;
}
</style>
