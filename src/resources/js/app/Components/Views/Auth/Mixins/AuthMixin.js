import {FormMixin} from "../../../../../core/mixins/form/FormMixin";

export const AuthMixin ={
    props:{
        configData:{
            type: Object
        }
    },
    mixins: [FormMixin],
    data(){
        return{
            preloader: false,
            createAccountPreloader: false,
        }
    },
    methods:{
        beforeSubmit() {
            this.preloader = true;
        },
        afterError(res) {
            this.preloader = false;
            this.createAccountPreloader = false
            this.$toastr.e(res.data.message);
        },
        afterFinalResponse(){
            this.preloader = false;
        }
    }
}
