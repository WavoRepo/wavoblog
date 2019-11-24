<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-10">
                <ol class="breadcrumb" style="background-color: #eceaea;">
                    <li v-if="title == 'Dashboard'" class="breadcrumb-item">
                        <router-link :to="'/admin/'">
                            <i class="fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </router-link>
                    </li>
                    <template v-if="title != 'Dashboard'">
                        <li class="breadcrumb-item">
                            <router-link :to="'/admin/'">
                                <i class="fa fa-th-large"></i>
                                <span class="nav-label">Dashboard</span>
                            </router-link>
                        </li>
                        <li v-show="isBlog(title)" class="breadcrumb-item">
                            <router-link :to="'/admin/blog'">
                                <i class="fa fa-blog"></i>
                                <span class="nav-label">Blog Posts</span>
                            </router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <strong>{{ title }}</strong>
                        </li>
                    </template>
                </ol>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default {
        name: 'breadcrumbs',
        data() {
            return {
                title: this.$route.name,
                slug: this.$route.path.replace(/\//g, '')
            }
        },
        computed: {
            ...mapState('SERVICES', {
                services: 'all'
            }),
        },
        watch: {
            $route ($to, $from){
                this.title = $to.name;
                if(this.title == 'Dashboard' || !this.services[this.$route.path.replace(/\//g, '')]) return;
            }
        },
        methods: {
            ...mapActions('USERS', [
                'setActiveUser',
                'setActiveUserDetails'
            ]),
            isBlog ($title) {
                if (window.location.href.indexOf('blog') > -1 && $title != 'Blog Posts') {
                    return true;
                }

                return false;
            },
            getActiveUser () {
                if(!_.isEmpty(this.activeUser)) return;

                let self = this;
                let url = '/api/v1/user/active';

                client.get(url)
                .then((response) => {
                    self.setActiveUser(response.data.user);

                    if(!response.data.details) return;
                    self.setActiveUserDetails(response.data.details);
                })
                .catch((error) => {
                    console.log('error ',  error);
                });
            },
        },
        mounted () {
            this.getActiveUser()
        }
    }
</script>
