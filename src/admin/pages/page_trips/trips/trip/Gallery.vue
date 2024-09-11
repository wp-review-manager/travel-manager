<template>
    <div class="tm-include-exclude-wrapper">
        <h2 class="section-title">Gallery</h2>
        <div class="tm_form_wrapper">
            <app-card :title="'Enable Image Gallery'" :sub_title="'If you want to hide/show image gallery from frontend you just enable/disable'">
                <template v-slot:actions>
                    <el-switch
                        v-model="meta.trip_gallery.enable_image_gallery"
                        size="large"
                        active-value="yes"
                        inactive-value="no"
                    />
                </template>

                <template v-slot:body v-if="meta.trip_gallery.enable_image_gallery == 'yes'">
                    <ImageGallery :image="meta.trip_gallery?.images " :is_multiple="true"/>
                </template>

            </app-card>
        </div>

        <div class="tm_form_wrapper">
            <app-card :title="'Enable Video Gallery'" :sub_title="'If you want to hide/show video gallery from frontend you just enable/disable'">
                <template v-slot:actions>
                    <el-switch
                        v-model="meta.trip_gallery.enable_video_gallery"
                        size="large"
                        active-value="yes"
                        inactive-value="no"
                    />
                </template>

                <template v-slot:body v-if="meta.trip_gallery.enable_video_gallery == 'yes'">
                    <div class="tm_form_wrapper">
                        <div class="input-wrapper">
                            <p class="form-label">Enter YouTube or Vimeo URL</p>
                            <div class="couple-inputs">
                                <el-input min="0" v-model="youtube_video_url" style="width: 100% !important" placeholder="https://www.youtube.com/" size="large" />
                                <el-button @click="addVideoUrl()" type="primary" size="large">Add</el-button>
                            </div>
                        </div>

                        <div class="tm-trip-gallery-thumbnail-container">
                            <div class="tm-trip-gallery-thumbnail" v-for="(thumbnail, index) in meta.trip_gallery.videos">
                                <img :src="thumbnail?.thumbnail" :alt="thumbnail?.alt"/>
                                <div @click="deleteVideo(index)" class="delete-btn">
                                    <Icon icon="tm-delete"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </app-card>

        </div>

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
            var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
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