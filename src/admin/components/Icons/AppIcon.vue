<template>
    <Plus v-if="icon === 'tm-plus'" />
</template>

<script>
import Plus from "@/components/Icons/Plus.vue";
export default {
    components: {
        Plus
    },
    props: {
        icon: {
            type: String,
        }
    }
}
</script>