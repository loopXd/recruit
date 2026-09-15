<template>
    <modal
        :modal-id="modalId"
        :title="selectedUrl ?  $editLabel('department') : $addLabel('department')"
        :preloader="preloader"
        @submit="submit"
        @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="selectedUrl ? selectedUrl : DEPARTMENT">
                <div class="form-group mb-0">
                    <label for="name">
                        {{ $t('name') }}
                    </label>
                    <app-input
                        id="name"
                        type="text"
                        :required="true"
                        :placeholder="$placeholder('name')"
                        v-model="formData.name"
                    />
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import {ModalMixin} from '../../../../../Mixins/ModalMixin';
import {FormMixin} from '../../../../../../core/mixins/form/FormMixin';
import {DEPARTMENT} from "../../../../../Config/ApiUrl";

export default {
    name: 'DepartmentAddEditModal',
    mixins: [ModalMixin, FormMixin],
    props: {
        tableId: {type: String}
    },
    data() {
        return {
            DEPARTMENT,
            modalId: 'department-add-edit-modal',
            formData: {}
        }
    },
    methods: {
        submit() {
            this.save(this.formData);
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