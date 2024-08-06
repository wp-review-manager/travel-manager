<template>
    {{ route.meta.title }} {{ route }}
</template>

<script>
export default {
    props: {
        route: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            form: {}
        };
    },
    mounted() {
        console.log("this.route");
        // this.form = this.route.meta.form;
    }
};
</script>