<template>
    <div class="tm_destinations_wrapper">
        <AddAttributes />
        <AppTable :tableData="tableData">
            <template #header>
                <h1 class="table-title">All Attributes</h1>
            </template>
            <template #filter>
                <el-input class="tm-search-input" v-model="input1" style="width: 240px" size="large"
                    placeholder="Please Input" prefix-icon="Search" />
                <AppDatePicker />
            </template>
            <template #columns>
                <el-table-column prop="date" label="Date" width="220" />
                <el-table-column prop="name" label="Name" width="180" />
                <el-table-column prop="state" label="State" width="180" />
                <el-table-column prop="city" label="City" width="220" />
                <el-table-column prop="zip" label="Zip" width="180" />
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
import AppDatePicker from "@/components/element/AppDatePicker.vue"
import AddAttributes from "@/pages/page_trips/attributes/AddAttributes.vue"
export default {
    components: {
        AppTable,
        AppDatePicker,
        AddAttributes
    },
    data() {
        return {
            input1: '',
            tableData: [
                {
                    date: '2016-05-03',
                    name: 'Tom',
                    state: 'California',
                    city: 'Los Angeles',
                    address: 'No. 189, Grove St, Los Angeles',
                    zip: 'CA 90036',
                    tag: 'Home',
                },
                {
                    date: '2016-05-02',
                    name: 'Tom',
                    state: 'California',
                    city: 'Los Angeles',
                    address: 'No. 189, Grove St, Los Angeles',
                    zip: 'CA 90036',
                    tag: 'Office',
                },
                {
                    date: '2016-05-04',
                    name: 'Tom',
                    state: 'California',
                    city: 'Los Angeles',
                    address: 'No. 189, Grove St, Los Angeles',
                    zip: 'CA 90036',
                    tag: 'Home',
                },
                {
                    date: '2016-05-04',
                    name: 'Tom',
                    state: 'California',
                    city: 'Los Angeles',
                    address: 'No. 189, Grove St, Los Angeles',
                    zip: 'CA 90036',
                    tag: 'Home',
                },
                {
                    date: '2016-05-01',
                    name: 'Tom',
                    state: 'California',
                    city: 'Los Angeles',
                    address: 'No. 189, Grove St, Los Angeles',
                    zip: 'CA 90036',
                    tag: 'Office',
                },
            ]
        }
    }
}
</script>