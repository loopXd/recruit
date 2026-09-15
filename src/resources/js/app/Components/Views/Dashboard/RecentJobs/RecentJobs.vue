<template>
    <div class="events-wrapper">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">
                {{ $t('last_applied_jobs') }}
            </h5>
            <a :href="getAppUrl('/admin/my-story')" class="btn btn-sm btn-primary px-3">
                {{ $t('view_all') }}
            </a>
        </div>

        <div class="card border-0 shadow" v-if="preloader">
            <div class="card-body min-height-300">
                <app-overlay-loader/>
            </div>
        </div>
        <AppliedJobOpeningCard  :job="{
            title: lastAppliedJob.job_post.name,
            type:  lastAppliedJob.job_post.job_type.name,
            location: lastAppliedJob.job_post.location ? lastAppliedJob.job_post.location : 'N/A',
            url: 'google.com',
            time: formatDateTimeDateTime(lastAppliedJob.job_post.created_at)
        }" v-else />
    </div>
</template>

<script>
import CoreLibrary from '../../../../../core/helpers/CoreLibrary';
import {
    formatDateTimeDateTime,
    formatDayFromDateTime,
    formatMonthNameFromDateTime,
} from "../../../../Helpers/DateTimeHelper";
import AppliedJobOpeningCard from "../../../Helpers/Editor/AppliedJobOpeningCard.vue";

export default {
    name: 'RecentJobs',
    methods: {formatDateTimeDateTime},
    extends: CoreLibrary,
    components: {AppliedJobOpeningCard},
    props: {
        lastAppliedJob: {}
    },
    data() {
        return {
            formatDayFromDateTime,
            formatMonthNameFromDateTime,
            preloader: false,

        }
    },
    mounted() {
        this.initializeTooltip();
    },
}
</script>