<template>
    <h1>General</h1>
</template>

<script>
export default {
    data() {
        return {
            trip_id: null,
        }
    },
    props: {
        trip_info: {
            type: Object,
            default: () => {}
        }
    },
    mounted() {
        this.trip_id = this.$route.params.id;
    }
}
</script>