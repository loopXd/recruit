<template>
    <div>
        <draggable v-model="formData" ghost-class="ghost" :move="checkMove">
            <form-section v-for="(section, index) in formData" :section="section" :key="`section-${index}`"
                @add-item-to-section="openAddItemToSctionModal" @edit="openAddSectionModal" @delete="removeSection(index)"
                @edit-item="openAddItemToSctionModal" @delete-item="removeItemfromSection"></form-section>
        </draggable>

        <add-section-modal v-if="isAddSectionModalOpen" :modal-id="addSectionModalId" @close="closeAddSectionModal"
            @add-section="addSection" :selected="selectedSection" />
        <add-item-to-section-modal v-if="isAddItemToSctionModalOpen" :modal-id="addItemToSctionModalId"
            @close="closeAddItemToSctionModal" @add-item-to-section="addItemToSection" :item="selectedItem" :section-key="selectedSection.key" />

        <button type="button" class="btn primary-text-color d-inline-flex align-items-center px-0 mb-primary"
            @click.prevent="openAddSectionModal(null)">
            <app-icon name="plus" class="size-14 mr-2" /> {{ $t('add_more_section') }}
        </button>

        <div class="d-flex justify-content-start align-items-center">
            <a href="#" class="btn btn-success d-inline-flex align-items-center justify-content-center"
                @click.prevent="saveForm">
                <app-icon name="save" class="size-17 mr-2" />
                {{ $t('save_changes') }}
            </a>
            <a v-if="preview" href="#" class="btn btn-primary d-inline-flex align-items-center justify-content-center ml-2"
                @click.prevent="viewApplicationForm">
                <app-icon name="eye" class="size-17 mr-2" />
                {{ $t('view_apply_form') }}
            </a>
        </div>
    </div>
</template>

<script>
import FormSection from './FormSection.vue';
import AddSectionModal from './AddSectionModal.vue';
import AddItemToSectionModal from './AddItemToSectionModal.vue';
import draggable from 'vuedraggable'
import _ from 'lodash'
export default {
    components: { FormSection, AddSectionModal, AddItemToSectionModal, draggable },
    props: ['formDataResponse', 'preview'],
    data() {
        return {
            // add section modal
            addSectionModalId: 'add-section-modal',
            isAddSectionModalOpen: false,
            // add item to section modal
            addItemToSctionModalId: 'add-item-to-sction-modal-id',
            isAddItemToSctionModalOpen: false,
            selectedSection: null,
            selectedItem: null,
            formData: []
        }
    },
    methods: {
        checkMove: function (evt) {
            // cannot move fixed once
            if (evt.draggedContext?.element?.actions?.fixed === true) return false
            let futureIndex = evt.draggedContext.futureIndex;
            let futureSection = this.formData[futureIndex] || null
            // cannot move to fixed once
            if (futureSection?.actions?.fixed === true) return false
            return true;
        },
        addSection({ section, update }) {
            if (update) {
                this.selectedSection.title = section.title
            } else {
                this.formData.push(section)
            }
            this.closeAddSectionModal()
        },
        removeSection(index) {
            this.formData.splice(index, 1)
        },
        // add section modal
        openAddSectionModal(section) {
            this.selectedSection = section
            this.isAddSectionModalOpen = true
            $(`#${this.addSectionModalId}`).modal('show')
        },
        closeAddSectionModal() {
            $(`#${this.addSectionModalId}`).modal('hide')
            this.isAddSectionModalOpen = false
            this.selectedSection = null
        },
        // add item to section modal
        openAddItemToSctionModal({ section, item }) {
            this.selectedSection = section
            this.selectedItem = item
            this.isAddItemToSctionModalOpen = true
            $(`#${this.addItemToSctionModalId}`).modal('show')
        },
        closeAddItemToSctionModal() {
            $(`#${this.addItemToSctionModalId}`).modal('hide')
            this.selectedSection = null
            this.selectedItem = null
            this.isAddItemToSctionModalOpen = false
        },
        addItemToSection(field) {
            if (this.selectedItem) {
                let item = this.formData.find(i => i.id === this.selectedSection.id).items.find(i => i.id === field.id)
                item.id = field.id
                item.fields = field.fields
            } else {
                this.formData.find(i => i.id === this.selectedSection.id).items.push(field)
            }
            this.closeAddItemToSctionModal()
        },
        removeItemfromSection({ section, item }) {
            section.items = section.items.filter(i => i.id !== item.id)
        },
        // form submit
        saveForm() {
            this.$emit('submit', this.formData)
        },
        viewApplicationForm() {
            this.$emit('viewForm')
        }
    },
    mounted() {
        this.formData = _.cloneDeep(this.formDataResponse)
    }
}
</script>
