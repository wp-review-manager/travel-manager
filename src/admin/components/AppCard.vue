<template>
    <div class="app-card">
        <div class="app-card-header">
            <div class="app-card-title">
                <div class="app-card-header-title">
                    <h3>{{ title }}</h3>
                </div>
                <div v-if="sub_title" class="app-card-header-sub-title">
                    {{ sub_title }}
                </div>
            </div>
            <div class="app-card-header-actions">
                <slot name="actions"></slot>
            </div>
        </div>
        <div class="app-card-body">
            <slot name="body"></slot>
            
        </div>
    </div>
</template>

<script>
export default {
    name: 'app-card',
    props: {
        title: {
            type: String,
            required: true
        },
        sub_title: {
            type: String,
            default: ''
        }
    }
}
</script>