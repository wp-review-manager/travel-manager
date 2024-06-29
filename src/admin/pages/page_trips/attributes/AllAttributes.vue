<template>
     <div class="tm_destinations_wrapper">

        <AppModal
            :title="'Add New Attribute'"
            :width="800"
            :showFooter="false"
            ref="add_attributes_modal">
            <template #body>
                <AddAttributions @updateDataAfterNewAdd="updateDataAfterNewAdd" />
            </template>
        </AppModal>

        <AppTable :tableData="attributes" v-loading="loading">
            <template #header>
                <h1 class="table-title">All Attributes</h1>
                <el-button @click="openAttributesAddModal" size="large" type="primary" icon="Plus">Add New Attribute</el-button>
            </template>
            <template #filter>
                <el-input @change="getDestinations" class="tm-search-input" v-model="search" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
            </template>
            <template #columns>
                <el-table-column prop="id" label="ID" width="40" />
                <el-table-column prop="attr_title" label=" Title" width="auto" />
                <el-table-column prop="attr_slug" label="Slug" width="auto" />
                <el-table-column prop="attr_desc" label="Description" width="420" />
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
           

        </AppTable>

     </div>
</template>

<script>
import AppTable from "@/components/AppTable.vue";
import AppModal from "@/components/AppModal.vue";
import AppDatePicker from "@/components/element/AppDatePicker.vue";
import AddAttributions from "@/pages/page_trips/attributes/AddAttributions.vue";
import Icon from "@/components/Icons/AppIcon.vue";


export default {
    components: {
        AppTable,
        AppModal,
        AppDatePicker,
        AddAttributions,
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
            active_id: null
        }
    },

    methods: {
       
        openAttributesAddModal() {
            this.$refs.add_attributes_modal.openModel();
        },
       
    },
   
}

</script>


