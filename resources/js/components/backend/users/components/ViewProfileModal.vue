<template>
    <div class="modal fade user-profile"
        id="viewProfileModal"
        tabindex="-1"
        role="dialog"
        aria-labelledby="editProfileLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="editProfileLabel">Profile Details</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form-control no-borders" action="" method="post" enctype="multipart/form-data" style="padding: 0;">
                    <div class="modal-body" style="background-color: #fff;">
                        <div class="row justify-content-end">
                            <div class="col-md-12">
                                    <div style="margin-bottom: 15px;">
                                        <div class="ibox-content no-padding border-left-right profile-image-wrap"
                                            :style="'background-image: url(\'' + base_url + '/images/no-image.jpg\');'">

                                            <img v-show="profilePrimary.profile_image"
                                                class="img-fluid"
                                                :src="base_url + getImage(profilePrimary.profile_image)">
                                        </div>
                                        <div class="ibox-content profile-content">
                                            <h2>
                                                <i class="fa fa-th-large"></i>
                                                <strong> {{profilePrimary.name}} </strong>
                                            </h2>

                                            <p class="text-info profile-email">
                                                <i class="fa fa-envelope"></i>
                                                {{profilePrimary.email }}
                                            </p>

                                            <p v-if="profileDetails.address" class="profile-address">
                                                <i class="fa fa-map-marker" style="margin-right: 15px;"></i>
                                                {{profileDetails.address }}
                                            </p>
                                            <p v-if="profileDetails.phone" class="profile-phone">
                                                <i class="fa fa-phone"></i>
                                                {{profileDetails.phone }}
                                            </p>

                                            <template  v-if="profileDetails.about">
                                                <h3>About me</h3>
                                                <p>
                                                    {{ profileDetails.about }}
                                                </p>
                                            </template>
                                        </div>
                                    </div>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="background-color: #fff;">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    export default {
        name: 'view-profile-modal',
        props: ['base_url'],
        data () {
            return {
                profilePrimary: {},
                profileDetails: {}
            }
        },
        computed: {
            ...mapState('USERS', {
                user: 'selectedUser',
                details: 'selectedDetails'
            })
        },
        watch: {
            user: function ($user) {
                this.profilePrimary = $user;
            },
            details: function ($details) {
                this.profileDetails = $details;
            }
        },
        methods: {
            getImage($image) {
                return ($image) ? $image + '?' + Math.random() : '';
            }
        },
        mounted () {
        }
    }
</script>

<style lang="css" scoped>
    .pretty {
        margin-top: 10px;
    }
</style>
