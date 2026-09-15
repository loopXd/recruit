<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <experience-level-add-edit-modal
            v-if="isExperienceLevelAddEditModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @closeModal="closeExperienceLevelAddEditModal"
        />

        <app-delete-modal
            v-if="isExperienceLevelDeleteModalActive"
            :modal-id="deleteModalId"
            :preloader="deleteLoader"
            @confirmed="confirmDelete"
            @cancelled="cancelDelete"
        />
    </div>
</template>

<script>
import ExperienceLevelTableMixin from "./ExperienceLevelTableMixin";

export default {
    name: 'ExperienceLevels',
    mixins: [ExperienceLevelTableMixin]
}
</script>