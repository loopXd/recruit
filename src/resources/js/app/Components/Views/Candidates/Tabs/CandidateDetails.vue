<template>
    <div class="table-wrapper">
    	<table v-if="!basicInformation" class="table table-card mb-4 candidates-details-table">
        	<tbody>
        		<tr>
            		<td class="text-muted width-150">{{ $t('name') }}</td>
            		<td>{{ applicant.full_name }}</td>
        		</tr>
        		<tr>
            		<td class="text-muted width-150">{{ $t('email') }}</td>
            		<td>{{ applicant.email }}</td>
        		</tr>
        		<tr>
            		<td class="text-muted width-150">{{ $t('gender') }}</td>
            		<td class="text-capitalize">{{ applicant.gender }}</td>
        		</tr>
        		<tr v-if="applicant.phone">
            		<td class="text-muted width-150">{{ $t('phone') }}</td>
            		<td class="text-capitalize">{{ applicant.phone }}</td>
        		</tr>
        		<tr v-if="applicant.date_of_birth">
            		<td class="text-muted width-150">{{ $t('date_of_birth') }}</td>
            		<td>{{ formatDateToLocal(applicant.date_of_birth) }}</td>
        		</tr>
        	</tbody>
    	</table>
		<template v-else>
			<table class="table table-card mb-4 candidates-details-table" v-for="(item, index) in basicInformation.items" :key="`basic_information-${index}`">
				<tbody>
					<tr v-for="(field, fieldIndex) in item.fields" :key="`field-${index}-${fieldIndex}`">
						<td class="text-muted width-150">{{ field.title }}</td>
						<td>{{ field.type === 'date' ? formatDateToLocal(field.value) : field.value }}</td>
					</tr>
				</tbody>
			</table>
		</template>
		<question-answer
            :apply-form="applyForm"
            :answers="answers"
        />
    </div>
</template>

<script>
import {formatDateToLocal} from "../../../../Helpers/Helpers";

export default {
    name: "CandidateDetails",
    props: {
        applicant: {},
        applyForm: {},
        answers: {},
		basicInformation: {}
    },
    data() {
        return {
            formatDateToLocal
        }
    }
}
</script>

<style class="scope">
html[theme="dark"] table {
  color: #afb1b6 !important;
  margin-bottom: 1rem;
  width: 100%;
}

html[theme="light"] table {
  color: #212529 !important;
  margin-bottom: 1rem;
  width: 100%;
}

html[theme="dark"] table label {
  color: #afb1b6 !important;
}

html[theme="light"] table label {
  color: #212529 !important;
}
</style>