<template>
    <div class="tm-table-wrapper">
        <div class="tm-table-header">
            <h1 class="table-title">All Trips</h1>
            <el-button size="large" type="primary" icon="Plus" >Add New Trip </el-button>
        </div>
        <div class="tm-table-filter">
            <el-input class="tm-search-input" v-model="input1" style="width: 240px" size="large" placeholder="Please Input"
                prefix-icon="Search" />
            <AppDatePicker />
        </div>
        <el-table :data="tableData" height="250" style="width: 100%">
            <el-table-column prop="date" label="Date" width="180" />
            <el-table-column prop="name" label="Name" width="180" />
            <el-table-column prop="address" label="Address" />
        </el-table>
    </div>
</template>

<script>
import AppDatePicker from "@/components/element/AppDatePicker.vue"
export default {
    name: "AppTable",
    components: {
        AppDatePicker
    },
    data() {
        return {
            input1: "",
            // Search: "el-icon-search"
        };
    },
    props: {
        tableData: {
            type: Array,
            default: () => [],
            required: true
        }
    }
}
</script>