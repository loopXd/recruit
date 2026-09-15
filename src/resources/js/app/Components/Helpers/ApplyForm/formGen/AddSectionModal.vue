<template>
    <modal :modal-id="modalId" :title="$t('add_section')" :preloader="preloader" modal-size="default" @submit="submit" @closeModal="$emit('close')">
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form" :data-url="''">
                <div class="form-group row">
                    <label for="assignment" class="mb-sm-0 col-sm-3">{{ $t('title') }}</label>
                    <div class="col-sm-9">
                        <app-input class="mb-2" name="title" type="text" v-model="formData.title" required />
                        <small class="text-danger" v-if="error">{{ error }}</small>
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import { uuidv4 } from '../../../../Helpers/TextHelper';
export default {
    mixins: [FormMixin],
    props: ['modalId', 'selected'],
    data() {
        return {
            preloader: false,
            formData: {
                title: ''
            },
            error: ''
        }
    },
    methods: {
        submit() {
            this.error = ''
            if(this.isValidForm()){
                let section = {
                    id: uuidv4(),
                    is_visible: true,
                    title: this.formData.title,
                    items: [],
                    actions: { edit: true, delete: true, move: true }
                }
                this.$emit('add-section', {section: section, update: !!this.selected})
            }else{
                this.error = this.$t('title_required')
            }
        },
        afterSuccess(response) { },
        afterSuccessFromGetEditData(response) { }
    },
    mounted(){
        if(this.selected){
            this.formData.title = this.selected.title
        }
    }
}
</script>