<template>
    <div class="tm_pricing_wrapper">
        <h2 class="section-title">Pricing</h2>
        <div class="tm_form_wrapper">
            <h2 class="package-title">Package Name</h2>
            <draggable class="dragArea list-group w-full" :list="list" @change="log">
                <div class="packaging-list" v-for="element in list"
                    :key="element.name">
                    <div class="input-wrapper">
                        <el-icon><DCaret /></el-icon>
                        <el-input v-model="element.name" style="width: calc(100% - 40px)" placeholder="Enter Trip Title"
                            size="large" />
                    </div>
                    <div class="action-buttons">
                        <el-button size="large" type="success">Edit Pricing</el-button>
                        <el-button size="large" icon="Delete" />
                    </div>

                </div>
            </draggable>

            <div class="add-new-package">
                <el-button size="large" link type="primary">Add New Package</el-button>
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
    data() {
        return {
            enabled: true,
            list: [
                { name: 'John', id: 1 },
                { name: 'Joao', id: 2 },
                { name: 'Jean', id: 3 },
                { name: 'Gerard', id: 4 },
            ],
            dragging: false,
        }
    },
    methods: {
        log(event) {
            console.log(event)
        },
    },
})
</script>