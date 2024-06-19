<template>
    <div class="tm-side-navbar-wrapper" :style="{ width: width }">
        <ul class="tm-side-navbar">
            <li v-for="route in routes" :key="route.label">
                <router-link :to="route.to">
                    <span>{{ route.label }}</span>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "SideNavBar",

    props: {
        routes: {
            type: Array,
            required: true
        },
        width: {
            type: String,
            default: "200px"
        }  
    }
}
</script>