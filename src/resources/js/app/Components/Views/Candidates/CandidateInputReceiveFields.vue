<template>
    <div>
        <div v-for="(item, index) in formBody.fields">
            <div class="mb-4" v-if="item.is_visible">
                <template v-if="item.type === 'custom-form' && false">
                    <!-- <pre>{{ item }}</pre> -->
                    <label class=" mx-2 mb-3 text-black-50 font-weight-bold text-uppercase font-2xl" style=" letter-spacing: 6px;">
                     {{ item.name.label }}
                        <!-- <sup v-if="field.require">*</sup> -->
                    </label>
                    <div class="px-2">
                        <div class="mb-4" v-for="(subField, subIndex) in item.custom_form" :key="'subField' + subIndex">
                            <label :for="subField.name.key">{{ subField.name.label }}
                                <sup v-if="subField.require">*</sup>
                            </label>
                            <pre>{{ formData.fields[index].custom_form[subIndex].value }}</pre>
                            <!-- :error-message="$errorMessage(errorObj, nameGen(subField.name) + [subIndex], false)" -->
                            <app-input class="mb-1" :type="subField.type" :id="subField.name.key"
                                :name="subField.name.key + subIndex" :required="subField.require"
                                v-model="formData.fields[index].custom_form[subIndex].value" />
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn text-danger d-inline-flex align-items-center mt-3 p-0"
                                @click="deleteSubFields(parentIndex, childIndex, subField.group, item.key, nameGen(field.name))">
                                {{ $t('remove') }}
                            </button>
                        </div>
                        <div class="mb-2 mt-4 border-bottom">
                        </div>
                        <div class="tab-pane-action mb-5">
                            <button type="button" class="btn btn-block justify-content-center"
                                @click="addSubFields(parentIndex, childIndex)">
                                <app-icon name="plus" class="size-14 mr-2" />
                                {{ $t('add_new') }}
                            </button>
                        </div>
                    </div>
                </template>
                <template v-else>
                    <label :for="item.name.key">{{ item.name.label }}<sup v-if="item.require">*</sup></label>
                    <!-- <app-input :error-message="$errorMessage(errorObj, item.name, false)" /> -->
                    <app-input :type="item.type" :id="item.name.key" :list="listGen(item.options, item.type, item)"
                        :required="item.require" v-model="formData.fields[index].value" />
                </template>
            </div>
        </div>
        <div class="tab-pane-action">
            <button type="button" v-if="currentIndex" @click.prevent="goPrevQuesStep()">
                <app-icon name="chevron-left" class="mr-2" />
                {{ $t('previous') }}
            </button>
            <div v-else></div>
            <button type="button" @click.prevent="goNextQuesStep">
                {{ $t('next') }}
                <app-icon name="chevron-right" class="ml-2" />
            </button>
        </div>

    </div>
</template>

<script>
import { FormMixin } from "../../../../core/mixins/form/FormMixin";
export default {
    mixins: [FormMixin],
    props: {
        title: {
            default: ''
        },
        formBody: {

        },
        currentIndex: {

        },
        errorObj: {
            default: () => { }
        }
    },
    data() {
        return {
            formData: this.formBody,
        }
    },
    methods: {
        listGen(options, type, field) {

            let useLang = ['gender', 'email'];

            if (!(type === 'radio' || type === 'checkbox' || type === 'select')) return [];
            if (this.isUndefined(options) || options.length < 1) return [];
            return options.map(item => {
                return {
                    id: this.nameGen(item),
                    value: useLang.includes(field.name) ? this.$t(item) : item
                }
            })
        },
        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
        goNextQuesStep() {
            this.$emit('next', this.formData)
        },
        goPrevQuesStep() {
            this.$emit('previous')
        },
        addSubFields() {
            
        }
    }
}
</script>

<style lang="scss" scoped></style>
