<template>
    <div class="tm_photo_card">
        <div
            v-if="is_multiple && image.length > 0"
            v-for="(img, index) in image"
            class="tm_photo_holder file_upload_icon_btn"
        >
            <img :src="img?.url" :alt="img?.name"/>
        </div>
        <div v-else-if="hasImage(image)" class="tm_photo_holder file_upload_icon_btn">
            <img :src="image?.url" :alt="image?.name"/>
        </div>
        <div v-if="buttonText" class="file_upload_text_btn">
            <Button @click="initUploader">
                {{ buttonText }}
            </Button>
        </div>
        <div v-else class="file_upload_icon_btn" @click="initUploader">
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
        },
        image: {
            type:  [Object, Array],
            required: true
        },
        is_multiple: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        initUploader(event) {
            let that = this;
            const upload = wp
                .media({
                    title: "Choose Image", //Title for Media Box
                    multiple: that.is_multiple, //For limiting multiple image
                })
                .on("select", () => {
                    const select = upload.state().get("selection");
                    const attach = select.first().toJSON();

                    if (that.is_multiple) {
                        select.toJSON()?.map((item) => {
                            let image = {
                                id: item.id,
                                url: item.url,
                                name: item.name,
                            };
                            that.image.push(image);
                        });
                    } else {
                        that.image.id = attach.id;
                        that.image.url = attach.url;
                        that.image.name = attach.name;
                    }
                    
                })
                .open();
        },
        hasImage(image) {
            return image?.url?.length > 0;
        }
    }
}
</script>