import {mapGetters} from "vuex";

export default {
    created() {
        this.$store.dispatch('getStatuses');
    },
    computed: {
        ...mapGetters([
            'statusListForJobPost'
        ])
    },
    watch: {
        statusListForJobPost: {
            handler: function (data) {
                if (!this.isCandidate){
                    let filter = this.options.filters.find(item => item.key === 'status');
                    if(!filter) return;
                    filter.option = data;
                    filter.active = data.find(i => i.name === 'status_open').id;
                }
            }
        }
    }
}