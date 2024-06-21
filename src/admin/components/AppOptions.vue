<template>
    <div class="tm-app-options">
        <draggable class="dragArea list-group w-full" :list="options">
            <div v-for="(option, index) in options" class="app-option" :key="index">
                <div class="draggable-icon">
                    <el-icon>
                        <DCaret />
                    </el-icon>
                </div>
                <div class="option-content">
                    <el-input v-model="option.label" placeholder="Option Label" size="large" />
                </div>
                <div class="delete-icon" @click="deleteOption(index)">
                    <Icon icon="tm-delete" />
                </div>
            </div>
        </draggable>
        <div class="add-new-option">
            <el-button @click="addNewOption" size="large" link type="primary">Add New Option</el-button>
            <Icon icon="tm-plus" />
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import Icon from "@/components/Icons/AppIcon.vue";
import { VueDraggableNext } from 'vue-draggable-next';
export default defineComponent({
    name: 'AppOptions',
    components: {
        Icon,
        draggable: VueDraggableNext,
    },
    props: {
        options: {
            type: Array,
            default: () => []
        }
    },
    methods: {
        addNewOption() {
            this.options.push({
                label: ''
            });
        },
        deleteOption(index) {
            this.options.splice(index, 1);
        }
    }
});
</script>