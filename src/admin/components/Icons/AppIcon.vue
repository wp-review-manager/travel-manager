<template>
    <Plus v-if="icon === 'tm-plus'" />
    <Cancel v-if="icon === 'tm-cancel'" />
</template>

<script>
import Plus from "@/components/Icons/Plus.vue";
import Cancel from "@/components/Icons/Cancel.vue";
export default {
    components: {
        Plus,
        Cancel
    },
    props: {
        icon: {
            type: String,
        }
    }
}
</script>