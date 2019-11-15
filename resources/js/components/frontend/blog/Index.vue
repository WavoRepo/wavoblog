<template>
    <div class="row blog-index">
        <template v-if="havePost" v-for="post of posts">
            <div v-if="post.status == 'Published'" class="col-lg-6">
                <div class="card">
                    <div class="card-header text-left">
                        <h3>{{ post.post_title }}</h3>
                        <div class="row">
                            <div class="small m-b-xs col-md-6 text-left">
                                <h4>
                                    {{ post.owner.name }}
                                    <span class="text-muted">
                                        <i class="fa fa-clock-o"></i>
                                        {{ post.created_at }}
                                    </span></h4>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-right">
                                    <h5>
                                        Category:
                                        <span>
                                            Uncategorized
                                        </span>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-content wrapper">
                        <div class="row">
                            <div class="col-md-5 featured-image-wrap"
                                :style="'background-image: url(' + base_url + '/images/featured_image.png);'">

                                <div v-show="post.featured_image" class="row image-wrap">
                                    <img :src="post.featured_image" alt="">
                                </div>
                            </div>
                            <div class="col-md-7 post-content">
                                <div class="excerpt">
                                    {{ excerpt(post.post_content) }}
                                </div>
                                <router-link :to="'/blog/' + post.post_slug" @click="setSelected(post.id)">
                                    <button class="btn btn-primary btn-xs pull-left mb-2"
                                        type="button"
                                        @click="setSelected(post.id)">

                                        <i class="fa fa-eye"> </i>
                                        Read More
                                    </button>
                                </router-link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <div v-show="!havePost" class="flex-center position-ref" style="height: calc(100vh - 400px); width: 100%;">
            <div class="content">
                <div class="title m-b-md">
                    WavoBlog
                </div>

                <div class="middle-box text-center" style="padding-top: 0;">
                    <h3 class="font-bold">Sorry, no article to display.</h3>
                    <div class="error-desc">
                        Check out our dashboard and start your new blog by adding new post in admin blog page. If there is a post already, please check the status and that should be published. Enjoy our new app.
                    </div>
                </div>

                <div class="">
                    <a :href="base_url + '/admin/blog'" class="btn btn-primary m-t">Post Listing</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default {
        name: 'blog',
        data () {
            return {
                havePost: true,
                posts: [],
                base_url: document.head.querySelector('meta[name="base_url"]').content
            }
        },
        computed: {
            ...mapState('USERS', {
                activeUser: 'active'
            }),
            ...mapState('POSTS', {
                blogPosts: 'all'
            }),
        },
        watch: {
            blogPosts: function ($posts) {
                if(_.isEmpty($posts)) return;
                this.posts = $posts;
            }
        },
        methods: {
            ...mapActions('POSTS', [
                'setPosts',
                'setSelectedPostById'
            ]),
            excerpt ($text) {

                return $text.replace(/(<([^>]+)>)/ig, '').substr(0, 450) + '...';
            },
            isBlog () {
                if (window.location.href.indexOf('blog') > -1 && this.$router.name != 'Blog Posts') {
                    return true;
                }

                return false;
            },
            getAllPosts () {


                let self = this;
                let url = '';
                if(!_.isEmpty(this.activeUser)) {
                    url = '/api/v1/post/?frontpage=true';
                } else {
                    url = '/api/v1/frontend/post/?frontpage=true';
                }

                axios.get(url)
                .then((response) => {
                    console.log(response);
                    if(_.isEmpty(response.data.posts)) {
                        this.havePost = false;
                    };
                    self.setPosts(response.data.posts);
                })
                .catch((error) => {
                    console.log('error: ', error);
                })
            },
            setSelected($id) {
                this.setSelectedPostById($id);
            },
            remove($id) {

            }
        },
        mounted() {
            if(!_.isEmpty(this.blogPosts)) {
                console.log(this.blogPosts);
                this.posts = this.blogPosts;
                return;
            }
            this.getAllPosts();
        }
    }
</script>
