<template>
    <div class="container blog-single article">
        <div class="row">
            <div class="text-center col-lg-12">
                <span v-show="post.created_at" class="text-muted">
                    <i class="fa fa-clock-o"></i>
                    {{ post.created_at  }}
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
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default {
        name: 'single-post',
        data() {
            return {
                post: {
                    owner: {}
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
            getImage($image) {
                return ($image) ? $image + '?' + Math.random() : '';
            },
            getPost($postId) {
                let self = this;
                let url = '/api/v1/post/?id=' + $postId;

                axios.get(url)
                .then((response) => {
                    self.setSelectedPost(response.data.posts);
                })
                .catch((error) => {
                    console.log('error: ', error);
                })
            }
        },
        created () {
            let _post = sessionStorage.getItem('selected-post');

            if(!_.isEmpty(this.blogPost)) {
                this.post = this.blogPost;
            }
            else if(!_.isEmpty(_post)) {
                _post = JSON.parse(_post);
                this.getPost( _post.id);
            } else {
                this.$router.push({name:'Blog Posts'});
            }
        }
    }
</script>

<style lang="css">

</style>
