<template>

    <div class="card">
        <div class="card-header">
            Featured Image
        </div>
        <div class="card-body">
            <div class="featured-image-wrap">
                <img v-show="!show_preview && image"
                    id="profile-image"
                    class="img-fluid"
                    :src="getImage(image)">

                <img v-show="show_preview"
                    alt="image"
                    id="featured-image-preview"
                    class="img-fluid"
                    :src="img_source">
            </div>
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <span class="btn btn-secondary btn-file btn-block">
                    <span class="fileinput-new">
                        <i class="fa fa-image"></i>
                        <span id="upload-photo-text">
                            Upload
                        </span>
                    </span>
                    <span class="fileinput-exists">
                        Change
                    </span>
                    <input type="file" id="upload-featured-image" accept="image/*" @change="preview"/>
                </span>
                <a href="#"
                    id="cancel-featured-image-upload"
                    class="btn btn-lg  btn-warning close fileinput-exists"
                    data-dismiss="fileinput"
                    @click="cancelUpload">

                    <i class="fa fa-ban"></i>
                    Cancel
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters,  mapActions } from 'vuex';

export default {
    props: ['featuredImage'],
    data() {
        return {
            show_preview: false,
            base_url: '',
            img_source: '',
            cachedImage: '',
            image: ''
        }
    },
    watch: {
        show_preview: function ($show) {
            if($show) {
                $('#upload-photo-text').html('Change Photo');
                $('#cancel-featured-image-upload').show();
            } else {
                $('#upload-photo-text').html('Upload Photo');
                $('#cancel-featured-image-upload').hide();
            }
        },
        featuredImage: function (featuredImage) {
            this.image = featuredImage;
            this.cachedImage = featuredImage;
        }
    },
    methods: {
        getImage($image) {
            return ($image) ? $image + '?' + Math.random() : '';
        },
        preview() {
            let self = this;
            this.show_preview = true;

            let photo = document.getElementById('upload-featured-image');
            let selectedImage = photo.files[0];

            let reader = new FileReader();

            reader.addEventListener('load', function () {
                self.img_source = reader.result;
                self.image = selectedImage;
            }, false);

            if (selectedImage) {
                reader.readAsDataURL(selectedImage);
            }
        },
        cancelUpload () {
            this.img_source = '';
            this.image = this.cachedImage;
            this.show_preview = false;
        },
    },
    mounted () {
        this.image = this.featuredImage;
        this.cachedImage = this.featured_image;
    }
}
</script>

<style lang="css" scoped>
</style>
