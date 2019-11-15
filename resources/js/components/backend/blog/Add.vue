<template>
    <div class="container py-4 blog-add">
        <form class="form-control no-borders" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-group row">
                        <h3 class="col-sm-12">Add New Post</h3>
                        <div class="col-sm-12">
                            <input type="text" id="post_title" class="form-control" v-model="post.title">
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
                            <p id="post-status-display">
                                Status:
                                <span> Draft </span>
                            </p>
                            <p id="post-owner-display">
                                Author:
                                <span>{{ activeUser.name }}</span>
                            </p>
                            <p id="post-created-display">
                                Published:
                                <span>{{ datenow }}</span>
                            </p>
                        </div>
                        <div class="card-footer">
                            <button type="button"
                                class="btn btn-secondary btn-mg pull-right ml-2"
                                @click="submitForm('Draft')">

                                Draft
                            </button>
                            <button type="button"
                                class="btn btn-primary btn-md pull-right"
                                @click="submitForm('Published')">

                                Publish
                            </button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            Featured Image
                        </div>
                        <div class="card-body">
                            <div class="featured-image-wrap">
                                <img v-show="show_preview"
                                    id="featured-image-preview"
                                    class="img-fluid"
                                    :src="img_source">
                            </div>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <span class="btn btn-secondary btn-file btn-block">
                                    <span class="fileinput-new">
                                        <i class="fa fa-image"></i>
                                        <span id="upload-photo-text">
                                            Upload Photo
                                        </span>
                                    </span>
                                    <span class="fileinput-exists">
                                        Change
                                    </span>
                                    <input type="file" id="upload-featured-image" accept="image/*" @change="preview"/>
                                </span>
                                <a href="#" id="cancel-upload"
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
        name: 'add_blog',
        components: {
            VueEditor
        },
        data() {
            return {
                datenow: '',
                show_preview: false,
                img_source: '',
                post: {},
                editor: ClassicEditor,
                editorData: '<p>Content of the editor.</p>',
                editorConfig: {
                    // The configuration of the editor.
                }
            }
        },
        computed: {
            ...mapState('POSTS', {
                blogPosts: 'all'
            }),
            ...mapState('USERS', {
                activeUser: 'active'
            })
        },
        watch: {
            show_preview: function ($show) {
                if($show) {
                    $('#upload-photo-text').html('Change Photo');
                    $('#cancel-upload').show();
                } else {
                    $('#upload-photo-text').html('Upload Photo');
                    $('#cancel-upload').hide();
                }
            }
        },
        methods: {
            ...mapActions('POSTS', [
                'addPosts'
            ]),
            time() {
                var self = this
                this.datenow = moment().format('Do MMM YYYY HH:mm ss');

                setInterval(self.time, 1);
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
                this.show_preview = false;
            },
            submitForm ($tatus) {
                if (this.validateForm()) {
                    let self = this;

                    let formData = new FormData();
                    formData.append('status', $tatus);

                    Object.keys(this.post).map(function(key) {
                        formData.append(key, self.post[key]);
                    });

                    let url = '/api/v1/post';

                    axios.post(url, formData)
                    .then((response) => {
                        if(!_.isEmpty(this.blogPosts)) {
                            self.addPosts(response.data.post);
                        }

                        self.$swal.fire({
                            type: 'success',
                            title: 'New Post has been added.',
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
                if(_.isEmpty(this.post.title)) {
                    $('#post_title').addClass('is-invalid');
                    return false;
                } else {
                    $('#post_title').removeClass('is-invalid');
                    return true;
                }
            }
        },
        mounted: function () {
            this.time();
        }
    }
</script>

<style>
    .quillWrapper,
    .ck.ck-editor {
        margin-top: 40px;
    }
    .quillWrapper .ql-editor,
    .ck.ck-editor__main>.ck-editor__editable{
        min-height: 58vh;
    }
</style>
