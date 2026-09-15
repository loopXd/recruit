<template>
    <modal :modal-id="modalId"
           :title="selectedUrl ? $editLabel('location') : $addLabel('location')"
           :preloader="preloader"
           @submit="submit"
           @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="selectedUrl ? selectedUrl : COMPANY_LOCATION">
                <div class="form-group mb-0">
                    <label for="address">
                        {{ $t('address') }}
                    </label>
                    <app-input
                        id="address"
                        type="text"
                        :required="true"
                        :placeholder="$placeholder('address')"
                        v-model="formData.address"
                    />
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
    import {ModalMixin} from "../../../../../Mixins/ModalMixin";
    import {FormMixin} from "../../../../../../core/mixins/form/FormMixin";
    import {COMPANY_LOCATION} from "../../../../../Config/ApiUrl";

    export default {
        name: 'LocationAddEditModal',
        mixins: [ModalMixin, FormMixin],
        props: {
            tableId: {type: String}
        },
        data() {
            return {
                COMPANY_LOCATION,
                modalId: 'location-add-edit-modal',
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