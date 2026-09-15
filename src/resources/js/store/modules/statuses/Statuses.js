import {axiosGet} from "../../../app/Helpers/AxiosHelper";
import {GET_STATUSES} from "../../../app/Config/ApiUrl";

export default {

    state: {
        statuses: [],
    },

    getters: {
        statuses: state => state.statuses,
        statusListForUser: state => state.statuses.filter(item => item.type === 'user'),
        statusListForJobApplicant: state => state.statuses.filter(item => item.type === 'job_applicant'),
        statusListForJobPost: state => state.statuses.filter(item => item.type === 'job_post')
            .map(e => {
                return {
                    id: e.id,
                    name: e.name,
                    translated_name: e.name === 'status_open' ? Vue.i18n.translate('active_jobs') : (e.name === 'status_closed' ? Vue.i18n.translate('inactive_jobs') :  Vue.i18n.translate('draft_jobs'))
                }
            }),
        statusListForTodo: state => state.statuses.filter(item => item.type === 'todo')
    },

    mutations: {
        SET_STATUS_LIST(state, data) {
            state.statuses = data;
        }

    },

    actions: {
        getStatuses({commit, rootState}, payload = '') {
            rootState.loader.loader = true;
            axiosGet(`${GET_STATUSES}?type=${payload}`).then((res) => {
                commit('SET_STATUS_LIST', res.data);
                rootState.loader.loader = false;
            })
        }
    },
}
