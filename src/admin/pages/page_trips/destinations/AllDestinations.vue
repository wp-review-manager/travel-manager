vue
Copy code
<template>
    <div class="tm_destinations_wrapper">
        <AppModal
            :title="'Add New Destinations'"
            :width="800"
            :showFooter="false"
            ref="add_destination_modal">
            <template #body>
                <AddDestinations @updateDataAfterNewAdd="updateDataAfterNewAdd" />
            </template>
        </AppModal>

        <AppTable :tableData="destinations" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Destinations</h1>
                <el-button @click="openDestinationAddModal" size="large" type="primary" icon="Plus">Add New Destination</el-button>
            </template>
            <template #filter>
                <el-input @change="getDestinations" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="40" />
                <el-table-column prop="place_name" label="Place Name" width="auto" />
                <el-table-column prop="place_slug" label="Slug" width="auto" />
                <el-table-column prop="place_desc" label="Description" width="420" />
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
                            <el-button link type="primary" size="small">
                                <Icon icon="tm-delete" />
                            </el-button>
                        </el-tooltip>
                    </template>
                </el-table-column>
            </template>
            <template #footer>
                <h1>pagination</h1>
                <el-pagination
                    v-model:current-page="currentPage"
                    v-model:page-size="pageSize"
                    :page-sizes="[10, 20, 30, 40]"
                    large
                    :disabled="total_destinations <= pageSize"
                    background
                    layout="total, sizes, prev, pager, next"
                    :total="+total_destinations"
                    @size-change="getDestinations"
                    @current-change="getDestinations"
                />
            </template>
        </AppTable>
        <AppModal
            :title="'Update Destinations'"
            :width="800"
            :showFooter="false"
            ref="update_destination_modal">
            <template #body>
                <AddDestinations :destination_data="destination" />
            </template>
        </AppModal>
    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddDestinations from "@/pages/page_trips/destinations/AddDestinations.vue"
import Icon from "@/components/Icons/AppIcon.vue"

export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddDestinations,
        Icon
    },
    data() {
        return {
            search: '',
            destinations: [],
            destination: {},
            total_destinations: 0,
            loading: false,
            add_destination_modal: false,
            currentPage: 1,
            pageSize: 10,
        }
    },

    methods: {
        getDestinations() {
            this.loading = true;
            let that = this;
            jQuery
                .post(ajaxurl, {
                    action: "tm_destinations",
                    route: "get_destinations",
                    per_page: this.pageSize,
                    page: this.currentPage,
                    search: that.search,
                    tm_admin_nonce: window.wpTravelManager.tm_admin_nonce,
                }).then((response) => {
                    that.destinations = response?.data?.data?.destinations;
                    that.total_destinations = response?.data?.data?.total;
                }).fail((error) => {
                    console.log(error);
                }).always(() => {
                    that.loading = false;
                })
        },
        openDestinationAddModal() {
            this.$refs.add_destination_modal.openModel();
        },
        openUpdateDestinationModal(row) {
            this.destination = row;
            this.$refs.update_destination_modal.openModel();
        },
        updateDataAfterNewAdd(new_destination) {
            this.destinations.unshift(new_destination);
        }
    },
    created() {
        this.getDestinations();
    },
}
</script>