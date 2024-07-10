<template>
    <div class="tm_destinations_wrapper">
        <AppModal
            :title="'Add New Difficulty'"
            :width="800"
            :showFooter="false"
            ref="add_difficulty_modal">
            <template #body>
                <AddDifficulty @updateDataAfterNewAdd="updateDataAfterNewAdd" />
            </template>
        </AppModal>

        <AppTable :tableData="difficulty" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Difficulty</h1>
                <el-button @click="openDifficultyAddModal" size="large" type="primary" icon="Plus">Add New Difficulty</el-button>
            </template>
            <template #filter>
                <el-input @change="getDifficulty" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="40" />
                <el-table-column prop="trip_defaulty_name" label="Difficulty Name" width="auto" />
                <el-table-column prop="trip_defaulty_slug" label="Slug" width="auto" />
                <el-table-column prop="trip_defaulty_desc" label="Description" width="420" />
                <el-table-column label="Image" width="auto">
                    <template #default="{ row }">
                        <img v-if="row.images?.url" :src="row.images?.url" alt="image" style="width: 60px; height: 60px; object-fit: cover;">
                        <span v-else>No Image</span>
                    </template>
                </el-table-column>
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit difficulty" placement="top-start">
                            <el-button @click="openUpdateDifficultyModal(row)" link type="primary" size="small">
                                <Icon icon="tm-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete difficulty" placement="top-start">
                            <el-button @click="openDeleteDifficultyModal(row)" link type="primary" size="small">
                                <Icon icon="tm-delete" />
                            </el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </template>
            <template #footer>
                <el-pagination
                    v-model:current-page="currentPage"
                    v-model:page-size="pageSize"
                    :page-sizes="[10, 20, 30, 40]"
                    large
                    :disabled="total_difficulty <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_difficulty"
                    @size-change="getDifficulty"
                    @current-change="getDifficulty"
                />
            </template>

        </AppTable>

        <AppModal
            :title="'Update Difficulty'"
            :width="800"
            :showFooter="false"
            ref="update_difficulty_modal">
            <template #body>
                <AddDifficulty :difficulty_data="difficult" />
            </template>
        </AppModal>

        <AppModal
            :title="'Delete Difficulty'"
            :width="400"
            :showFooter="false"
            ref="delete_difficulty_modal">
            <template #body>
                <div class="delete-modal-body">
                    <h1>Are you sure ?</h1>
                    <p>You want to delete this difficulty</p>
                </div>
            </template>
            <template #footer>
                <div class="tm-modal-footer">
                    <el-button @click="$refs.delete_difficulty_modal.handleClose()" type="default" size="medium">Cancel</el-button>
                    <el-button @click="deleteDifficulty" type="primary" size="medium">Delete</el-button>
                </div>
            </template>
        </AppModal>
        
      
    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddDifficulty from "@/pages/page_trips/difficulty/AddDifficulty.vue"
import Icon from "@/components/Icons/AppIcon.vue"

export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddDifficulty,
        Icon
    },
    data() {
        return {
            search: '',
            difficulty: [],
            difficult: {},
            total_difficulty: 0,
            loading: false,
            add_difficulty_modal: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },
    methods: {
        getDifficulty() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_difficulty",
                    route: "get_difficulty",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.difficulty = response?.data?.data?.difficulty;
                    that.total_difficulty = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
        deleteDifficulty() {
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_difficulty",
                    route: "delete_difficulty",
                    id: that.active_id,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.getDifficulty();
                    that.$refs.delete_difficulty_modal.handleClose();
                }).fail((error) => {
                    console.log(error);
                })
        },
      
        openDifficultyAddModal() {
            this.$refs.add_difficulty_modal.openModel();
        },
        openDeleteDifficultyModal(row) {
            this.active_id = row.id;
            this.$refs.delete_difficulty_modal.openModel();
        },
        openUpdateDifficultyModal(row) {
            this.difficult = row;
            this.$refs.update_difficulty_modal.openModel();
        },
        updateDataAfterNewAdd(new_difficulty) {
            this.$refs.add_difficulty_modal.handleClose();
            this.difficult.unshift(new_difficulty);
        },
    },
    created() {
        this.getDifficulty();
    },
 
}
</script>