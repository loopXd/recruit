<template>
    <modal
        :modal-id="modalId"
        :title="modalTitle"
        :preloader="preloader"
        :submit-button-text="$t('send')"
        @submit="submit"
        @closeModal="closeModal">

        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="TEST_MAIL"
            >
                <div class="form-group row align-items-center">
                    <label for="email_address" class="mb-sm-0 col-sm-3">
                        {{ $t('email_address') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            :label="$t('email_address')"
                            :placeholder="$placeholder('email_address')"
                            v-model="formData.email"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label for="subject" class="mb-sm-0 col-sm-3">
                        {{ $t('subject') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            :label="$t('subject')"
                            :placeholder="$placeholder('subject')"
                            v-model="formData.subject"
                            :required="true"
                        />
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <label for="message" class="mb-sm-0 col-sm-3">
                        {{ $t('message') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            type="textarea"
                            :label="$t('message')"
                            :placeholder="$placeholder('message')"
                            v-model="formData.message"
                            :required="true"
                        />
                    </div>
                </div>

            </form>
        </template>
    </modal>
</template>

<script>

import {ModalMixin} from "../../../../Mixins/ModalMixin";
import {TEST_MAIL} from '../../../../Config/ApiUrl';
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import ErrorHandlerMixin from "../../../../Mixins/ErrorHandlerMixin";

export default {
    name: "TestMailModal",
    mixins: [ModalMixin, FormMixin, ErrorHandlerMixin],
    data() {
        return {
            TEST_MAIL,
            formData: {},
            modalId: 'test-mail-modal',
            modalTitle: this.$t('send_test_email'),
            preloader: false,
        }
    },
    methods: {
        submit() {
            this.errorHandle = true;
            this.save(this.formData);
        },
        afterSuccess({data}) {
            this.formData = {};
            $('#test-mail-modal').modal('hide');
            this.$emit('input', false);
            this.$toastr.s(data.message);
        },
        afterError(res) {
            this.$toastr.e(res.data.message);
            this.errors = res.data.errors;
            if(this.errorHandle) this.handleFormSubmissionError('formData', res.data.errors);
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.preloader = false;
        }
    },
}
</script>