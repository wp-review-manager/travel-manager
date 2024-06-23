<template>
    <div class="tm-app-options" style="border: none; padding: 0;">
        <draggable class="dragArea list-group w-full" :list="options">
            <div v-for="(option, index) in options" class="app-option" :key="index">
                <div class="draggable-icon">
                    <el-icon>
                        <DCaret />
                    </el-icon>
                </div>
                <div class="option-content">
                    <div class="input-wrapper">
                        <p class="form-label" for="name">Day {{ index + 1 }}</p>
                        <el-input style="margin-bottom: 16px;" v-model="option.title" placeholder="Itinerary Title" size="large" />
                        <el-input type="textarea" rows="6" v-model="option.description" placeholder="Itinerary description" size="large" />
                    </div>
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
    name: 'itinerary-options',
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
                title: 'Itinerary Title',
                description: 'Itinerary description'
            });
        },
        deleteOption(index) {
            this.options.splice(index, 1);
        }
    }
});
</script>

<style lang="scss" scoped>
.app-option {
    border-bottom: 1px solid #E8EAF1; padding: 24px 0px;
    &:hover {
       background-color: #E8EAF1;
       border-radius: 8px; 
    }
}
</style>