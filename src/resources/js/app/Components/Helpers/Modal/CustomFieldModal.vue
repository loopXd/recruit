<template>
    <modal :modal-id="modalId"
        :title="(selectedUrl || previousData) ? $editLabel('custom_field') : $addLabel('custom_field')"
        :preloader="preloader" :hide-cancel-button="Boolean(previousData)"
        :submit-button-text="previousData ? $t('update') : $t('save')" modal-size="default" @submit="submit"
        @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form :class="{ 'loading-opacity': preloader }" ref="form"
                :data-url="selectedUrl ? selectedUrl : (contextUrl ? contextUrl : '')">
                <div class="form-group">
                    <label for="name">{{ $t('name') }}</label>
                    <app-input id="name" type="text" :placeholder="$placeholder('custom_field', 'name')"
                        v-model="formData.name.label" :required="true" />
                </div>
                <div class="form-group">
                    <label for="type">{{ $t('type') }}</label>
                    <app-input id="type" type="select" :list="availableInputTypes" v-model="formData.type"
                        :required="true" />
                </div>
                <div v-if="haveMultiOptions" class="form-group">
                    <label for="option">{{ $t('options') }}</label>
                    <div class="custom-input-group mb-2">
                        <input id="option" type="text" class="form-control" :placeholder="$t('type_option_name')"
                            v-model="newSubOption" @keyup.enter="addOption" />
                        <div class="input-group-append">
                            <button type="button" @click="addOption">
                                <app-icon name="plus" class="size-18" />
                            </button>
                        </div>
                    </div>
                    <div v-if="formData.options && formData.options.length && haveMultiOptions">
                        <div class="default-base-color rounded d-flex align-items-center justify-content-between px-3 py-2 mb-1"
                            v-for="(option, index) in formData.options" :key="`multi-option-${index}`">
                            <span>{{ option }}</span>
                            <button type="button" class="btn bg-danger padding-5" @click="deleteOption(option)">
                                <app-icon name="trash-2" class="text-white size-12" />
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="haveMultiInput" class="form-group">
                    <template v-if="formData.custom_form && formData.custom_form.length">
                        <label for="option">{{ $t('custom_form') }}</label>
                        <div v-for="( field, index) in formData.custom_form" class="row align-items-end" :key="index">
                            <div class="col-7 form-group">
                                <label for="option">{{ $t('name') }}</label>
                                <div class="custom-input-group">
                                    <input id="option" type="text" class="form-control" :placeholder="$t('type_field_name')"
                                        v-model="formData.custom_form[index].name" />
                                </div>
                            </div>
                            <div class="col-4 form-group">
                                <label for="type">{{ $t('type') }}</label>
                                <app-input id="type" type="select" :list="availableInputTypesForSub"
                                    v-model="formData.custom_form[index].type" :required="true" />
                            </div>
                            <div class=" col-1 form-group">
                                <div class="mb-2">
                                    <button type="button" class="btn bg-danger padding-5" @click="deleteField(index)">
                                        <app-icon name="trash-2" class="text-white size-12" />
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 form-group" v-if="formData.custom_form[index].type == 'select' || formData.custom_form[index].type == 'checkbox' || formData.custom_form[index].type == 'radio'">
                                <label for="option">{{ $t('options') }}</label>
                                <div class="custom-input-group mb-2">
                                    <input id="option" type="text" class="form-control"
                                    v-model="newSubOption[nameGen(formData.custom_form[index].name)]"
                                        :placeholder="$t('type_option_name')" 
                                        @keyup.enter="addSubOption(index, nameGen(formData.custom_form[index].name))" />
                                    <div class="input-group-append">
                                        <button type="button" @click="addSubOption(index, nameGen(formData.custom_form[index].name))">
                                            <app-icon name="plus" class="size-18" />
                                        </button>
                                    </div>
                                </div>
                                <div v-if="formData.custom_form[index].options && formData.custom_form[index].options.length">
                                    <div class="default-base-color rounded d-flex align-items-center justify-content-between px-3 py-2 mb-1"
                                        v-for="(opt, idx) in formData.custom_form[index].options" :key="`multi-option-${idx}`">
                                        <span>{{ opt }}</span>
                                        <button type="button" class="btn bg-danger padding-5" @click="deleteSubOption(opt, index)">
                                            <app-icon name="trash-2" class="text-white size-12" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>

                    <button type="button" class="btn primary-text-color d-inline-flex align-items-center px-0"
                        @click="addCustomForm">
                        <app-icon name="plus" class="size-14 mr-2" />
                        {{ $t('add_more_fields') }}
                    </button>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import { ModalMixin } from '../../../Mixins/ModalMixin';
import { FormMixin } from '../../../../core/mixins/form/FormMixin';

export default {
    name: 'CustomFieldModal',
    mixins: [ModalMixin, FormMixin],
    props: {
        tableId: {
            type: String
        },
        contextUrl: {
            type: String
        },
        submissionType: {
            type: String,
            default: 'form'
        },
        previousData: {},
    },
    watch: {
        previousData: {
            handler: function (data) {
                if (this.submissionType !== 'form' && data) {
                    this.formData = _.cloneDeep(data)
                }
                if (!this.formData.hasOwnProperty("options")) {
                    this.formData.options = []
                }
                if (!this.formData.hasOwnProperty("custom_form")) {
                    this.formData.custom_form = []
                }
            },
            immediate: true
        },
        inputType: {
            handler: function (data) {
                if (this.haveMultiOptions) {
                    if (this.formData.options?.length < 1) this.formData.options = [];
                }
                // if (this.haveMultiInput) {
                //     if (this.formData.custom_form?.length < 1) this.formData.custom_form = [];
                // }
            }
        }
    },
    data() {
        return {
            modalId: 'custom-field-modal',
            formData: {
                name: '',
                type: '',
                options: [],
                custom_form: []
            },
            availableInputTypes: [
                { id: '', value: this.$chooseLabel('option'), disabled: true },
                { id: 'text', value: this.$t('text') },
                { id: 'email', value: this.$t('email') },
                { id: 'number', value: this.$t('number') },
                { id: 'textarea', value: this.$t('textarea') },
                { id: 'tel-input', value: this.$t('tel_input') },
                { id: 'date', value: this.$t('date') },
                { id: 'radio', value: this.$t('radio') },
                { id: 'checkbox', value: this.$t('checkbox') },
                { id: 'select', value: this.$t('select') },
                { id: 'dropzone', value: this.$t('Upload') },
                { id: 'custom-form', value: this.$t('custom_form') }
            ],
            availableInputTypesForSub: [
                { id: '', value: this.$chooseLabel('option'), disabled: true },
                { id: 'text', value: this.$t('text') },
                { id: 'email', value: this.$t('email') },
                { id: 'number', value: this.$t('number') },
                { id: 'textarea', value: this.$t('textarea') },
                { id: 'tel-input', value: this.$t('tel_input') },
                { id: 'date', value: this.$t('date') },
                { id: 'radio', value: this.$t('radio') },
                { id: 'select', value: this.$t('select') },
            ],
            newOption: '',
            newSubOption: {},
        }
    },
    computed: {
        haveMultiOptions() {
            return this.formData.type === 'radio' || this.formData.type === 'checkbox' || this.formData.type === 'select'
        },
        haveMultiInput() {
            return this.formData.type === 'custom-form'
        }
    },
    methods: {
        addOption() {
            if (this.newOption) {
                this.formData.options.push(this.newOption);
            }

            this.newOption = '';
        },
        addSubOption(index, key) {
            if (this.newSubOption[key]) {
                this.formData.custom_form[index].options.push(this.newSubOption[key]);
            }

            this.newSubOption[key] = '';
        },
        addCustomForm() {
            const newCustomForm = {
                name: '',
                type: '',
                options: [],
            };
            this.formData.custom_form.push(newCustomForm);
        },
        deleteOption(item) {
            this.formData.options.splice(this.formData.options.indexOf(item), 1);
        },
        deleteSubOption(item, idx) {
            
            this.formData.custom_form[idx].options.splice(this.formData.custom_form[idx].options.indexOf(item), 1);
        },
        deleteField(index) {
            this.formData.custom_form.splice(index, 1);
        },
        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
        submit() {
            let field = {};

            if (this.submissionType === 'form') {
                this.save(this.formData);
            } else {
                if (this.haveMultiOptions && (this.formData.options.length < 1)) {
                    this.$toastr.w(this.$t('option_should_not_be_empty'));
                    return;
                }
                if (this.haveMultiInput && !this.formData.custom_form.length) {
                    this.$toastr.w(this.$t('custom_form_should_not_be_empty'));
                    return;
                }
                if (this.formData.name && this.formData.type) {
                    field.name = this.formData.name;
                    field.type = this.formData.type;

                    if (this.formData.options && this.formData.options.length)
                        field.options = this.formData.options;
                    if (this.formData.custom_form && this.formData.custom_form.length)
                        field.custom_form = this.formData.custom_form;

                    if (!this.previousData) this.$emit('customFieldSubmit', field);
                    else this.$emit('customFieldUpdate', field);

                    this.afterFinalResponse();
                } else this.$toastr.w(this.$t('please_fill_all_fields'));
            }
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit(`reload-${this.tableId}`)
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.preloader = false;
        }
    }
}
</script>