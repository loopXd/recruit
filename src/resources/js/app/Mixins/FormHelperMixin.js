import {FormMixin} from '../../core/mixins/form/FormMixin';
import HelperMixin from "./HelperMixin";
export const FormHelperMixin = {
    mixins: [FormMixin, HelperMixin],
    data() {
        return {
            preloader:false,
        }
    },
    methods: {
        beforeSubmit(){
            this.preloader = true;
        },
        afterError(response) {
            this.$toastr.e(response.data.message);
        },
        afterFinalResponse() {
            this.preloader = false;
        }
    }
}