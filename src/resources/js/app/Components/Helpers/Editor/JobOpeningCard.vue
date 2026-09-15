<template>
    <div class="job-opening-card bg-white p-4 cursor-pointer h-100 d-flex flex-column justify-content-between" @click="handleCardClick">
        <div :class="`opening-card-header d-flex align-items-${job.department ? 'center' : 'start'}`">
            <div class="icon bg-primary">
                <span class="text-white">{{ job.title[0].toUpperCase() }}</span>
            </div>
            <div>
                <p class="lead job-title mb-0">{{job.title | truncate}}</p>
                <small class="job-department">{{ job.location | truncate }}</small>
            </div>
        </div>
        <div>
            <div class="badge-wrapper mt-4 mb-5">
            	<span class="custom-badge d-inline-block mr-1 mb-2">
                	{{ job.type }}
            	</span>
                <span  v-if="job.salary && Boolean(+job.is_viewable)" class="custom-badge d-inline-block mr-1">
                	{{ numberFormatter(job.salary) }}
            	</span>
                <span v-if="job.department" class="custom-badge d-inline-block mr-1 ">
                	{{ job.department }}
            	</span>
                <span  v-if="job.experience_level" class="custom-badge d-inline-block mr-1 ">
                	{{ job.experience_level.name }}
            	</span>
                <span class="custom-badge d-inline-block mr-1">
                	{{ ucFirst($t(job.working_arrangement)) }}
            	</span>
            </div>

            <div class="location-wrapper d-flex">
                <app-icon name="user" class="d-inline-block mr-2 text-primary size-17" />
                <p class="location-text">
                    <span class="text-muted">{{ $t('number_of_vacancies') }}: </span> {{ job.vacancy_count }}
                </p>
            </div>
            <div class="location-wrapper d-flex">
                <app-icon name="calendar" class="d-inline-block mr-2 text-primary size-17" />
                <p class="location-text">
                    <span class="text-muted">{{ $t('last_submission_date') }}: </span> {{ formatDateToLocal(job['last_submission_date']) }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import {formatDateToLocal} from "../../../Helpers/DateTimeHelper";
import {ucFirst} from "../../../Helpers/TextHelper";
import {numberFormatter} from "../../../Helpers/Helpers";
export default {
	props: ['job'],
    filters: {
        truncate(value) {
            if (!value) return;
            const limit = 40;
            if (value.length < limit) return value;
            return value.substring(0, limit) + '...'
        }
    },
    data() {
       return {
           formatDateToLocal,
           ucFirst,
           numberFormatter
       }
    },
    methods: {
		handleCardClick() {
			window.location.assign(this.job.url);
		}
    }
}
</script>

<style lang="scss" scoped>

</style>
