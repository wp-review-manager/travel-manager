<template>
    <Plus v-if="icon === 'tm-plus'" />
    <Cancel v-if="icon === 'tm-cancel'" />
    <Edit v-if="icon === 'tm-edit'" />
    <Delete v-if="icon === 'tm-delete'" />
    <Eye v-if="icon === 'tm-eye'" />
</template>

<script>
import Plus from "@/components/Icons/Plus.vue";
import Cancel from "@/components/Icons/Cancel.vue";
import Edit from "@/components/Icons/Edit.vue";
import Delete from "@/components/Icons/Delete.vue";
import Eye from "./Eye.vue";

export default {
    components: {
        Plus,
        Cancel,
        Edit,
        Delete,
        Eye
    },
    props: {
        icon: {
            type: String,
        }
    }
}
</script>