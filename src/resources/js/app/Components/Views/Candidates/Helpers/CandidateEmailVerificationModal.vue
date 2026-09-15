<template>
    <app-modal :modal-id="modalId" :title="$t('verify_email')" :preloader="preloader" :modal-backdrop="false"
        modal-size="default" :submit-button-text="$t('next')" :hide-cancel-button="true" @submit="submit"
        @closeModal="">
        <template slot="header">
            <h5 class="modal-title" id="exampleModalLabel">{{ $t('verify_email') }}</h5>
            <button type="button" class="close outline-none" @click.prevent="cancelApplication">
                <span>
                    <app-icon :name="'x'"></app-icon>
                </span>
            </button>
        </template>
        <template slot="body">
            <app-overlay-loader v-if="preloader" />
            <form class="mb-0" :class="{ 'loading-opacity': preloader }" ref="form" :data-url="PUBLIC_VERIFY_EMAIL"
                @submit.prevent="submit">
                <div class="form-group row align-items-center">
                    <label for="verify-email" class="mb-sm-0 col-sm-3">
                        {{ $t('email') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input type="email" :placeholder="$placeholder('email')" id="verify-email" :required="true"
                            :errorMessage="errors?.email?.length ? errors.email[0] : ''"
                            v-model="formData.email_address" @keyup.enter.prevent="submit" />
                    </div>
                </div>
            </form>
        </template>
        <template slot="footer">
            <button type="button" class="btn btn-primary" v-if="!errors?.email?.length" @click="submit">{{ $t('next')
                }}</button>
            <button type="button" class="btn btn-primary" v-else @click="back">{{ $t('back') }}</button>
        </template>
    </app-modal>
</template>

<script>

import { PUBLIC_VERIFY_EMAIL } from "../../../../Config/ApiUrl";
import { FormMixin } from "../../../../../core/mixins/form/FormMixin";
import { urlGenerator } from "../../../../Helpers/AxiosHelper";

export default {
    name: 'CandidateEmailVerificationModal',
    mixins: [FormMixin],
    props: {
        modalId: {
            type: String
        },
        jobPostId: {}
    },
    data() {
        return {
            PUBLIC_VERIFY_EMAIL,
            preloader: false,
            formData: {
                email_address: '',
                job_post_id: this.jobPostId
            },
            errors: []
        }
    },
    methods: {
        beforeSubmit() {
            this.preloader = true;
        },
        back() {
            window.location.replace(urlGenerator('/'));
        },
        submit() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            if (response.data.status == false) {
                this.$toastr.w(response.data.message);
            } else {
                this.$emit('verifiedData', this.formData.email_address, response.data);
            }
            this.preloader = false;
            this.closeModal()
        },
        afterError(res) {
            this.preloader = false;

            if (res.status === 422) {
                if (res?.data?.errors) {
                    this.errors = res.data.errors
                }
                return
            }

            this.$toastr.e(res.data.message);
        },
        cancelApplication() {
            this.$emit('cancelApplication');
        },
        closeModal() {
            this.$emit('close');
        },
    },
    mounted() {
        $(`#${this.modalId}`).modal('show');
    }
}
</script>
