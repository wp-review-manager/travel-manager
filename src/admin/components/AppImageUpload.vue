<template>
    <div class="tm_photo_card">
        <div class="tm_photo_holder file_upload_icon_btn" >
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSreAlx8vw_nSEP7lJzvHzk__lcXehVxw02kQ&s" />
        </div>
        <div v-if="buttonText" class="file_upload_text_btn">
            <Button @click="initUploader">
                {{ buttonText }}
            </Button>
        </div>
        <div v-else class="file_upload_icon_btn">
            <div class="icon">
                <Icon icon="tm-plus" />
            </div>
        </div>
    </div>
</template>

<script type="text/babel">
import Icon from "@/components/Icons/AppIcon.vue";

export default {
    components: {
        Icon,
    },
    props: {
        buttonText: {
            type: String,
        }
    }
}
</script>