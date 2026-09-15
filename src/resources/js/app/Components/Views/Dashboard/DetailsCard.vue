<template>
    <div class="details-card p-4 row mx-0 flex-wrap text-left border-rounded">
        <div :class="`col-12 col-md-${activePreview === 'mobile' ? 12 : 6 }`">
            <div class="detail mb-3">
                <span class="text-secondary" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing};`">
                    <app-icon name="briefcase" class="size-16"/>
                    {{ $t('job_type') }}:
                </span>
                <span
                    :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">{{
                        data.job_type.name
                    }}
                </span>
            </div>
            <div :class="`detail ${activePreview === 'mobile' ? 'mb-3' : ''}`">
                <span class="text-secondary" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing};`">
                    <app-icon name="user" class="size-16"/>
                    {{ $t('vacancy') }}:
                </span>
                <span
                    :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">{{
                        data.vacancy_count
                    }}
                </span>
            </div>
        </div>
        <div :class="`col-12 col-md-${activePreview === 'mobile' ? 12 : 6 }`" >
            <div class="detail mb-3">
                <span class="text-secondary" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing};`">
                    <app-icon name="folder" class="size-16"/>
                    {{ $t('department') }}:
                </span>
                <span :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">
                    {{ data.department.name }}
                </span>
            </div>
            <div :class="`detail ${activePreview === 'mobile' ? 'mb-3' : ''}`" v-if="Boolean(+data.is_viewable)">
                <span class="text-secondary" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing};`">
                    <app-icon name="credit-card" class="size-16"/>
                    {{ $t('salary') }}:
                </span>
                <span
                    :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">{{
                        data.salary
                    }}</span>
            </div>
        </div>

            <div class="detail mt-3" :class="`col-12 col-md-${activePreview === 'mobile' ? 12 : 6 }`">
                <span class="text-secondary" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing};`">
                    <app-icon name="cpu" class="size-16"/>
                    {{ $t('working_arrangement') }}:
                </span>
                <span :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">
                    {{ $t(data.working_arrangement) }}
                </span>
            </div>
            <div class="`detail mt-3" v-if="Boolean(+data.is_viewable)" :class="`col-12 col-md-${activePreview === 'mobile' ? 12 : 6 }`">
                <span class="text-secondary" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing};`">
                    <app-icon name="briefcase" class="size-16"/>
                    {{ $t('experience_level') }}:
                </span>
                <span
                    :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">{{
                        $t(data.experience_level.name)
                    }}</span>
            </div>

        <!-- <pre>{{ data }}</pre> -->
        <!--                            <p :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">-->
        <!--                                {{ content.details }}-->
        <!--                            </p>-->

        <!--                            <p v-if="data.vacancy_count"-->
        <!--                               :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">-->
        <!--                                {{ $t('vacancy') }} - {{ data.vacancy_count }}-->
        <!--                            </p>-->

        <!--                            <p v-if="Boolean(data.department)" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">-->
        <!--                                {{ $t('department') }} - {{ data.department.name }}-->
        <!--                            </p>-->

        <!--                            <p v-if="Boolean(data.is_viewable)" :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">-->
        <!--                                {{ $t('salary') }} - {{ data.salary }}-->
        <!--                            </p>-->
    </div>
</template>

<script>
export default {
    props: {
        content: {
            type: Object,
            required: true
        },
        detailsStyle: {
            type: Object,
            required: true
        },
        data: {
            type: Object,
            required: true
        },
        activePreview: {
            type: String,
            required: true
        }
    }
}
</script>

<style lang="scss">
.details-card {
    background-color: var(--icon-hover-bg);
}
</style>
