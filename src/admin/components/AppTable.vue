<template>
    <div class="tm-table-wrapper">
        <div class="tm-table-header">
            <slot name="header"></slot>
        </div>
        <div class="tm-table-filter">
            <slot name="filter"></slot>
        </div>

        <el-table :data="tableData" style="width: 100%">
            <slot name="columns"></slot>
        </el-table>
        <div class="tm-pagination table-footer">
            <slot name="footer"></slot>
        </div>
    </div>
</template>

<script>

export default {
    name: "AppTable",
    props: {
        tableData: {
            type: Array,
            default: () => [],
            required: true
        }
    }
}
</script>