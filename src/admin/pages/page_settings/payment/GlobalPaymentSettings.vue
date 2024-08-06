<template>
    {{ route_name }}
</template>

<script>
export default {
    props: {
        route_name: {
            type: String,
            required: true
        }
    },
};
</script>