<template>
    <form :data-url="endpoint" class="mb-4" ref="form" @submit.prevent="submitData">
        <div class="mt-5">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-3 col-xl-3 col-md-3 col-sm-12">
                        <label class="ext-left d-block mb-2 mb-lg-0">{{
                                labelText ? labelText : $t('upload_file')
                            }}</label>
                        <small class="text-muted font-italic">{{ mutedText ? mutedText : $t('upload_file') }}</small>
                    </div>
                    <app-input
                        class="col-lg-8 col-xl-8 col-md-8 col-sm-12"
                        :id="inputId"
                        type="file"
                        :required="true"
                        :label="$t('upload_file')"
                        v-model="formData.uploaded_file"
                        :error-message="$errorMessage(errors, 'uploaded_file')"
                    />
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-3"></div>
            <div class="col-9">
                <app-submit-button
                    :label="buttonText ? buttonText :$t('save')"
                    :loading="loading"
                    @click="submitData"/>
            </div>
        </div>
    </form>
</template>

<script>

import {FormHelperMixin} from "../../../../../Mixins/FormHelperMixin";
import ErrorHandlerMixin from "../../../../../Mixins/ErrorHandlerMixin";

export default {
    name: "FileInput",
    mixins: [FormHelperMixin],
    props: ['endpoint', 'inputId', 'labelText', 'mutedText', 'buttonText'],
    data() {
        return {
            formData: {
                uploaded_file: '',
            },
            loading: false,
            errors: {},
        }
    },
    methods: {
        submitData() {
            this.loading = true;
            this.message = '';
            this.errors = {};

            let formData = new FormData;

            formData.append('_method', 'patch')
            formData.append('uploaded_file', this.formData.uploaded_file);

            this.submitFromFixin('post', this.endpoint, formData);
        },
        afterSuccess(response) {
            this.scrollToTop()
            this.toastAndReload(response.data.message);
        },
        afterError(res) {
            this.preloader = false;
            this.errors = res.data.errors;
            this.$toastr.s(res.data.message);
        },
    },
}
</script>
