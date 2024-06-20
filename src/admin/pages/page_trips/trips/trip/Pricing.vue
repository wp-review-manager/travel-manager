<template>
    <div class="tm_pricing_wrapper">
        <h2 class="section-title">Pricing</h2>
        <div class="tm_form_wrapper">
            <h2 class="package-title">Package Name</h2>
            <draggable class="dragArea list-group w-full" :list="meta.packages" @change="log">
                <div class="packaging-list" v-for="package_data in meta.packages" :key="package_data.id">
                    <div class="input-wrapper">
                        <el-icon>
                            <DCaret />
                        </el-icon>
                        <el-input v-model="package_data.title" style="width: calc(100% - 40px)"
                            placeholder="Enter Trip Title,  Ex: Golden/ Regular" size="large" />
                    </div>
                    <div class="action-buttons">
                        <el-button size="large" type="success">Edit Pricing</el-button>
                        <el-button size="large" icon="Delete" />
                    </div>

                </div>
            </draggable>

            <div class="add-new-package">
                <el-button @click="addNewPackage" size="large" link type="primary">Add New Package</el-button>
                <Icon icon="tm-plus" />
            </div>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import Icon from '@/components/Icons/AppIcon.vue'
import { VueDraggableNext } from 'vue-draggable-next'
export default defineComponent({
    components: {
        draggable: VueDraggableNext,
        Icon
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

                package_quantity: {
                    enable: "yes",
                    quantity: 44
                },

                pricing: [
                    {
                        adult: {
                            enable: "yes",
                            label: "Adult",
                            price: 500,
                            pricing_type: "per_person/group",// Should research about group
                            selling_price: {
                                enable: "yes",
                                price: 400
                            },
                            min_pax: 3,
                            max_pax: 5,
                        },
                    }
                ]
            })
        }
    },
})
</script>