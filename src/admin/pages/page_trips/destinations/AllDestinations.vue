<template>
    <div class="tm_destinations_wrapper">
        <AppModal
            :title="'Add New Destinations'"
            :width="800"
            :showFooter="false"
            ref="add_destination_modal" >
            <template #body>
                <AddDestinations />
            </template>
        </AppModal>
        <AppTable :tableData="destinations" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Destinations</h1>
                <el-button @click="openDestinationAddModal" size="large" type="primary" icon="Plus">Add New Destination </el-button>
            </template>
            <template #filter>
                <el-input @change="getDestinations()" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
                <AppDatePicker />
            </template>
            <template #columns>
                <!-- <el-table-column prop="date" label="Date" width="220" /> -->
                <el-table-column prop="place_name" label="Place Name" width="220" />
                <el-table-column prop="place_slug" label="Slug" width="220" />
                <el-table-column prop="place_desc" label="Description" width="340" />
                <el-table-column label="Image" width="220" >
                    <template #default="{ row }">
                        <img v-if="row.images?.url" :src="row.images?.url" alt="image" style="width: 100px; height: 100px; object-fit: cover;">
                        <span v-else>No Image</span>
                    </template>
                </el-table-column>
                <el-table-column  label="Operations" width="120">
                    <template #default>
                        <el-button link type="primary" size="small">Edit</el-button>
                    </template>
                </el-table-column>
            </template>

            <template #footer>
                <el-pagination small background layout="prev, pager, next" :total="50" class="mt-4" />
            </template>
        </AppTable>
    </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddDestinations from "@/pages/page_trips/destinations/AddDestinations.vue"
export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddDestinations
    },
    data() {
        return {
            search: '',
            destinations: [],
            total_destinations: 0,
            loading: false,
            add_destination_modal: false,
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
                    per_page: 10,
                    page: 1,
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
        }
    },
    mounted() {
        this.getDestinations();
    }

}
</script>