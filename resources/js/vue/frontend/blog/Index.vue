<template>
    <div class="row blog-index">
        <div class="col-lg-12">
            <div class="input-group search_wrap admin_search_wrap" style="width: calc(100% - 178px); right: 162px;">
                <input type="text"
                    placeholder="Search By Title, Post Owner's Name, Content, Date(yyyy-mm-dd), Slug, And Status"
                    v-model="search"
                    class="form-control form-control-lg">
                <div class="input-group-btn">
                    <button class="btn btn-lg btn-primary" @click="searchPost">
                        Search
                    </button>
                </div>
            </div>
            <button class="btn btn-secondary btn-lg pull-right" @click="block = !block">
                <i class="fa fa-th-large"></i>
                <strong>Display: </strong>
                <span style="text-align: center; min-width: 40px; display: inline-block;" v-html="blockBtnText"></span>
            </button>
        </div>
        <div :class="'col-lg-12 mb-4 meta_wrap' + hasMeta()">
            <button v-for="meta of metas" type="button" class="btn btn-secondary btn-sm mr-2">
                {{ meta }} <span class="badge badge-warning" @click="removeTheMeta(meta)"><i class="fa fa-times"></i></span>
            </button>
        </div>
        <div class="col-lg-12 pagination_wrap">
                <post-pagination />
        </div>
        <template v-if="havePost" v-for="post of posts">
            <div v-if="post.status == 'Published'" :class="'col-lg-' + blockWidth">
                <div class="card">
                    <div class="card-header text-left">
                        <h3>{{ post.title }}</h3>
                        <div class="row">
                            <div class="small m-b-xs col-md-6 text-left">
                                <h4>
                                    {{ post.owner.name }}
                                    <span class="text-muted">
                                        <i class="fa fa-clock-o"></i>
                                        {{ formatDate(post.created_at) }}
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
                                <div class="excerpt" v-html="excerpt(post.content, excerptCount)">

                                </div>
                                <router-link :to="'/blog/' + post.slug" @click="setSelected(post.id)">
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
        <div v-show="!havePost" class="flex-center position-ref no-article-display">
            <div class="content">
                <div class="title m-b-md">
                    {{ app_name }}
                </div>

                <div class="middle-box text-center">
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
        <div v-show="!hasResult" class="flex-center position-ref no-result-display">
            <div class="content">
                <div class="middle-box text-center wrapper">
                    <h3 class="font-bold">Sorry, no result to display.</h3>
                    <div class="error-desc">
                        There's no result for search: <span v-for="meta of metas"> <strong>{{ meta }}</strong> - </span>. Try another search.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    import PostPagination from '../../../utility/PostPagination';

    export default {
        name: 'blog',
        components: {
            PostPagination
        },
        data () {
            return {
                havePost: true,
                hasResult: true,
                posts: [],
                search: '',
                base_url: document.head.querySelector('meta[name="base_url"]').content,
                app_name: document.head.querySelector('meta[name="app_name"]').content,
                block: false,
                blockWidth: 12,
                blockBtnText: 'Full',
                excerptCount: 650,
            }
        },
        computed: {
            ...mapState('USERS', {
                activeUser: 'active'
            }),
            ...mapState('POSTS', {
                blogPosts: 'all',
                metas: 'searchMeta',
                results: 'searchResult',
                paginatePost: 'paginate'
            }),
        },
        watch: {
            block: function ($block) {
                this.toggleBlock($block);
                sessionStorage.setItem('toggle-block', $block);
            },
            blogPosts: function ($posts) {
                if(_.isEmpty($posts)) return;
                this.posts = $posts;
            },
            results: function ($post) {
                if(_.isEmpty($post) && _.isEmpty(this.metas)) {
                    this.hasResult = true;
                    this.posts = this.blogPosts;
                    return;
                }
                if(_.isEmpty($post) && !_.isEmpty(this.metas)) {
                    this.hasResult = false;
                    this.posts = $post;
                    return;
                }
                this.hasResult = true;
                this.posts = $post;
            },
            paginatePost: function ($paginatePost) {
                if(_.isEmpty($paginatePost)) return;
                this.hasResult = true;
                this.posts = $paginatePost;
            },
        },
        methods: {
            ...mapActions('POSTS', [
                'setPosts',
                'searchPosts',
                'removeMeta',
                'setSelectedPostById'
            ]),
            hasMeta () {
                if(!_.isEmpty(this.metas)) return ' mt-4';
                return '';
            },
            toggleBlock($block) {
                if(!$block) {
                    this.blockWidth = 6;
                    this.excerptCount = 350;
                    this.blockBtnText = 'Block';
                } else {
                    this.blockWidth = 12;
                    // this.excerptCount = 1400;
                    this.excerptCount = 650;
                    this.blockBtnText = 'Full';
                }
            },
            formatDate($date) {
                // return moment($date).format('ll');
                return moment($date).format('Do MM YYYY hh:mm');
            },
            excerpt ($text, excerptCount) {
                if(!$text) return $text;
                // return $text.replace(/(<([^>]+)>)/ig, '').substr(0, excerptCount) + '...';
                return $text.replace(/<img[^>]*>/g, '').substr(0, excerptCount) + '...';
            },
            isBlog () {
                if (window.location.href.indexOf('blog') > -1 && this.$router.name != 'Blog Posts') {
                    return true;
                }

                return false;
            },
            searchPost () {
                this.searchPosts(this.search);
                this.search = '';
            },
            removeTheMeta ($meta) {
                this.removeMeta($meta);
                this.searchPosts('');
            },
            getAllPosts () {

                let self = this;
                let url = '';
                if(!_.isEmpty(this.activeUser)) {
                    url = '/api/v1/post/?frontpage=true';
                } else {
                    url = '/api/v1/frontend/post/?frontpage=true';
                }
                url = '/api/v1/post/published';

                client.get(url)
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
            // Check if display is block or full
            let toggleBlock = sessionStorage.getItem('toggle-block');
            toggleBlock = (!toggleBlock) ? true :(toggleBlock === 'true' ) ? true : false;
            this.block = toggleBlock;
            this.toggleBlock(toggleBlock);

            // Populate post
            if(!_.isEmpty(this.paginatePost)) {
                this.havePost = true;
                this.posts = this.paginatePost;
                return;
            }
            if(!_.isEmpty(this.results)) {
                this.havePost = true;
                this.posts = this.results;
                return;
            }
            if(!_.isEmpty(this.blogPosts)) {
                this.havePost = true;
                this.posts = this.blogPosts;
                return;
            }

            this.getAllPosts();
        }
    }
</script>
