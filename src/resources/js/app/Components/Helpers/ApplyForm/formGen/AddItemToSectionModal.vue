<template>
    <!-- Modal scrolling being set to false to render the popover menus in additional-inputs-for-date-field-wrapper  -->
    <modal :modal-id="modalId" :title="$t('add_item_to_section')" :preloader="preloader" modal-size="extra-large" @submit="submit" :modal-scroll="false"
        @closeModal="$emit('close')">
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form" :data-url="''">
                <div class="row">
                    <div class="col">
                        <h4>{{ $t('fields') }}</h4>
                    </div>
                </div>
                <!-- <hr> -->
                <div v-for="(field, index) in formData.fields" :key="`field-${index}-${key}`">
                    <div class="row my-1 align-items-center">
                        <div class="col-6">
                            <div class="d-flex align-items-center w-100">
                                <app-input type="switch"
                                           :id="`field_${index}_is_visible`" v-model="field.is_visible" :disabled="!field.actions.edit" />
                                <app-input type="text" class="ml-2 w-100" v-model="field.title" :name="`field-${index}`" :disabled="getDisabledState(field)" required />
                            </div>
                        </div>
                        <div class="col-3">
                            <app-input type="select" v-model="field.type" :list="availableInputTypes" :disabled="!field.actions.edit"
                                       :id="`field_${index}_type`"
                                @input="($e) => { field.options = []; $e === 'custom-form' ? field.fields = [] : delete field.fields } " />
                        </div>
                        <div class="col-2">
                            <div class="d-flex flex-column">
                                <div class="d-flex">
                                    <input type="checkbox" :checked="field.required" :id="`field_${index}_required`" @input="($e) => field.required = $e.target.checked" :disabled="!field.actions.edit"><label :for="`field_${index}_required`" class="ml-1 mb-0" style="font-size: 80%">{{ $t('required') }}</label>
                                </div>
                                <div class="d-flex">
                                    <input type="checkbox" :checked="field.duplicate" :id="`field_${index}_duplicate`" @input="($e) => field.duplicate = $e.target.checked" :disabled="!field.actions.edit"><label :for="`field_${index}_duplicate`" class="ml-1 mb-0" style="font-size: 80%">{{ $t('duplicatable') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-1">
                            <div class="d-inline-flex align-items-center">
                                <a v-if="field.actions.delete" href="#"
                                    class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                                    @click="deleteField(field, index)">
                                    <app-icon name="trash-2" class="size-14" />
                                </a>
                            </div>
                        </div>
                        <template v-if="field.type === 'date'">
                            <div :key="maxMinInputKey" class="col-12 row additional-inputs-for-date-field-wrapper border-bottom my-4 pb-3" v-if="'dateOptions' in field">
                                <div class="col-4">
                                    <label>{{ $t('date_mode') }}</label>
                                    <app-input
                                        type="select"
                                        v-model="field?.dateOptions.dateMode"
                                        :list="availableDateInputModes"
                                    />
                           
                                </div>
                                <div class="col-4">
                                    <label :class=" field.dateOptions.dateMode === 'time' ? 'text-muted' : ''">{{ $t('min_date') }}</label>
                                    <template v-if="field.dateOptions.dateMode !== 'time'">
                                        <app-input
                                            type="date"
                                            v-model="field.dateOptions.minDate"
                                        />
                                    </template>
                                    <template v-else>
                                        <app-input
                                            type="date"
                                            :disabled="true"
                                        />
                                    </template>
                                </div>
                                <div class="col-4">
                                    <label :class=" field.dateOptions.dateMode === 'time' ? 'text-muted' : ''">{{ $t('max_date') }}</label>
                                    <template v-if="field.dateOptions.dateMode !== 'time'">
                                        <app-input
                                            type="date"
                                            v-model="field.dateOptions.maxDate"
                                        />
                                    </template>
                                    <template v-else>
                                        <app-input
                                            type="date"
                                            :disabled="true"
                                        />
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div class="row">
                        <div class="col">
                            <!-- add remove option -->
                            <div v-if="['radio', 'checkbox', 'select'].includes(field.type)"
                                class="py-2 px-5 alert-primary">
                                <div v-for="(option, i) in field.options" :key="`${index}-option-${i}`" class="mb-1">
                                    <div class="row align-items-center">
                                        <div class="col-3">{{ $t('option') }} {{ i + 1 }}</div>
                                        <div class="col-8">
                                            <app-input :ref="`field_${index}_options_${i}`" type="text" v-model="field.options[i]" @keyup.enter="addOption(index, i)"/>
                                        </div>
                                        <div class="col-1">
                                            <a href="#"
                                                class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                                                @click="field.options.splice(i, 1)">
                                                <app-icon name="trash-2" class="size-14" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn primary-text-color d-inline-flex align-items-center px-0" type="button"
                                    @click="addOption(index)">
                                    <app-icon name="plus" class="size-14 mr-2" /> {{ $t('add_option') }}
                                </button>
                            </div>
                            <!-- custom form -->
                            <div v-if="field.type === 'custom-form'" class="py-2 px-3 alert-primary">
                                <div v-for="(f, cfIndex) in field.fields" :key="`custom-form-field-${cfIndex}`">
                                    <div class="row my-1 align-items-center">
                                        <div class="col-6">
                                            <div class="d-flex align-items-center w-100">
                                                <app-input type="switch" v-model="f.is_visible"
                                                           :id="`field_${index}_cf_${cfIndex}_is_visible`" />
                                                <app-input type="text" class="ml-2 w-100" :name="`f-${cfIndex}`" v-model="f.title" />
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <app-input type="select"
                                                       :id="`field_${index}_cf_${cfIndex}_type`" v-model="f.type" :list="availableInputTypesForSub" @input="() => {f.options = []; rc();}" />
                                        </div>
                                        <div class="d-flex col-2">
                                            <input type="checkbox" :checked="f.required"
                                                   :id="`field_${index}_cf_${cfIndex}_required`"
                                                @input="($e) => f.required = $e.target.checked"> <label :for="`field_${index}_cf_${cfIndex}_required`" class="ml-1 mb-0" style="font-size: 80%">{{ $t('required') }}</label>
                                        </div>
                                        <div class="col-1">
                                            <div class="d-inline-flex align-items-center">
                                                <a href="#"
                                                    class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                                                    @click="() => { field.fields.splice(cfIndex, 1); rc(); }">
                                                    <app-icon name="trash-2" class="size-14" />
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <!-- add remove option from custom form -->
                                            <div v-if="['radio', 'checkbox', 'select'].includes(f.type)"
                                                class="py-2 px-5 alert-primary">
                                                <div v-for="(option, cfOptIndex) in f.options" :key="`f-${index}-cf-${cfIndex}-option-${cfOptIndex}`"
                                                    class="mb-1">
                                                    <div class="row align-items-center">
                                                        <div class="col-3">{{ $t('option') }} {{ cfOptIndex + 1 }}</div>
                                                        <div class="col-8">
                                                            <app-input type="text" :ref="`field_${index}_cf_${cfIndex}_options_${cfOptIndex}`" v-model="f.options[cfOptIndex]"  @keyup.enter="addOption(index, cfOptIndex, cfIndex)"/>
                                                        </div>
                                                        <div class="col-1">
                                                            <a href="#"
                                                                class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                                                                @click="() => { f.options.splice(cfOptIndex, 1); rc();} ">
                                                                <app-icon name="trash-2" class="size-14" />
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="btn primary-text-color d-inline-flex align-items-center px-0"
                                                    type="button" @click="addOption(index, null, cfIndex)">
                                                    <app-icon name="plus" class="size-14 mr-2" /> {{ $t('add_option') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- custom form date input type constraint inputs  -->
                                    <template v-if="f.type === 'date'">
                                        <div :key="maxMinInputKey" class="col-12 additional-inputs-for-date-field-wrapper border-bottom ml-4 my-4 mx-0 pb-3 px-0" v-if="'dateOptions' in f">
                                            <div class="mb-4 col-4">
                                                <label>{{ $t('date_mode') }}</label>
                                                <app-input
                                                    type="select"
                                                    v-model="f?.dateOptions.dateMode"
                                                    :list="availableDateInputModes"
                                                />

                                            </div>
                                            <div class="mb-4 col-4">
                                                <label :class=" f.dateOptions.dateMode === 'time' ? 'text-muted' : ''">{{ $t('min_date') }}</label>
                                                <template v-if="f.dateOptions.dateMode !== 'time'">
                                                    <app-input
                                                        type="date"
                                                        v-model="f.dateOptions.minDate"
                                                    />
                                                </template>
                                                <template v-else>
                                                    <app-input
                                                        type="date"
                                                        :disabled="true"
                                                    />
                                                </template>
                                            </div>
                                            <div class="mb-4 col-4">
                                                <label :class=" f.dateOptions.dateMode === 'time' ? 'text-muted' : ''">{{ $t('max_date') }}</label>
                                                <template v-if="f.dateOptions.dateMode !== 'time'">
                                                    <app-input
                                                        type="date"
                                                        v-model="f.dateOptions.maxDate"
                                                    />
                                                </template>
                                                <template v-else>
                                                    <app-input
                                                        type="date"
                                                        :disabled="true"
                                                    />
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <button class="btn primary-text-color d-inline-flex align-items-center px-0" type="button"
                                    @click="addInnerField(field)">
                                    <app-icon name="plus" class="size-14 mr-2" /> {{ $t('add_field') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn primary-text-color d-inline-flex align-items-center px-0" type="button"
                    @click="addField">
                    <app-icon name="plus" class="size-14 mr-2" /> {{ $t('add_field') }}
                </button>
            </form>
        </template>
    </modal>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import { uuidv4 } from '../../../../Helpers/TextHelper.js';
import _ from 'lodash'
export default {
    mixins: [FormMixin],
    props: ['modalId', 'item', 'sectionKey'],
    data() {
        return {
            preloader: false,
            key: 0,
            maxMinInputKey: 0,
            formData: {
                id: uuidv4(),
                fields: [],
                actions: { edit: true, delete: true, move: true }
            },
            dateInputDetails: {
                dateMode: '',
                minDate: '',
                maxDate: '',
            },
            availableDateInputModes: [
                { id: 'date', value: this.$t('date') },
                { id: 'time', value: this.$t('time') },
                { id: 'dateTime', value: this.$t('dateTime') },
                { id: 'range', value: this.$t('range') },
            ],
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
                { id: 'checkbox', value: this.$t('checkbox') },
                { id: 'radio', value: this.$t('radio') },
                { id: 'select', value: this.$t('select') },
            ],
        }
    },
    methods: {
        rc(){
            this.key++;
        },
        getDisabledState(field) {
            if (this.sectionKey === 'basic_information') return false;
            return !field.actions.edit
        },
        addOption(index, optionIndex = null, customFormIndex = null) {
            if(customFormIndex !== null && customFormIndex >= 0) {
                if(optionIndex !== null && optionIndex >= 0) {
                    if(this.formData.fields[index].fields[customFormIndex].options[optionIndex] === '') return;
                }
                let focusIndex = this.formData.fields[index].fields[customFormIndex].options.indexOf('')
                if(focusIndex < 0) {
                    this.formData.fields[index].fields[customFormIndex].options.push("")
                    focusIndex = this.formData.fields[index].fields[customFormIndex].options.indexOf('')
                }
                this.rc()
                this.$nextTick(() => {
                    try {
                        this.$refs[`field_${index}_cf_${customFormIndex}_options_${focusIndex}`][0].$el.querySelector('input').focus()
                    } catch (e) {}
                })
                return;
            }

            if(optionIndex !== null && optionIndex >= 0) {
                if(this.formData.fields[index].options[optionIndex] === '') return;
            }
            let focusIndex = this.formData.fields[index].options.indexOf('')
            if(focusIndex < 0) {
                this.formData.fields[index].options.push("")
                focusIndex = this.formData.fields[index].options.indexOf('')
            }
            this.rc()
            this.$nextTick(() => {
                try {
                    this.$refs[`field_${index}_options_${focusIndex}`][0].$el.querySelector('input').focus()
                } catch (e) {}
            })
        },
        submit() {
            const fieldsMapped = this.formData.fields.map(field => {
                if (!('dateOptions' in field)) return field;
                if (field.dateOptions.dateMode !== 'time') return field;
                return {...field, dateOptions: { ...field.dateOptions, minDate: '', maxDate: '' }}
            })

            const formData = { ...this.formData, fields: fieldsMapped };
            this.$emit('add-item-to-section', formData)
        },
        afterSuccess(response) { },
        afterSuccessFromGetEditData(response) { },
        addField() {
            let filed = { is_visible: true, title: '', type: 'text', options: [], required: false, actions: { edit: true, delete: true, move: true }, dateOptions: { dateMode: 'date' } }
            this.formData.fields.push(filed)
        },
        addInnerField(field) {
            field.fields.push({ title: '',
                type: 'text',
                options: [],
                required: false,
                is_visible: true,
                // these two values are used by the date constraint inputs, should the user select the date type
                fields: [],
                dateOptions: this.createDateOptions(field) 
            });
            this.rc();
        },
        deleteField(field, index) {
            this.formData.fields.splice(index, 1)
        },
        createDateOptions(field) {
            return {
                dateMode: 'date',
                minDate: field?.dateOptions?.minDate ? new Date(field.dateOptions.minDate) : '', 
                maxDate: field?.dateOptions?.maxDate ? new Date(field.dateOptions.maxDate) : new Date() 
            }
        }
    },
    mounted() {
        if (this.item) {
            this.formData = _.cloneDeep(this.item) 
            const fieldsMapped = this.formData.fields.map(field => ({
                ...field,
                dateOptions: this.createDateOptions(field), 
                fields: Array.isArray(field?.fields) ? field.fields.map(innerField => ({
                    ...innerField,
                    dateOptions: this.createDateOptions(innerField) 
                })) : [],
                name: 'fahim'
            }))
            this.formData.fields = fieldsMapped;
            setTimeout(() => {
                this.maxMinInputKey++;
            }, 1000);
        }
    }
}
</script>
