<template>
    <div class="tm_destinations_wrapper">

        <AppModal
            :title="'Add New Activities'"
            :width="800"
            :showFooter="false"
            ref="add_activities_modal">
            <template #body>
                <AddActivities  />
            </template>
        </AppModal>

        <AppTable :tableData="activities" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Activities</h1>
                <el-button @click="openActivitiesAddModal" size="large" type="primary" icon="Plus">Add New Activities</el-button>
            </template>

             <template #filter>
                <el-input @change="getActivities" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="40" />
                <el-table-column prop="trip_activity_name" label="activity Name" width="auto" />
                <el-table-column prop="trip_activity_slug" label="Slug" width="auto" />
                <el-table-column prop="trip_activity_desc" label="Description" width="420" />
                <el-table-column label="Image" width="auto">
                    <template #default="{ row }">
                        <img v-if="row.images?.url" :src="row.images?.url" alt="image" style="width: 60px; height: 60px; object-fit: cover;">
                        <span v-else>No Image</span>
                    </template>
                </el-table-column>
                <el-table-column label="Operations" width="120">
                    <template #default="{ row }">
                        <el-tooltip class="box-item" effect="dark" content="Click to edit destinations" placement="top-start">
                            <el-button @click="openUpdateDestinationModal(row)" link type="primary" size="small">
                                <Icon icon="tm-edit" />
                            </el-button>
                        </el-tooltip>
                        <el-tooltip class="box-item" effect="dark" content="Click to delete destinations" placement="top-start">
                            <el-button @click="openDeleteDestinationModal(row)" link type="primary" size="small">
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
                    :disabled="total_activities <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_activities"
                    @size-change="getActivities"
                    @current-change="getActivities"
                />
            </template>

        </AppTable>

    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddActivities from "@/pages/page_trips/activities/AddActivities.vue"
import Icon from "@/components/Icons/AppIcon.vue"

export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddActivities,
        Icon
    },
    data() {
        return {
            search: '',
            activities: [],
            activity: {},
            total_activities: 0,
            loading: false,
            add_activities_modal: false,
            currentPage: 1,
            pageSize: 10,
            active_id: null
        }
    },

    methods: {
           getActivities() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_activities",
                    route: "get_activities",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.activities = response?.data?.data?.activities;
                    that.total_activities = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
      
        openActivitiesAddModal() {
            this.$refs.add_activities_modal.openModel();
        },
    },
       created() {
        this.getActivities();
    },
  
}
</script>