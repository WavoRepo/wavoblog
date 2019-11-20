<template>
    <div class="modal fade user-edit"
        id="editProfileModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="editProfileLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="editProfileLabel">
                        Edit Profile
                    </h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-control no-borders" action="" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row justify-content-end">
                            <label for="cash" class="col-sm-3 col-form-label">Profile Image</label>
                            <div class="col-md-9">
                                <div class="ibox uploader-wrap">
                                    <div>
                                        <div class="ibox-content no-padding border-left-right profile-image-wrap"
                                            :style="'background-image: url(\'' + base_url + '/images/no-image.jpg\');'">

                                            <img v-show="!show_preview && profile.user_image"
                                                id="profile-image"
                                                class="img-fluid"
                                                :src="getImage(profile.user_image)">

                                            <img v-show="show_preview"
                                                alt="image"
                                                id="profile-image-preview"
                                                class="img-fluid"
                                                :src="img_source">
                                        </div>
                                        <div class="ibox-content image-uploader">
                                            <div class="user-button">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="fileinput fileinput-new"
                                                            data-provides="fileinput">

                                                            <span class="btn btn-secondary btn-file btn-block">
                                                                <span class="fileinput-new">
                                                                    <i class="fa fa-image"></i> <span id="upload-photo-text">Upload Photo</span>
                                                                </span>
                                                                <span class="fileinput-exists">
                                                                    Change
                                                                </span>
                                                                <input type="file" id="upload-profile-image" accept="image/*" @change="preview"/>
                                                            </span>
                                                            <span class="fileinput-filename"></span>
                                                            <a href="#"
                                                                id="cancel-upload"
                                                                class="btn btn-lg  btn-warning close fileinput-exists"
                                                                data-dismiss="fileinput"
                                                                @click="cancelUpload">

                                                                <i class="fa fa-ban"></i> Cancel
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group row">
                                    <label for="vendorCode" class="col-sm-3 col-form-label">
                                        Name
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" id="user_name" class="form-control" v-model="profile.name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="cash" class="col-sm-3 col-form-label">
                                        Email
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" id="user_email" class="form-control" v-model="profile.email" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="percentage" class="col-sm-3 col-form-label">
                                        Phone
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" v-model="profile.phone">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="yqTax" class="col-sm-3 col-form-label">
                                        Address
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" v-model="profile.address">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="upsell" class="col-sm-3 col-form-label">
                                        About Me
                                    </label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" v-model="profile.about"> </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="cancel">Cancel</button>
                        <button type="button" class="btn btn-primary" @click="submitForm">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    export default {
        name: 'edit-profile-modal',
        props: ['base_url'],
        data () {
            return {
                img_source: '',
                show_preview: false,
                profile: {

                },
                cachedImage: ''
            }
        },
        computed: {
            ...mapState('USERS', {
                user: 'active',
                details: 'activeDetails'
            })
        },
        watch: {
            user: function ($user) {
                this.profile.name = $user.name;
                this.profile.email = $user.email;
            },
            details: function ($details) {
                if(!_.isEmpty($details)) {
                    this.profile = $details;
                    this.img_source = $details.user_image;
                    this.cachedImage = $details.user_image;
                }
            },
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
            ...mapActions('USERS', [
                'setActiveUser',
                'setActiveUserDetails'
            ]),
            getImage($image) {
                return ($image) ? $image + '?' + Math.random() : '';
            },
            preview() {
    			let self = this;
    			this.show_preview = true;

    			let photo = document.getElementById('upload-profile-image');
    			let image = photo.files[0];

    			let reader = new FileReader();

    			reader.addEventListener('load', function () {
    				self.img_source = reader.result;
                    self.profile.user_image = image;
    			}, false);

    			if (image) {
    				reader.readAsDataURL(image);
    			}
    		},
            cancelUpload () {
                this.img_source = '';
                this.profile.user_image = this.cachedImage;
                this.show_preview = false;
            },
            cancel () {

            },
            submitForm () {
                if (this.validateForm()) {
                    let self = this;

                    let formData = new FormData();

                    formData.append('name', this.user.name);

                    Object.keys(this.profile).map(function(key) {
                        formData.append(key, self.profile[key]);
                    });

                    let url = '/api/v1/user/' + this.user.id;

                    axios.post(url, formData)
                    .then((response) => {
                        self.setActiveUser(response.data.user);

                        if(!_.isEmpty(response.data.details)) {
                            self.setActiveUserDetails(response.data.details);
                        }
                        self.show_preview = false;
                        $('#editProfileModal').modal('hide');

                        self.$swal.fire({
                            type: 'success',
                            title: 'Profile has been updated.',
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
                if(this.profile.name == '' ) {
                    $('#user_name').addClass('is-invalid');
                    return false;
                } else {
                    $('#user_name').removeClass('is-invalid');
                }

                return true;
            }
        },
        mounted: function () {
            this.profile.name = this.user.name;
            this.profile.email = this.user.email;
            this.profile = this.details;
        }
    }
</script>
