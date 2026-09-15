<template>
	<div class="job-search-input-wrapper mx-auto">
		<div
			class="job-search-input bg-white px-4 py-4 shadow border w-100" :class="{'abs-positioning': enableCustomPositioning }">
			<p class="d-sm-block mb-3 text-center display-4">{{ $t('search_your_dream_job') }}</p>
			<div class="form-group form-group-with-search d-flex align-items-center position-relative">
				<span :key="'search'" class="form-control-feedback">
					<app-icon name="search" />
				</span>
				<input type="text" class="form-control top search-input" name="search" v-model="searchTerm" :readonly="readonly" :placeholder="$t('search')"
					@keyup="handleChange" @keyup.enter="handleEnterPress" />
                <span :key="'x'" class="to-right cursor-pointer" v-if="showClearBtn" @click="handleClearSearchTermClick">
					<app-icon name="x" />
				</span>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: "Search",
	props: {
		readonly: {
			type: Boolean,
			default: true
		},
		enableCustomPositioning: {
			type: Boolean,
			default: false
		}
	},
	data() {
        return {
            searchTerm: '',
            showClearBtn: false
        }
	},
	methods: {
		handleChange(e) {
			this.$emit('input', e.target.value);
		},
        handleEnterPress(e) {
            this.showClearBtn = true;
            this.$emit('enterpress', e.target.value)
        },
        handleClearSearchTermClick() {
            this.searchTerm = '';
            this.showClearBtn = false
            this.$emit('search-term-clear')
        }
	},
}
</script>

<style lang="scss" scoped>
.job-search-input-wrapper {
	position: relative;
}



.job-search-input {
	margin: 0 auto;
	border-radius: 1.25rem;

	p {
		font-size: 16px;
	}

	input {
		padding: 0.5rem 1rem;
		border-radius: 2rem;
	}
}

.abs-positioning {
	z-index: 2;
	position: absolute;
	top: 0%;
	left: 50%;
	transform: translate(-50%, -50%);
}

.to-right {
    display: block;
    position: absolute;
    right: 0.75rem;
}
</style>
