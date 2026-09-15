<template>
    <div class="events-wrapper">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">
                {{ $t('set_job_alert') }}
            </h5>
            <a class="cursor-pointer" @click.prevent="openJobAlertModal">
                <app-icon name="settings" class="primary-text-color mr-2"/>
            </a>
        </div>
        <job-alert-add-edit-modal
                v-if="isJobTypeAddEditModalActive"
                :work-arrangements="workArrangements"
                @closeModal="closeJobTypeAddEditModal"
        />
    </div>
</template>

<script>

import CoreLibrary from '../../../../../core/helpers/CoreLibrary';

export default {
    name: 'JobAlerts',
    extends: CoreLibrary,
    props: {
        workArrangements: {
            type: Array,
            required: true
        },
        isCandidate: {
            type: Boolean,
            required: true
        },
    },
    data() {
        return {
            preloader: true,
            isJobTypeAddEditModalActive:false,
        };
    },

    methods: {
        closeJobTypeAddEditModal() {
            this.selectedUrl = '';
            this.isJobTypeAddEditModalActive = false;
        },
        openJobAlertModal(){
            this.isJobTypeAddEditModalActive = true;
        }
    }
}
</script>