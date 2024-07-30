<template>
    <div class="tm-include-exclude-wrapper">
        <!-- <h2 class="section-title">Map</h2> -->

        <app-card :title="'Map'" :sub_title="' '">
            <template v-slot:actions>
                <el-switch v-model="meta.map.enable" size="large" active-value="yes" inactive-value="no" />
            </template>

            <template v-slot:body v-if="meta.map.enable == 'yes'">
                <app-card class="tm_label_card" :title="'Map Image'">
                    <template v-slot:body>
                        <ImageGallery :image="meta.map?.image" />
                    </template>
                </app-card>

                <app-card class="tm_label_card" :title="'Map Iframe Code'">
                    <template v-slot:body>
                        <div class="tm_form_wrapper">
                            <div class="input-wrapper">
                                <p class="form-label">Enter The Map Iframe Code</p>
                                <div class="couple-inputs">
                                    <el-input type="textarea" rows="6" v-model="meta.map.iframe_code"
                                        style="width: 100% !important" />
                                </div>
                            </div>
                        </div>
                    </template>
                </app-card>
            </template>

        </app-card>




    </div>
</template>


<script>
import AppCard from "@/components/AppCard.vue";
import Icon from "@/components/Icons/AppIcon"
import ImageGallery from "@/components/AppImageUpload.vue";

export default {
    components: {
        AppCard,
        ImageGallery,
        Icon
    },
    props: {
        meta: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            youtube_video_url: ''
        }
    },
    methods: {
        deleteVideo(index) {
            this.meta.trip_gallery?.videos.splice(index, 1)
        },
        addVideoUrl() {
            if (!this.validateUrl(this.youtube_video_url)) {
                this.$notify.error('Please enter a valid youtube  url');
                return;
            }
            if (this.youtube_video_url) {
                let url = {
                    video_link: this.youtube_video_url,
                    alt: "YouTube",
                    thumbnail: this.getThumbnail(this.youtube_video_url)
                }
                this.meta.trip_gallery.videos.push(url);
                this.youtube_video_url = '';
            }
        },
        getYouTubeVideoId(url) {
            const regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[7].length == 11) ? match[7] : null;
        },
        getThumbnail(url) {
            const videoId = this.getYouTubeVideoId(url);
            if (videoId) {
                return `https://img.youtube.com/vi/${videoId}/0.jpg`;
            }
            return '';
        },
        validateUrl(url) {
            var pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
                '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
                '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
                '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
                '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
                '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
            return !!pattern.test(url);
        }
    }
}
</script>

<style lang="scss" scoped>
.tm-trip-gallery-thumbnail-container {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;

    .tm-trip-gallery-thumbnail {
        width: calc(25% - 20px);
        border-radius: 8px;
        position: relative;

        img {
            border-radius: 8px;
        }

        .delete-btn {
            position: absolute;
            top: 4px;
            right: 4px;
            background: #F04438;
            padding: 4px;
            border-radius: 50%;
            cursor: pointer;
            color: #fff;
        }
    }
}
</style>