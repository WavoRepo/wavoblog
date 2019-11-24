<template>
    <article class="py-4">
        <div v-show="havePost" class="container blog-single article">
            <div class="row">
                <div class="container-fluid text-right">
                    <template v-if="activeUser">
                        <button class="btn btn-white btn-xs" type="button" @click="sentToFrontend">Blog listing</button>
                        <button v-show="hasUser" class="btn btn-white btn-xs" type="button" @click="sentToBackend">Admin Blog listing</button>
                    </template>
                </div>
                <div class="text-center col-lg-12">
                    <span v-show="post.created_at" class="text-muted">
                        <i class="fa fa-clock-o"></i>
                        {{ formatDate(post.created_at)  }}
                    </span>
                    <h1>
                        {{ post.post_title }}
                    </h1>
                </div>
                <div class="col-lg-12 featured-image-wrap">
                    <img v-show="post.featured_image"
                        :src="getImage(post.featured_image)">
                </div>
                <hr style="width: 100%; margin: 2rem 0;">
                <div class="col-lg-12 post-content-wrap" v-html="post.post_content"></div>
            </div>
            <hr>
            <div v-show="post.owner.name" class="row">
                <div class="col-md-6">
                        <h5>Category:</h5>
                        <button class="btn btn-primary btn-xs" type="button">
                            Uncategorized
                        </button>
                </div>
                <div class="col-md-6">
                    <div class="small text-right">
                        <h5>Author:</h5>
                        <div> <i class="fa fa-user"> </i> {{ post.owner.name }} </div>
                    </div>
                </div>
            </div>
        </div>
        <page-not-found v-show="!havePost" />
    </article>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    import PageNotFound from '../../PageNotFound';

    export default {
        name: 'single-post',
        components: {
            PageNotFound
        },
        data() {
            return {
                hasUser: document.head.querySelector('meta[name="has_user"]').content,
                frontpage: document.head.querySelector('meta[name="frontpage"]'),
                post: {
                    owner: {}
                }
            }
        },
        computed: {
            ...mapState('APP', {
                baseUrl: 'baseUrl'
            }),
            ...mapState('USERS', {
                activeUser: 'active'
            }),
            ...mapState('POSTS', {
                blogPost: 'selected'
            }),
        },
        watch: {
            blogPost: function ($post) {
                if(!_.isEmpty($post)) {
                    this.post = $post;
                }
            },
        },
        methods: {
            ...mapActions('POSTS', [
                'setSelectedPost'
            ]),
            formatDate($date) {
                // return moment($date).format('ll');
                return moment($date).format('Do MMMM YYYY');
            },
            getImage($image) {
                return ($image) ? $image + '?' + Math.random() : '';
            },
            sentToFrontend () {
                if(this.hasUser) {
                    if(this.frontpage) {
                        this.$router.push({name:'Blog Posts'});
                    } else {
                        window.location.href = this.baseUrl + '/blog';
                    }
                } else {
                    this.$router.push({name:'Blog Posts'});
                }
            },
            sentToBackend () {
                if(this.frontpage) {
                    window.location.href = this.baseUrl + '/admin/blog';
                } else {
                    this.$router.push({name:'Blog Posts'});
                }
            },
            getPost($postId) {
                let self = this;

                let url = '';
                if(!_.isEmpty(this.activeUser)) {
                    url = '/api/v1/post/?id=' + $postId;
                } else {
                    url = '/api/v1/frontend/post/?id=' + $postId;
                }


                client.get(url)
                .then((response) => {
                    self.setSelectedPost(response.data.posts);
                })
                .catch((error) => {
                    console.log('error: ', error);
                })
            }
        },
        created () {
            this.havePost = true;
            let _post = sessionStorage.getItem('selected-post');

            if(!_.isEmpty(this.blogPost)) {
                this.post = this.blogPost;
                this.havePost = true;
            }
            else if(!_.isEmpty(_post)) {
                _post = JSON.parse(_post);
                if (_post.post_slug != this.$route.params.post) {
                    this.havePost = false;
                    return;
                }
                this.getPost( _post.id);
                this.havePost = true;
            }
            else {
                this.$router.push({name:'Blog Posts'});
            }
        }
    }
</script>

<style lang="css" scoped>
    #app article {
        background-color: #fff;
        min-height: calc(100vh - 96px);
    }
</style>
