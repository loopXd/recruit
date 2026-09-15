<template>
    <form ref="form" data-url="">
        <div v-for="(item, index) in formData.items" :key="`item-${item.id}-${index}`">
            <div v-for="(field, fieldIndex) in item.fields" :key="`i-${index}-field-${field.id}-${fieldIndex}`">
                <div class="mb-4" v-if="field.is_visible">
                    <!-- custom form -->
                    <template v-if="field.type === 'custom-form'">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="mb-3 text-black-50 font-weight-bold text-uppercase font-2xl"
                                style="letter-spacing: 6px;">
                                {{ field.title }}<sup v-if="field.required">*</sup>
                            </label>
                            <button type="button"
                                class="border-0 text-danger default-base-color width-30 height-30 rounded-pill d-inline-flex align-items-center justify-content-center"
                                v-if="field.removeable" @click="item.fields.splice(fieldIndex, 1)">
                                <app-icon name="x" />
                            </button>
                        </div>
                        <div class="border-bottom">
                            <div class="mb-4" v-for="(f, i) in field.fields"
                                :key="`i-${index}-f-${fieldIndex}-custom-fields-${f.id}-${i}`">
                                <label class="fahim">{{ f.title }} <sup v-if="f.required">*</sup></label>
                                <template v-if="!('dateOptions' in f)">
                                    <app-input
                                        :name="f.name || nameGen(`${field.title}-${index}-${fieldIndex}-${f.title}-${i}`)"
                                        :id="f.id || `i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :key="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :radioCheckboxName="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        v-model="f.value" :placeholder="f.title"
                                        :list="(f.options || []).map(i => ({ id: i, value: i }))" :type="f.type"
                                        :required="f.required" :disabled="f.disabled" />
                                </template>
                                <template v-else>
                                    <app-input
                                        :name="f.name || nameGen(`${field.title}-${index}-${fieldIndex}-${f.title}-${i}`)"
                                        :id="f.id || `i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :key="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        :radioCheckboxName="`i-${index}-f-${fieldIndex}-item-${item.id}-${i}`"
                                        v-model="f.value" :placeholder="f.title"
                                        :list="(f.options || []).map(i => ({ id: i, value: i }))" :type="f.type"
                                        :required="f.required" :disabled="f.disabled" v-bind="f.dateOptions" />
                                </template>
                                <small class="text-danger" v-if="true">{{
                                    errors[nameGen(`${field.title}-${index}-${fieldIndex}-${f.title}-${i}`)] }}</small>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="d-flex justify-content-between align-items-center">
                            <label>{{ field.title }}<sup v-if="field.required">*</sup></label>
                            <button type="button"
                                class="border-0 text-danger default-base-color width-30 height-30 rounded-pill d-inline-flex align-items-center justify-content-center"
                                v-if="field.removeable" @click="item.fields.splice(fieldIndex, 1)">
                                <app-icon name="x" />
                            </button>
                        </div>
                        <template v-if="!('dateOptions' in field)">
                            <app-input
                                :type="field.type"
                                :placeholder="field.title"
                                :id="field.id || `i-${index}-item-${fieldIndex}`"
                                :key="`i-${index}-item-${item.id}-${fieldIndex}`"
                                :name="nameGen(`${field.title}-${index}-${fieldIndex}`)"
                                :field-name="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :radioCheckboxName="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :list="(field.options || []).map(i => ({ id: i, value: i }))"
                                :required="field.required"
                                v-model="field.value"
                                v-bind="field.type === 'tel-input' ? { validCharactersOnly: true } : {}"
                                :disabled="field.disabled" />
                        </template>
                        <template v-else>
                            <app-input
                                :type="field.type"
                                :placeholder="field.title"
                                :id="field.id || `i-${index}-item-${fieldIndex}`"
                                :key="`i-${index}-item-${item.id}-${fieldIndex}`"
                                :name="nameGen(`${field.title}-${index}-${fieldIndex}`)"
                                :field-name="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :radioCheckboxName="nameGen(`i-${index}-${field.title}-${index}-${fieldIndex}`)"
                                :list="(field.options || []).map(i => ({ id: i, value: i }))"
                                :required="field.required"
                                v-model="field.value"
                                v-bind="field.dateOptions" />
                        </template>

                        <small class="text-danger" v-if="true">
                            {{ errors[nameGen(`${field.title}-${index}-${fieldIndex}`)] }}
                        </small>
                    </template>
                    <div>
                        <button class="btn primary-text-color d-inline-flex align-items-center px-0"
                            v-if="field.duplicate" @click="add_more(item, field, fieldIndex)">{{ $t('add_more') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane-action">
            <button v-if="!(index < 1)" type="button" @click.prevent="goPrevQuesStep">
                <app-icon name="chevron-left" class="mr-2" />
                {{ $t('previous') }}
            </button>
            <div v-else></div>
            <button v-if="steps > (index + 1)" type="button" @click.prevent="goNextQuesStep()">
                {{ $t('next') }}
                <app-icon name="chevron-right" class="ml-2" />
            </button>
            <button v-else type="button" @click.prevent="goNextQuesStep(true)">
                {{ $t('finish') }}
            </button>
        </div>
    </form>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import _ from 'lodash';

export default {
    mixins: [FormMixin],
    props: ['formBody', 'steps', 'index', 'selectedForm'],
    data() {
        return {
            formData: _.cloneDeep(this.selectedForm || this.formBody),
            errors: {}
        }
    },
    methods: {
        /* --- RG default unificado --- */
        setDefaultRG() {
            const rgField = this.formData.items.flatMap(item => item.fields).find(f => f.id === 'rg');
            if (!rgField) return;
            if (!rgField.value || rgField.value.trim() === '') {
                rgField.value = '000000-SN';
                const rgInput = document.getElementById('rg');
                if (rgInput) rgInput.value = rgField.value;
            }
            return rgField.value;
        },

        /* --- Navegação --- */
        goNextQuesStep(isFinalStep = false) {
            let invalidComponents = this.isValidForm();
            this.errors = {};
            for (let component of invalidComponents) {
                let name = component.$attrs.name;
                this.errors[name] = this.$t("this_field_is_required");
            }
            if (!invalidComponents.length) {
                this.$emit('next', this.formData, isFinalStep);
            }
        },
        goPrevQuesStep() {
            this.$emit('previous');
        },

        add_more(item, field, index) {
            let newField = _.cloneDeep(field);
            delete field.duplicate;
            field.removeable = true;
            if (newField.type === 'custom-form') {
                newField.fields = newField.fields.map(f => ({ ...f, value: '' }));
            } else {
                delete newField.value;
            }
            let fields = this.formData.items.find(i => i.id === item.id).fields;
            fields.splice(index + 1, 0, newField);
        },

        removeField(item, field, index) {
            let fields = this.formData.items.find(i => i.id === item.id).fields;
            fields.splice(index, 1);
        },

        nameGen(value) {
            return value.split(' ').map(i => i.toLowerCase()).join('-');
        },

        /* --- Form Validation --- */
        isValidForm() {
            let instance = this;
            let childComponents = this.getInputComponents();
            let invalidFields = [];
            let message = "";

            childComponents.forEach((item) => {
                instance.fields[item.name] = item.value;

                if (item.required && (!item.value || item.value.length === 0)) {
                    message = this.$t("this_field_is_required");
                    invalidFields.push(item);
                    instance.makeFieldStatusObject(item, message, "required");
                } else if (item.type === "email" && !this.isValidEmail(item.value)) {
                    message = this.$t("this_field_is_invalid");
                    invalidFields.push(item);
                    instance.makeFieldStatusObject(item, message, "email");
                } else if (item.id === "cpf") {
                    const cpf = (item.value || '').replace(/\D/g, '');
                    if (cpf.length === 11 && !this.isValidCPF(cpf)) {
                        message = this.$t("cpf_invalid");
                        invalidFields.push(item);
                        instance.makeFieldStatusObject(item, message, "cpf");
                    }
                } else if (item.id === "rg") {
                    if (!/^\d{6}-[A-Z]{2}$/.test(item.value || '')) {
                        this.setDefaultRG();
                    }
                }
            });

            return invalidFields;
        },

        /* --- CEP --- */
        applyCepMask(event) {
            let value = event.target.value.replace(/\D/g, '');
            if (value.length <= 5) {
                value = value.replace(/(\d{5})(\d{1,})/, '$1-$2');
            } else {
                value = value.replace(/(\d{5})(\d{3})(\d{0,})/, '$1-$2');
            }
            this.updateAddressFields({ cep: value });
            event.target.value = value;
        },
        fetchAddress(event) {
            const cep = event.target.value.replace(/\D/g, '');
            if (cep.length === 8) this.getAddressFromCep(cep);
        },
        async getAddressFromCep(cep) {
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                this.updateAddressFields(data);
            } catch (error) {
                this.$toastr.e("Não foi possível encontrar o CEP informado");
            }
        },
        updateAddressFields(data) {
            this.formData.items.forEach(item => {
                item.fields.forEach(field => {
                    if (field.type === 'custom-form') {
                        field.fields.forEach(f => {
                            if (f.id === 'zipcode') f.value = data.cep || '';
                            if (f.id === 'adrress') f.value = data.logradouro || '';
                            if (f.id === 'neighborhood') f.value = data.bairro || '';
                            if (f.id === 'city') f.value = data.localidade || '';
                            if (f.id === 'state') f.value = data.uf || '';
                        });
                    }
                });
            });
        },

        /* --- CPF --- */
        applyCPFMask(event) {
            let value = event.target.value.replace(/\D/g, '');
            if (value.length > 11) value = value.slice(0, 11);

            if (value.length > 9) value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{1,2})$/, '$1.$2.$3-$4');
            else if (value.length > 6) value = value.replace(/^(\d{3})(\d{3})(\d{1,3})$/, '$1.$2.$3');
            else if (value.length > 3) value = value.replace(/^(\d{3})(\d{1,3})$/, '$1.$2');

            event.target.value = value;

            this.formData.items.forEach(item => {
                item.fields.forEach(field => {
                    if (field.id === 'cpf') field.value = value;
                });
            });
        },
        validateCPFOnBlur(event) {
            const value = event.target.value.replace(/\D/g, '');
            if (!this.isValidCPF(value)) this.$toastr.e("O CPF informado não é válido");
        },
        isValidCPF(cpf) {
            if (!cpf || cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) return false;
            let sum = 0, rest;
            for (let i = 1; i <= 9; i++) sum += parseInt(cpf.substring(i - 1, i)) * (11 - i);
            rest = (sum * 10) % 11; if (rest === 10 || rest === 11) rest = 0;
            if (rest !== parseInt(cpf.substring(9, 10))) return false;
            sum = 0;
            for (let i = 1; i <= 10; i++) sum += parseInt(cpf.substring(i - 1, i)) * (12 - i);
            rest = (sum * 10) % 11; if (rest === 10 || rest === 11) rest = 0;
            if (rest !== parseInt(cpf.substring(10, 11))) return false;
            return true;
        },

        /* --- RG --- */
        applyRGMask(event) {
            let value = event.target.value.toUpperCase();
            value = value.replace(/[^0-9A-Z]/g, '');
            if (value.length > 2) value = value.slice(0, -2) + '-' + value.slice(-2);
            event.target.value = value;

            this.formData.items.forEach(item => {
                item.fields.forEach(field => {
                    if (field.id === 'rg') field.value = value;
                });
            });
        },
        validateRGOnBlur(event) {
            const value = this.setDefaultRG();
            if (!/^\d{6}-[A-Z]{2}$/.test(value)) {
                this.$toastr.e("O formato correto do RG é 123456-SP");
            }
        }
    },

    mounted() {
        // CEP
        const zipcodeInput = document.getElementById('zipcode');
        if (zipcodeInput) {
            zipcodeInput.addEventListener('input', this.applyCepMask);
            zipcodeInput.addEventListener('input', this.fetchAddress);
        }

        // CPF
        const cpfInput = document.getElementById('cpf');
        if (cpfInput) {
            cpfInput.addEventListener('input', this.applyCPFMask);
            cpfInput.addEventListener('blur', this.validateCPFOnBlur);
        }

        // RG
        const rgInput = document.getElementById('rg');
        if (rgInput) {
            rgInput.addEventListener('input', this.applyRGMask);
            rgInput.addEventListener('blur', this.validateRGOnBlur);
        }
    },

    beforeDestroy() {
        // CEP
        const zipcodeInput = document.getElementById('zipcode');
        if (zipcodeInput) {
            zipcodeInput.removeEventListener('input', this.applyCepMask);
            zipcodeInput.removeEventListener('input', this.fetchAddress);
        }

        // CPF
        const cpfInput = document.getElementById('cpf');
        if (cpfInput) {
            cpfInput.removeEventListener('input', this.applyCPFMask);
            cpfInput.removeEventListener('blur', this.validateCPFOnBlur);
        }

        // RG
        const rgInput = document.getElementById('rg');
        if (rgInput) {
            rgInput.removeEventListener('input', this.applyRGMask);
            rgInput.removeEventListener('blur', this.validateRGOnBlur);
        }
    }
}
</script>

<style lang="scss" scoped></style>
