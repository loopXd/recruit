<template>
    <div :id="'dropdown-'+rowData.id"
         class="dropdown keep-inside-clicks-open dropdown-note-editor d-inline-block">
        <button
            type="button"
            class="btn p-0 primary-text-color"
            data-toggle="dropdown">
            <app-icon name="file-text" :class="iconClass"/>
        </button>
        <div class="dropdown-menu p-primary mt-1">
            <div>
                <p class="text-secondary">
                    {{ noteTitle }}
                </p>
                <div v-if="!isEditNote" class="note note-warning custom-scrollbar p-4 mb-5">
                    <p class="text-muted">
                        {{ formData.disqualification_reason }}
                    </p>
                </div>
                <div v-else class="mb-5">
                    <form class="position-relative mb-0"
                          :class="{'loading-opacity': preloader}"
                          ref="form"
                          :data-url="url">
                        <app-overlay-loader v-if="preloader"/>
                        <app-input
                            type="textarea"
                            v-model="formData.disqualification_reason"
                            :text-area-rows="4"
                            :required="true"
                            :placeholder="$t('type_note_here')"
                        />
                    </form>
                </div>
                <div class="text-right">
                    <template v-if="isEditNote">
                        <a href="#"
                           class="btn btn-secondary mr-1"
                           :class="{'disabled': preloader}"
                           @click.prevent="closeDropDown">
                            {{ $t('cancel') }}
                        </a>
                        <a href="#"
                           class="btn btn-primary mr-1"
                           :class="{'disabled': preloader}"
                           @click.prevent="submitData">
                            {{ $t('save') }}
                        </a>
                    </template>
                    <template v-else>
                        <a href="#"
                           v-if="editPermission"
                           class="btn btn-link mr-1"
                           @click.prevent="toggleEditable">
                            {{ $t('edit') }}
                        </a>
                        <a href="#"
                           class="btn btn-secondary"
                           @click.prevent="closeDropDown">
                            {{ $t('close') }}
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {FormHelperMixin} from "../../../Mixins/FormHelperMixin";

export default {
    name: "AppNoteEditor",
    mixins: [FormHelperMixin],
    props: {
        tableId: {},
        url: {},
        rowData: {},
        noteTitle: {
            default: function () {
                return this.$t('default_note_title');
            }
        },
        noteDescription: {
            default: function () {
                return this.$t('default_note_description');
            }
        },
        method: {
            type: String,
            default: 'patch'
        },
        iconClass: {
            type: String,
            default: 'size-18'
        },
        editPermission: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            preloader: false,
            isEditNote: false,
            formData: {
                disqualification_reason: this.noteDescription
            }
        }
    },
    mounted() {
        $('#dropdown-' + this.rowData.id).on('hidden.bs.dropdown', () => {
            this.isEditNote = false;
        })
    },
    methods: {
        closeDropDown() {
            this.isEditNote = false;
            $(".dropdown-menu").removeClass('show')
        },
        toggleEditable() {
            this.isEditNote = !this.isEditNote
        },
        submitData() {
            this.submitFromFixin(this.method, this.url, this.formData)
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.closeDropDown();
            if(this.tableId) this.$hub.$emit(`reload-${this.tableId}`)
        }
    }
}
</script>

<style scoped lang="scss">
.dropdown {
    .dropdown-menu {
        width: 350px;

        .note {
            overflow-y: auto;
            max-height: 200px;
        }
    }
}
</style>