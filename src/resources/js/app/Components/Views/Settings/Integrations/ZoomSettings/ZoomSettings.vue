<template>
    <div class="container-fluid p-0">
        <app-overlay-loader v-if="preloader"/>
        <form v-else ref="form" data-url="/admin/app/settings/zoom-settings"
              class="mb-0"
              :class="{'loading-opacity': preloader}">

            <div class="form-group row align-items-center">
                <label for="api_key" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('api_key') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="api_key"
                               type="text"
                               v-model="formData.api_key"
                               :placeholder="$placeholder('api_key')"
                               :required="true"/>
                </div>
            </div>

            <div class="form-group row align-items-center">
                <label for="api_secret" class="col-lg-3 col-xl-3 mb-lg-0">
                    {{ $t('api_secret') }}
                </label>
                <div class="col-lg-8 col-xl-8">
                    <app-input id="api_secret"
                               type="password"
                               v-model="formData.api_secret"
                               :placeholder="$placeholder('api_secret')"
                               :show-password="true"
                               :required="true"/>
                </div>
            </div>

            <div v-if="$have('PERMISSION_UPDATE_DELIVERY_SETTINGS')" class="mt-5 action-buttons">
                <button class="btn btn-primary mr-2" @click.prevent="submit">
                    {{ $t('save') }}
                </button>
            </div>
        </form>
    </div>
</template>

<script>
import {axiosGet} from "../../../../../Helpers/AxiosHelper";
import {GET_ZOOM_SETTINGS} from "../../../../../Config/ApiUrl";
import {FormMixin} from "../../../../../../core/mixins/form/FormMixin";

export default {
    name: "ZoomSettings",
    mixins: [FormMixin],
    data() {
        return {
            preloader: false,
            formData: {
                api_secret: '',
                api_key: ''
            },
        }
    },
    created() {
        this.getZoomSettings();
    },
    methods: {
        beforeSubmit() {
            this.preloader = true;
        },
        submit() {
            this.save(this.formData);
        },
        afterFinalResponse() {
            this.preloader = false;
        },
        afterSuccess(res) {
            this.$toastr.s(res.data.message);
        },
        afterError(res) {
            this.$toastr.e(res.data.message);
        },
        getZoomSettings() {
            this.preloader = true;
            axiosGet(GET_ZOOM_SETTINGS).then((res) => {
                if (res.data.length !== 0){
                    this.formData = res.data;
                }
                this.preloader = false;
            })
        },
    }
}
</script>