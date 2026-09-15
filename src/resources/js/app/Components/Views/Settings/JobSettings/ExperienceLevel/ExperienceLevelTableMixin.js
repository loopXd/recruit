import {EXPERIENCE_LEVEL} from "../../../../../Config/ApiUrl";
import DeleteMixin from "../../../../../Mixins/DeleteMixin";

export default {
    mixins: [DeleteMixin],
    props: ['id', 'props'],
    data() {
        return {
            selectedUrl: '',
            tableId: 'experience-level',
            deleteModalId: 'experience-level-delete-modal',
            rowData: {},
            options: {
                url: EXPERIENCE_LEVEL,
                name: 'Experience level',
                datatableWrapper: false,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name'
                    },
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        key: 'edit',
                        modifier: () => this.$have('PERMISSION_UPDATE_EXPERIENCE_LEVEL')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        key: 'delete',
                        modifier: () => this.$have('PERMISSION_DELETE_EXPERIENCE_LEVEL')
                    }
                ],
                showAction: (this.$have('PERMISSION_UPDATE_EXPERIENCE_LEVEL') || this.$have('PERMISSION_DELETE_EXPERIENCE_LEVEL')),
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
            isExperienceLevelAddEditModalActive: false,
            isExperienceLevelDeleteModalActive: false,
        }
    },
    beforeMount() {
        this.options.columns.push({
            title: this.$t('actions'),
            type: 'action'
        })
    },
    mounted() {
        this.addExperienceLevel();
    },
    methods: {
        addExperienceLevel() {
            this.$hub.$on(`headerButtonClicked-${this.id}`, () => {
                this.openExperienceLevelAddEditModal();
            })
        },

        getTableAction(rowData, actionObj) {
            this.rowData = rowData;

            if (actionObj.key === 'edit') {
                this.selectedUrl = `${EXPERIENCE_LEVEL}/${rowData.id}`
                this.openExperienceLevelAddEditModal();
            } else if (actionObj.key === 'delete') {
                this.openExperienceLevelDeleteModal();
            }
        },
        openExperienceLevelAddEditModal() {
            this.isExperienceLevelAddEditModalActive = true;
        },
        closeExperienceLevelAddEditModal() {
            this.selectedUrl = '';
            this.isExperienceLevelAddEditModalActive = false;
        },
        openExperienceLevelDeleteModal() {
            this.isExperienceLevelDeleteModalActive = true;
        },
        confirmDelete() {
            this.deleteAndReload(`${EXPERIENCE_LEVEL}/${this.rowData.id}`);
        },
        cancelDelete() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isExperienceLevelDeleteModalActive = false;
        }
    }
}