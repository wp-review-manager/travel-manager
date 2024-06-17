<template>
    <el-dialog class="tm-app-modal" v-model="dialogVisible" :title="title" width="500" :before-close="handleClose">
        <slot name="body"></slot>
        <template #footer v-if="showFooter">
            <div class="dialog-footer">
                <el-button @click="dialogVisible = false">{{ cancelButtonText }}</el-button>
                <el-button type="primary" @click="dialogVisible = false">
                    {{ confirmButtonText }}
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script>
export default {
    data() {
        return {
            dialogVisible: false,
        };
    },
    props: {
        title: {
            type: String,
            default: "Dialog",
        },
        confirmButtonText: {
            type: String,
            default: "Confirm",
        },
        cancelButtonText: {
            type: String,
            default: "Cancel",
        },
        showFooter: {
            type: Boolean,
            default: true,
        },
    },
    methods: {
        handleClose(done) {
           this.dialogVisible = false;
        },
        openModel() {
            this.dialogVisible = true;
        },
    },
};
</script>