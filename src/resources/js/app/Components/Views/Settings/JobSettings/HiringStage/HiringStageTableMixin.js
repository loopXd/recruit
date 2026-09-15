import DeleteMixin from "../../../../../Mixins/DeleteMixin";
import {STAGE} from "../../../../../Config/ApiUrl";

export default {
    mixins: [DeleteMixin],
    props: ['id', 'props'],
    data() {
        return {
            selectedUrl: '',
            tableId: 'hiring-stage',
            deleteModalId: 'hiring-stage-delete-modal',
            rowData: {},
            options: {
                url: STAGE,
                name: 'Hiring Stage',
                datatableWrapper: false,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'custom-html',
                        key: 'name',
                        modifier: value => `<span class="text-capitalize">${ 
                            this.predefinedStages.includes(value.toLowerCase()) ? this.$t(value) : value 
                        }</span>`
                    }
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        key: 'edit',
                        modifier: data => {
                            if (!this.$have('PERMISSION_UPDATE_STAGE')) return false
                            return !this.predefinedStages.includes(data.name)
                        }
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        key: 'delete',
                        modifier: data => {
                            if (!this.$have('PERMISSION_DELETE_STAGE')) return false
                            return !this.predefinedStages.includes(data.name)
                        }
                    }
                ],
                showAction: (this.$have('PERMISSION_UPDATE_STAGE') || this.$have('PERMISSION_DELETE_STAGE')),
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
            predefinedStages: ['new', 'hired', 'disqualified'],
            isStageAddEditModalActive: false,
            isStageDeleteModalActive: false,
        }
    },
    beforeMount() {
        if (this.$have('PERMISSION_UPDATE_STAGE') || this.$have('PERMISSION_DELETE_STAGE')) {
            this.options.columns.push({
                title: this.$t('actions'),
                type: 'action'
            })
        }
    },
    mounted() {
        this.addStage();
    },
    methods: {
        addStage() {
            this.$hub.$on(`headerButtonClicked-${this.id}`, () => {
                this.openStageAddEditModal();
            })
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;

            if (actionObj.key === 'edit') {
                this.openStageAddEditModal();
                this.selectedUrl = `${STAGE}/${rowData.id}`;
            } else if (actionObj.key === 'delete') {
                this.openStageDeleteModal();
            }
        },
        openStageAddEditModal() {
            this.isStageAddEditModalActive = true;
        },
        closeStageAddEditModal() {
            this.selectedUrl = '';
            this.isStageAddEditModalActive = false;
        },
        openStageDeleteModal() {
            this.isStageDeleteModalActive = true;
        },
        confirmDelete() {
            this.deleteAndReload(`${STAGE}/${this.rowData.id}`);
        },
        cancelDelete() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isStageDeleteModalActive = false
        }
    }
}
