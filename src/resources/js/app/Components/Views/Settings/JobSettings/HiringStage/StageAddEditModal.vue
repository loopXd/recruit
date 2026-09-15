<template>
    <modal
        :modal-id="modalId"
        :title="selectedUrl ? $editLabel('hiring_stage') : $addLabel('hiring_stage')"
        :preloader="preloader"
        @submit="submit"
        @closeModal="closeModal">

        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="selectedUrl ? selectedUrl : STAGE">
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
import {STAGE} from "../../../../../Config/ApiUrl";
import ErrorHandlerMixin from "../../../../../Mixins/ErrorHandlerMixin";

export default {
    name: 'StageAddEditModal',
    mixins: [ModalMixin, FormMixin, ErrorHandlerMixin],
    props: {
        tableId: {type: String}
    },
    data() {
        return {
            STAGE,
            modalId: 'stage-add-edit-modal',
            formData: {}
        }
    },
    methods: {
        submit() {
            this.errorHandle = true;
            if (this.formData.name) this.formData.name = this.formData.name.toLowerCase();
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit(`reload-${this.tableId}`);
            this.closeModal();
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.preloader = false;
        }
    }
}
</script>