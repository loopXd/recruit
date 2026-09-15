<template>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-8">
                <div class="back-image"
                     :style="'background-image: url('+ urlGenerator(configData.company_banner) +')'">
                </div>
            </div>
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 pl-md-0">
                <div class="login-form d-flex align-items-center">
                    <form class="sign-in-sign-up-form w-100"
                          ref="form" data-url="/admin/users/login" action="store">

                        <div class="text-center mb-4">
                            <img :src="urlGenerator(configData.company_logo)" alt=""
                                 class="img-fluid logo">
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 px-0">
                                <h6 class="text-center mb-0">{{ $t('hi_there') }}</h6>
                                <label class="text-center d-block">{{ $t('log_in_to_your_dashboard') }}</label>
                            </div>
                        </div>
                        <div class="form-row"  v-if="demoCredentials">
                            <div class="form-group col-12 px-0">
                                <label for="login_as">{{ $t('login_as') }}</label>
                                <app-input
                                        type="select"
                                        v-model="loginRole"
                                        :label="$t('login_as')"
                                        list-value-field="name"
                                        :list="selectableRole"
                                />
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 px-0">
                                <label for="login_email">{{ $t('email') }}</label>
                                <app-input type="email"
                                           v-model="login.email"
                                           :placeholder="$t('enter_your_email')"
                                           :required="true"/>
                            </div>
                        </div>
                        <div class="form-row position-relative mb-4">
                            <div class="form-group col-12 px-0">
                                <label for="login_password">{{ $t('password') }}</label>
                                <app-input type="password"
                                           v-model="login.password"
                                           :placeholder="$t('enter_your_password')"
                                           :required="true"
                                           :show-password="true"
                                />
                                <a :href="urlGenerator('/forget-password')" class="forgot-password bluish-text text-right mt-2 d-inline-block position-absolute">
                                    <app-icon name="lock" class="pr-2 size-18"/>
                                    <small>{{ $t('forgot_password') }}</small>
                                </a>
                            </div>
                        </div>
                        <!-- <div class="form-row form-row flex-column flex-md-row justify-content-center justify-content-md-between justify-content-lg-between"> -->
                        <!--     <a :href="urlGenerator('/forget-password')" -->
                        <!--        class="bluish-text d-flex align-items-center justify-content-center justify-content-lg-end"> -->
                        <!--         <app-icon name="lock" class="pr-2"/> -->
                        <!--         {{ $t('forgot_password') }} -->
                        <!--     </a> -->
                        <!-- </div> -->

                        <div class="form-row">
                            <div class="form-group col-12 px-0 mb-3">
                                <app-load-more
                                        :preloader="preloader"
                                        :label="$t('login')"
                                        type="submit"
                                        class-name="btn btn-primary btn-block text-center"
                                        @submit="submit"/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-12 px-0">
                                <app-load-more
                                        :preloader="createAccountPreloader"
                                        :label="$t('create_new_account')"
                                        type="submit"
                                        class-name="btn btn-success btn-block text-center"
                                        @submit="register"/>
                            </div>
                        </div>

                        <div class="text-center">
                            <a :href="urlGenerator('/')"
                               class="bluish-text text-center">
                                {{ $t('go_to_career_page') }}
                            </a>
                        </div>
                        <div class="form-row">
                            <div class="col-12">
                                <p class="text-center mt-5">
                                    {{
                                        $t('copyright_text') + ' ' + year + ' ' + $t('by') + ' ' + configData.company_name
                                    }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ThemeMixin from "../../../../core/mixins/global/ThemeMixin";
import {AuthMixin} from "./Mixins/AuthMixin";
import {urlGenerator} from "../../../Helpers/AxiosHelper";

export default {
    name: "Login",
    mixins: [AuthMixin, ThemeMixin],
    components: {},
    props: {
        demo: {}
    },
    data() {
        return {
            urlGenerator,
            year: moment(moment.now()).format("YYYY"),
            login: {
                email: '',
                password: ''
            },
            loginRole: '',
        };
    },
    computed: {
        demoCredentials() {
            return this.demo ? JSON.parse(this.demo) : '';
        },
        selectableRole() {
            return [{id: '', name: this.$t('select_role')}]
                .concat(Object.keys(this.demoCredentials).map((item) => {
                    return {
                        id: item,
                        name: this.demoCredentials[item].role,
                    }
                }))
        },
        loginRoleUpdate() {
            return this.loginRole
        }
    },
    watch: {
        loginRoleUpdate: {
            handler: function (role) {
                this.login.password = role ? this.demoCredentials[role].password : '';
                this.login.email = role ? this.demoCredentials[role].email : '';
            }
        }
    },
    methods: {
        submit() {
            this.save(this.login);
        },
        register() {
            this.createAccountPreloader = true;

            const query = new URLSearchParams(window.location.search);
            const redirect = query.get('redirect');

            const path = redirect
                ? `/register?redirect=${encodeURIComponent(redirect)}`
                : '/register';

            window.location = urlGenerator(path);
        },
        afterSuccess(res) {
            window.location = res.data;
        }
    }
}
</script>

<style lang="scss" scoped>
.forgot-password {
    right: 0;
}
</style>
