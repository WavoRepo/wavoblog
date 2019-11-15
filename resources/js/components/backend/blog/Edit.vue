<template>
    <div class="container  py-4 blog-edit">
        <form class="form-control no-borders" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group row">
                        <h3 class="col-sm-12">Edit Post</h3>
                        <div class="col-sm-12">
                            <input type="text" id="post_title" class="form-control input-lg" v-model="post.title">
                        </div>
                        <div class="col-sm-12 slug-wrap">
                            Permalink: {{ base_url }}/
                            <input type="text" id="post_slug" class="col-sm-7 form-control input-sm" v-model="post.slug">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <vue-editor v-model="post.content"></vue-editor>
                             <!-- <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor> -->
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            Publish
                        </div>
                        <div class="card-body">
                            <p id="post-status-display"> Status:
                                <select class="form-control" v-model="post.status">
                                    <option>Draft</option>
                                    <option>Published</option>
                                </select>
                            </p>
                            <p id="post-owner-display">
                                Author:
                                <span>{{ activeUser.name }}</span>
                            </p>
                            <p id="post-owner-display">
                                Published:
                                <span>{{ formatDate(post.created_at) }}</span>
                            </p>
                        </div>
                        <div class="card-footer">
                            <button type="button"
                                class="btn btn-primary btn-lg pull-right"
                                @click="submitForm">

                                Update
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Featured Image
                        </div>
                        <div class="card-body">
                            <div class="featured-image-wrap">
                                <img v-show="!show_preview && post.featured_image"
                                    id="profile-image"
                                    class="img-fluid"
                                    :src="getImage(post.featured_image)">

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
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import { VueEditor } from "vue2-editor";
    import { mapState, mapGetters,  mapActions } from 'vuex';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        name: 'edit_blog',
        components: {
            VueEditor
        },
        data() {
            return {
                show_preview: false,
                base_url: '',
                img_source: '',
                post: {
                    owner: {}
                },
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    // The configuration of the editor.
                }
            }
        },
        computed: {
            ...mapState('APP', {
                baseUrl: 'baseUrl'
            }),
            ...mapState('POSTS', {
                blogPost: 'selected'
            }),
            ...mapState('USERS', {
                activeUser: 'active'
            })
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
            blogPost: function ($post) {
                if(!_.isEmpty($post)) {
                    this.updateValue($post);
                }
            },
        },
        methods: {
            ...mapActions('POSTS', [
                'updatePosts',
                'setSelectedPost'
            ]),
            formatDate($date) {
                // return moment($date).format('ll');
                return moment($date).format('Do MM YYYY HH:mm');
            },
            getImage($image) {
                return ($image) ? $image + '?' + Math.random() : '';
            },
            preview() {
    			let self = this;
    			this.show_preview = true;

    			let photo = document.getElementById('upload-featured-image');
    			let image = photo.files[0];

    			let reader = new FileReader();

    			reader.addEventListener('load', function () {
    				self.img_source = reader.result;
                    self.post.featured_image = image;
    			}, false);

    			if (image) {
    				reader.readAsDataURL(image);
    			}
    		},
            cancelUpload () {
                this.img_source = '';
                this.post.featured_image = this.cachedImage;
                this.show_preview = false;
            },
            updateValue ($post) {
                this.base_url =  this.baseUrl;
                this.post.id =  $post.id;
                this.post.title =  $post.post_title;
                this.post.slug =  $post.post_slug;
                this.post.status =  $post.status;
                this.post.author =  $post.owner.name;
                this.post.content =  $post.post_content;
                this.post.featured_image =  $post.featured_image;

                this.img_source = $post.featured_image;
                this.cachedImage = $post.featured_image;
            },
            submitForm () {
                if (this.validateForm()) {

                    let self = this;

                    let formData = new FormData();

                    Object.keys(this.post).map(function(key) {
                        formData.append(key, self.post[key]);
                    });

                    let url = '/api/v1/post/' + this.post.id;

                    axios.post(url, formData)
                    .then((response) => {
                        self.updatePosts(response.data.post);
                        self.setSelectedPost(response.data.post);
                        self.show_preview = false;

                        self.$swal.fire({
                            type: 'success',
                            title: 'Post has been updated.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    })
                    .catch((error) => {
                        console.log('error: ', error);
                    })
                }
            },
            validateForm () {
                $('#post_title').removeClass('is-invalid');
                $('#post_slug').removeClass('is-invalid').css('border', 'none');

                if(_.isEmpty(this.post.title)) {
                    $('#post_title').addClass('is-invalid');
                    return false;
                }

                if(_.isEmpty(this.post.slug)) {
                    $('#post_slug').addClass('is-invalid').css('border', '1px solid red');
                    return false;
                }
                return true;
            }
        },
        mounted () {
            let _post = sessionStorage.getItem('selected-post');

            if(!_.isEmpty(this.blogPost)) {
                this.updateValue(this.blogPost);
            }
            else if(!_.isEmpty(_post)) {
                this.updateValue(JSON.parse(_post));
            } else {
                this.$router.push({name:'Blog Posts'});
            }
        }
    }
</script>

<style>
    .quillWrapper,
    .ck.ck-editor {
        margin-top: 25px;
    }
    .quillWrapper .ql-editor,
    .ck.ck-editor__main>.ck-editor__editable{
        min-height: 58vh;
    }
</style>
