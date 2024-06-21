<template>
    <div class="tm_pricing_wrapper">
        <h2 class="section-title">Pricing</h2>
        <div class="tm_form_wrapper">
            <h2 class="package-title">Package Name</h2>
            <draggable class="dragArea list-group w-full" :list="meta.packages" @change="log">
                <div class="packaging-list" v-for="(package_data, index) in meta.packages" :key="package_data.id">
                    <div class="input-wrapper">
                        <el-icon>
                            <DCaret />
                        </el-icon>
                        <el-input v-model="package_data.title" style="width: calc(100% - 40px)"
                            placeholder="Enter Trip Title,  Ex: Golden/ Regular" size="large" />
                    </div>
                    <div class="action-buttons">
                        <el-switch
                            v-model="package_data.enable"
                            size="large"
                            active-value="yes"
                            inactive-value="no"
                            style="margin: 0px 10px;"
                        ></el-switch>
                        <el-button :class="package_data.enable == 'no' ? 'disabled_edit' : ''" @click="openEditPackageModal(package_data)" size="large" type="success">Edit Pricing</el-button>
                        <el-button @click="deleteConfirmation(index)" size="large" icon="Delete" />
                    </div>

                </div>
            </draggable>

            <div class="add-new-package">
                <el-button @click="addNewPackage" size="large" link type="primary">Add New Package</el-button>
                <Icon icon="tm-plus" />
            </div>
        </div>

        <AppModal
            :title="`Edit Pricing - ${package_info.title}`"
            :width="800"
            :showFooter="false"
            ref="edit_package_modal">
            <template #body>
                <EditPricing :package_info="package_info" />
            </template>
            <template #footer>
                <div class="pricing-save-btn">
                    <el-button @click="saveTrip(index)" size="large" type="primary">Save</el-button>
                </div>
            </template>
        </AppModal>
        
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import Icon from '@/components/Icons/AppIcon.vue';
import AppModal from '@/components/AppModal.vue';
import EditPricing from './_EditPricing.vue';
import { VueDraggableNext } from 'vue-draggable-next';
export default defineComponent({
    components: {
        draggable: VueDraggableNext,
        Icon,
        AppModal,
        EditPricing
    },
    props: {
        meta: {
            type: Object,
            default: () => { }
        }
    },
    data() {
        return {
            enabled: true,
            dragging: false,
            package_info: {}
        }
    },
    methods: {
        log(event) {
            console.log(event)
        },
        addNewPackage() {
            this.meta.packages.push({
                id: this.meta.packages.length + 1,
                title: 'Ex: Golden/ Regular',
                enable: "yes",
                available_booking_date: {
                    enable: "yes",
                    start_date: "",
                    end_date: ""
                },
                available_booking_date: {
                    enable: "yes",
                    start_date: "2023-11-12",
                    end_date: "2023-11-12"
                },
                package_quantity: 0,
                pricing: [
                    {
                        enable: "yes",
                        label: "Adult",
                        price: 500,
                        pricing_type: "per_person/group",// Should research about group
                        selling_price: 400,
                        min_pax: 3,
                        max_pax: 5,
                    }
                ]
            })
        },
        deleteConfirmation(index) {
            this.$confirm('This will permanently delete the package. Continue?', 'Warning', {
                confirmButtonText: 'OK',
                cancelButtonText: 'Cancel',
                type: 'warning',
            }).then(() => {
                this.meta.packages.splice(index, 1);
                this.$notify({
                    type: 'success',
                    message: 'Delete completed',
                    position: 'bottom-right'
                });
            }).catch(() => {
                this.$notify({
                    type: 'info',
                    message: 'Delete canceled',
                    position: 'bottom-right'
                });
            });
        },
        openEditPackageModal(row) {
            if (row.enable == 'yes') {
                this.package_info = row;
                this.$refs.edit_package_modal.openModel();
            }
        },
        saveTrip(index) {
            this.$emit('saveTrip', index);
            this.$refs.edit_package_modal.handleClose();
        }
    },
})
</script>