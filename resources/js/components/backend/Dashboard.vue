<template>
    <div class="container py-4">
        <div class="row"  id="sortable-view">
            <template v-for="service of services">
                <div class="col-lg-4">
                    <router-link :to="'/admin/' + service.slug">
                    <div class="ibox">
                        <div class="ibox-title">
                            <h5>{{ service.name }}</h5>
                        </div>
                        <div class="ibox-content">
                            <center><i :class="service.icon" :style="contentStyle(service.status)"></i></center>
                        </div>
                        <div class="ibox-footer align-center">
                            {{ service.desc }}
                        </div>
                    </div>
                    </router-link>
                </div>
            </template>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default{
        name: 'dashboard',
        data() {
            return {
            }
        },
        computed: {
            ...mapState('SERVICES', {
                services: 'all'
            }),
        },
        methods: {
            ...mapActions('SERVICES', [
                'setServices'
            ]),
            ...mapActions('USERS', [
                'setUsers',
                'setActiveUser',
                'setActiveUserDetails'
            ]),
            titleStyle ($status) {
                if ($status == 'active') {
                    return 'color: #676A68';
                } else {
                    return 'color: #a7b1c2;';
                }
            },
            contentStyle ($status) {
                if ($status == 'active') {
                    return 'font-size: 4.5rem; color: #0e9aef;';
                } else {
                    return 'font-size: 4.5rem; color: #a7b1c2';
                }
            },
            getActiveUser () {
                if(!_.isEmpty(this.user)) return;

                let self = this;
                axios.get('/api/v1/user')
                .then(function (response) {
        			self.setActiveUser(response.data.user);
                    if(!response.data.details) return;
                    self.setActiveUserDetails(response.data.details);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getAllUsers () {
                let self = this;

                if(!_.isEmpty(this.users)) return;

                axios.get('/api/v1/user/')
                .then((response) => {
                    self.setUsers(response.data.users);
                })
                .catch((error) => {
                    console.log('error: ', error);
                })
            },
            getServices () {
                if(!_.isEmpty(this.services)) return;

                let self = this;
                axios.get('/api/v1/get-dashboard-services')
                .then(function (response) {
        			self.setServices(response.data);
                })
                .catch(function (error) {
                    console.log(error);
                });
            },

        },
        mounted() {
            this.getActiveUser();
            this.getServices();
        }
    }
</script>
