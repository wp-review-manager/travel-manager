<template>
    <Plus v-if="icon === 'tm-plus'" />
    <Cancel v-if="icon === 'tm-cancel'" />
    <Edit v-if="icon === 'tm-edit'" />
    <Delete v-if="icon === 'tm-delete'" />
</template>

<script>
import Plus from "@/components/Icons/Plus.vue";
import Cancel from "@/components/Icons/Cancel.vue";
import Edit from "@/components/Icons/Edit.vue";
import Delete from "@/components/Icons/Delete.vue";
export default {
    components: {
        Plus,
        Cancel,
        Edit,
        Delete
    },
    props: {
        icon: {
            type: String,
        }
    }
}
</script>