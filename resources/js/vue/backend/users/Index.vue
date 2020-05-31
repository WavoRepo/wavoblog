<template>
    <div class="container py-4 user-index">
        <div class="row">
            <template v-for="user of users">
                <div class="col-lg-4">
                    <div class="contact-box center-version">
                        <div class="rounded-circle profile-image-wrap"
                            :style="'background-image: url(\'' + base_url + '/images/no-image.jpg\'); '">

                            <img v-show="user.profile_image"
                                alt="image"
                                class="rounded-circle"
                                :src="base_url + getImage(user.profile_image)">
                        </div>

                        <h3 class="m-b-xs align-center"><strong>{{ user.name }}</strong></h3>
                        <p class="text-info  align-center"><i class="fa fa-envelope"></i> {{ user.email }}</p>

                        <div class="contact-box-footer">
                            <div class="m-t-xs btn-group">
                                <button class="btn btn-xs btn-white"><i class="fa fa-envelope"></i> Email</button>
                                <button class="btn btn-xs btn-white"><i class="fa fa-comment"></i> Message</button>
                                <button class="btn btn-xs btn-white"
                                    data-toggle="modal"
                                    data-target="#viewProfileModal"
                                    @click="selectUser(user.id)">

                                    <i class="fa fa-eye"></i> View
                                </button>
                                <button v-if="user.email == activeUser.email"
                                    class="btn btn-xs btn-white"
                                    data-toggle="modal"
                                    data-target="#editProfileModal">

                                    <i class="fa fa-pencil"></i> Edit Profile
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </template>
            <view-profile-modal :base_url="base_url"/>
            <edit-profile-modal :base_url="base_url"/>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    // import ViewProfileModal from './components/ViewProfileModal';
    // import EditProfileModal from './components/EditProfileModal';

    export default {
        name: 'users',
        // components: {
        //     ViewProfileModal,
        //     EditProfileModal
        // },
        data() {
            return {
                base_url: document.head.querySelector('meta[name="base_url"]').content
            }
        },
        computed: {
            ...mapState('USERS', {
                users: 'all',
                activeUser: 'active'
            })
        },
        watch: {
            selectedDetails: function ($details) {
                console.log($details);
            }
        },
        methods: {
            ...mapActions('USERS', [
                'setUsers',
                'setSelectedUser'
            ]),
            ucwords (str) {
                return (str + '').replace(/^(.)|\s+(.)/g, function ($1) {
                    return $1.toUpperCase()
                })
            },
            getImage ($image) {
                return ($image) ? $image + '?' + Math.random() : '';
            },
            getAllUsers () {
                let self = this;

                if(!_.isEmpty(this.users)) return;

                client.get('/api/v1/user/')
                .then((response) => {
                    self.setUsers(response.data.users);
                })
                .catch((error) => {
                    console.log('error: ', error);
                })
            },
            selectUser($id) {
                this.setSelectedUser($id);
            }
        },
        mounted () {
            this.getAllUsers();
        }
    }
</script>
