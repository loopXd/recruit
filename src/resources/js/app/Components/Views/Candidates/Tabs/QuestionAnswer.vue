<template>
    <div>
        <div v-for="(step, stepIndex) in applyFormComputed" :key="`step-${stepIndex}`">
            <h5 class="step-title">{{ step.title }}</h5>
            <div v-for="(form, formIndex) in step.items" :key="`form-${formIndex}`"
                class="my-2 text-muted">
                <table class="table table-borderless">
                    <tbody>
                        <tr v-for="(field, index) in form.fields" :key="`item-${index}`">
                            <td class="w-50 px-0" :colspan="field.type === 'custom-form' ? 2 : 1" :class="{ 'border-bottom': field.type === 'custom-form' && (index !== form.fields.length - 1) }">
                                <template v-if="field.type === 'custom-form'">
                                    <label class="mb-3 text-black-50 font-weight-bold text-uppercase font-2xl"
                                        style="letter-spacing: 6px; font-size: 0.9rem">{{ field.title }}</label>
                                </template>
                                <template v-else>
                                    <span class="text-muted">{{ field.title }}</span>
                                </template>
                                <table v-if="field.type === 'custom-form'" class="table table-borderless m-0">
                                    <tbody>
                                        <tr v-for="(f, i) in field.fields"
                                            :key="`custome-field-${index}-${i}`">
                                            <td class="w-50 px-0">
                                                <span class="text-muted">{{ f.title }}</span>
                                            </td>
                                            <td class="w-50 px-0">
                                                <span v-if="['text', 'email', 'number', 'textarea', 'tel-input'].includes(f.type)">{{ f.value || '-' }}</span>
                                                <span v-else-if="['radio', 'checkbox', 'select'].includes(f.type)">
                                                    <app-input
                                                        :id="`item-${f.id}-${index}`"
                                                        :key="`item-${f.id}-${index}`"
                                                        :radioCheckboxName="f.title"
                                                        v-model="f.value"
                                                        :list="(f.options || []).map(i => ({ id: i, value: i }))"
                                                        :type="f.type"
                                                        :disabled="true"
                                                    />
                                                </span>
                                                <span v-else>{{ f.type === 'date' ? formatDateToLocal(f.value) || '-' : f.value || '-' }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                            <td class="w-50 px-0" v-if="field.type !== 'custom-form'">
                                <span v-if="field.type === 'dropzone'" class="text-muted">
                                    <div v-for="(file) in field.value">
                                        <span>{{ file.name }}</span>
                                    </div>
                                </span>
                                <span v-else-if="['text', 'email', 'number', 'textarea', 'tel-input'].includes(field.type)">{{ field.value || '-' }}</span>
                                <span v-else-if="['radio', 'checkbox', 'select'].includes(field.type)">
                                    <app-input
                                        :id="`item-${field.id}-${index}`"
                                        :key="`item-${field.id}-${index}`"
                                        :radioCheckboxName="field.title"
                                        v-model="field.value"
                                        :list="(field.options || []).map(i => ({ id: i, value: i }))"
                                        :type="field.type"
                                        :disabled="true"
                                    />
                                </span>
                                <span v-else>{{ field.type === 'date' ? formatDateToLocal(field.value) || '-' : field.value || '-' }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
    </div>
</template>

<script>
import { formatDateToLocal } from '../../../../Helpers/DateTimeHelper'
export default {
    name: "QuestionAnswer",
    props: {
        applyForm: {},
        answers: {}
    },
    data() {
        return {
            visibleIndex: 0,
            formatDateToLocal,
        }
    },
    computed: {
        applyFormComputed() {
            return this.applyForm?.filter((_, i) => +i !== 0)
        }
    },
    methods: {
        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
    }
}
</script>
