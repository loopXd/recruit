<template>
	<div class="dropdown d-block d-sm-none">
  		<span class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    		<app-icon name="menu" class="size-20" />
  		</span>
  		<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
            <!-- <a class="dropdown-item" href="#jobs"> -->
            <!-- 	<app-icon name="file-text" class="size-15 text-muted mr-2" /> -->
            <!-- 	{{ $t('about_us') }} -->
            <!-- </a> -->
            <!-- <a class="dropdown-item" href="#jobs"> -->
            <!-- 	<app-icon name="briefcase" class="size-15 text-muted mr-2" /> -->
            <!-- 	{{ $t('job_post') }} -->
            <!-- </a> -->
            <a class="dropdown-item" :href="urlGenerator('/admin/users/login ')">
            	<app-icon :name="`${loggedIn ? 'columns' : 'log-in' }`" class="size-15 text-muted mr-2" />
				<span>
            		{{ loggedIn ? $t('dashboard') : $t('login')}}
				</span>
            </a>
  		</div>
	</div>	
</template>

<script>
import { urlGenerator } from '../../../Helpers/AxiosHelper';
export default {
	name:"HeroDropdown",
	data() {
		return {
			urlGenerator
		}
	},
	props: {
		loggedIn: {
			required: true,
			type: Boolean
		}
	}
}
</script>

<style lang="scss" scoped>

</style>
