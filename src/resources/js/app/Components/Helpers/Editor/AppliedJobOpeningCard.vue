<template>
    <div class="applied-job-opening-card bg-white p-3 d-flex flex-column justify-content-between">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="opening-card-header d-flex">
                <div class="icon bg-primary">
                    <span class="text-white">{{ job.title[0].toUpperCase() }}</span>
                </div>
                <p class="lead job-title mb-0">{{job.title | truncate}}</p>
            </div>
            <div class="badge-wrapper">
            	<span class="custom-badge d-inline-block mr-1">
                	{{ job.type }}
            	</span>
            </div>
        </div>
        <div class="location-wrapper d-flex">
            <app-icon name="map-pin" class="d-inline-block mr-2 size-15 text-muted" />
            <p class="location-text mb-1">
                {{ job.location }}
            </p>
        </div>
        <div class="location-wrapper d-flex">
            <app-icon name="calendar" class="d-inline-block mr-2 size-15 text-muted" />
            <p class="location-text mb-1">
                {{ job.time }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['job'],
    filters: {
        truncate(value) {
            const limit = 35;
            if (value.length < limit) return value;
            return value.substring(0, limit) + '...'
        }
    },
    methods: {
    }
}
</script>

<style lang="scss" scoped>
.applied-job-opening-card {
  box-shadow: 1px 1px 20px rgba(0, 0, 0, 0.05);
  border-radius: 0.25rem;
  font-weight: 600;
  transition: 250ms;

  &:hover {
    box-shadow: 1px 1px 20px rgba(0, 0, 0, 0.09);
  }

  .opening-card-header {
    gap: 0.65rem;

    .job-title {
      font-size: 17px;
      color: #585858;
    }
  }

  .icon {
    position: relative;
    min-width: 30px;
    min-height: 30px;
    max-height: 30px;
    border-radius: 9px;

    span {
      position: absolute;
      font-size: 1.25rem;
      color: #585858;
      top: 50%;
      left: 50%;
      transform: translate(-50% , -50%);
    }
  }
  .custom-badge {
    background-color: #EDF0FF;
    color: #636363;
    border-radius: 4px;
    font-size: 12px;
    word-wrap: break-word;
    white-space: nowrap;
    padding: 0.15rem 0.75rem;
  }
  .location-text {
    color: #888888;
    font-size: 12px;
  }
}

</style>
