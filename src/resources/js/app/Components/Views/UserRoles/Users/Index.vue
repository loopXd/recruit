<template>
    <div class="card card-with-shadow border-0 pb-primary">
        <div class="card-header d-flex align-items-center p-primary primary-card-color">
            <h5 class="card-title d-inline-block mb-0">{{ $t('users') }}</h5>
            <div class="d-flex ml-auto align-items-center">
                <app-search @input="getSearchValue" />
                <input type="text" v-model="city" @input="applyCityFilter" :placeholder="$t('city')"
                       class="form-control form-control-sm ml-2" style="width:200px;" />
            </div>
        </div>
        <div class="p-primary d-flex align-items-center primary-card-color">
            <ul class="nav tab-filter-menu justify-content-flex-end">
                <li class="nav-item" v-for="(item, index) in userFilterOptions" :key="index">
                    <a href="#" class="nav-link py-0 font-size-default"
                        :class="[value == item.id ? 'active' : index === 0 && value === '' ? 'active' : '']"
                        @click="getFilterValue(item.id)">
                        {{ item.translated_name }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body px-primary">
            <app-table :id="data.tableId" class="remove-datatable-x-padding" :options="userTableOptions"
                :filtered-data="filteredData" :search="search" @action="action" />
        </div>
    </div>
</template>

<script>
import { TableMixin } from '../Mixins/TableMixin';
import * as actions from '../../../../Config/ApiUrl';

export default {
    name: "User",
    mixins: [TableMixin],
    data() {
        return {
            city: '',
            value: '',
            filteredData: {},
            userTableOptions: {
                name: 'Users',
                url: actions.USERS,
                tablePaddingClass: 'pt-0',
                datatableWrapper: false,
                showHeader: false,
                tableShadow: false,
                columns: [
                    {
                        title: this.$t('user'),
                        type: 'media-object',
                        key: 'profile_picture',
                        mediaTitleKey: 'full_name',
                        mediaSubtitleKey: 'email',
                        default: "",
                        isVisible: true,
                        modifier: (value, row) => {
                            return value ? value.full_url : '';
                        }
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        isVisible: true,
                        modifier: (value) => {
                            return `<span class="badge badge-sm badge-pill badge-${value.class}">${value.translated_name}</span>`;
                        }
                    },
                    {
                        title: this.$t('action'),
                        type: 'action',
                        key: 'invoice',
                        isVisible: true
                    },
                ],
                showSearch: false,
                showFilter: false,
                paginationType: 'pagination',
                responsive: true,
                rowLimit: 10,
                showAction: true,
                orderBy: 'desc',
                actionType: 'dropdown',
                actions: [
                    {
                        title: this.$t('edit'),
                        type: 'none',
                        modifier: () => this.$have('PERMISSION_UPDATE_USERS')
                    },
                    {
                        title: this.$t('delete'),
                        type: 'none',
                        modifier: (row) => {
                            if (!this.$have('PERMISSION_DELETE_USERS')) return false
                            const { status } = row;
                            return status.name !== 'status_invited';
                        }
                    },
                    {
                        title: this.$t('cancel_invitation'),
                        type: 'none',
                        modifier: (row) => {
                            if (!this.$have('PERMISSION_UPDATE_USERS')) return false
                            const { status } = row;
                            return status.name === 'status_invited';
                        }
                    },
                    {
                        title: this.$t('active'),
                        type: 'none',
                        modifier: (row) => {
                            if (!this.$have('PERMISSION_UPDATE_USERS')) return false
                            const { status } = row;
                            return status.name !== 'status_invited' && status.name !== 'status_active';
                        }
                    },
                    {
                        title: this.$t('de_activate'),
                        type: 'none',
                        modifier: (row) => {
                            if (!this.$have('PERMISSION_UPDATE_USERS')) return false
                            const { status } = row;
                            return status.name !== 'status_invited' && status.name !== 'status_inactive';
                        }
                    }
                ],
            },
            userFilterOptions: [
                { id: '', name: 'all_users', translated_name: 'Todos' },
            ],
        }
    },
    mounted() {
        this.getStatuses();
    },
    methods: {
        getFilterValue(item) {
            this.value = item;
            this.filteredData['status-id'] = item;
            this.$hub.$emit('reload-' + this.data.tableId);
        },
        getStatuses() {
            let url = `${actions.GET_STATUSES}?type=user`;

            this.axiosGet(url).then(response => {
                this.userFilterOptions = [...this.userFilterOptions, ...response.data];
            });
        }
        ,
        applyCityFilter() {
            if (this.city && this.city.length) this.filteredData['city'] = this.city;
            else delete this.filteredData['city'];
            this.$hub.$emit('reload-' + this.data.tableId);
        }
    }
}
</script>
