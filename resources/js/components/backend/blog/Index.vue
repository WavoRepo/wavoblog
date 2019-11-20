<template>
    <div class="container py-4 blog-index">
        <div class="row">
            <div class="col-lg-8" style="position: relative;">
                <router-link :to="'/admin/blog/add'">
                    <button v-show="isBlog"
                        type="button"
                        class="btn btn-secondary btn-lg xmb-4">

                        Add
                    </button>
                </router-link>
                <div class="input-group" style="position: absolute; top: 0; width: calc(100% - 216px); right: 14px;">
                    <input type="text"
                        placeholder="Search By Title, Content, Date(yyyy-mm-dd), Slug, And Status"
                        v-model="search"
                        class="form-control form-control-lg" style="font-size: 0.8rem;">
                    <div class="input-group-btn">
                        <button class="btn btn-lg btn-primary" @click="searchPost">
                            Search
                        </button>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-lg xmb-4" @click="ownersPost">
                    {{ postOwnerText }}
                </button>
            </div>
        </div>
        <div class="row col-lg-10" style=" z-index: 10; position: relative;">
            <div class="col-lg-12 mt-4 mb-4">
                <button v-for="meta of metas" type="button" class="btn btn-primary btn-sm mr-2">
                    {{ meta }} <span class="badge badge-light" @click="removeTheMeta(meta)"><i class="fa fa-times"></i></span>
                </button>
            </div>
        </div>
        <div class="row" style="margin-top: -40px;">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs" style="direction: rtl; padding: 0;">
                        <li><a class="nav-link active" data-toggle="tab" href="#blog-block"> <i class="fa fa-th-large"></i></a></li>
                        <li><a class="nav-link" data-toggle="tab" href="#blog-table"><i class="fa fa-th-list"></i></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="blog-block" class="tab-pane active">
                            <div class="panel-body" style="background-color: transparent; border: none; padding: 20px 0;">
                                <blog-list-block :posts="posts" :activeUser="activeUser" :postOwner="postOwner"/>
                            </div>
                        </div>
                        <div id="blog-table" class="tab-pane">
                            <div class="panel-body" style="background-color: transparent; border: none; padding: 20px 0;">
                                <blog-list-table :posts="posts" :activeUser="activeUser" :postOwner="postOwner"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div v-show="!hasResult" class="flex-center position-ref" style="height: calc(100vh - 270px); width: 100%;">
                <div class="content wrapper">
                    <div class="middle-box text-center wrapper" style="background-color: #fff;">
                        <h3 class="font-bold">Sorry, no result to display.</h3>
                        <div class="error-desc">
                            Start your new blog by clicking the add button. If there is a post already, maybe the search result is empty. Enjoy our new app.
                        </div>
                    </div>
                </div>
            </div>
            <div id="owner_msg" class="flex-center position-ref" style="height: calc(100vh - 270px); width: 100%; display: none;">
                <div class="content wrapper">
                    <div class="middle-box text-center wrapper" style="background-color: #fff;">
                        <h3 class="font-bold">Sorry, no result to display.</h3>
                        <div class="error-desc">
                            You don't owned a post on this result. Start your new blog by clicking the add button. Enjoy our new app.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    import blogListTable from './components/BlogListTable';
    import BlogListBlock from './components/BlogListBlock';

    export default {
        name: 'blog',
        components: {
            blogListTable,
            BlogListBlock
        },
        data () {
            return {
                hasResult: true,
                postOwner: false,
                postOwnerText: 'Owner\'s Posts',
                search: '',
                posts: {},
            }
        },
        computed: {
            ...mapState('POSTS', {
                blogPosts: 'all',
                paginatePost: 'paginate',
                metas: 'searchMeta',
                results: 'searchResult'
            }),
            ...mapState('USERS', {
                activeUser: 'active'
            })
        },
        watch: {
            blogPosts: function ($post) {
                if(_.isEmpty($post) || !_.isEmpty(this.paginatePost)) return;
                this.hasResult = true;
                this.posts = $post;
            },
            results: function ($post) {
                this.CheckResultsOwnerIsActive();
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
                'removeMeta',
                'searchPosts'
            ]),
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
            ownersPost () {
                this.postOwner = !this.postOwner;
                if(!this.postOwner) {
                    this.postOwnerText = 'Owner\'s Posts';
                } else {
                    this.postOwnerText = 'Show All Posts';
                }
                this.CheckResultsOwnerIsActive();
            },
            CheckResultsOwnerIsActive() {
                let self = this;
                let visible = false;
                $('#owner_msg').hide();

                setTimeout(function(){
                    $('.card').each(function() {
                        if($(this).css('display') != 'none') {
                            visible = true;
                        }
                    });
                    if(!visible && self.postOwner && self.hasResult) {
                        $('#owner_msg').show();
                    }
                }, 1000);
            },
            getAllPosts () {

                let self = this;
                let url = '/api/v1/post';

                axios.get(url)
                .then((response) => {
                    if(_.isEmpty(response.data.posts)) {
                        self.hasResult = true;
                        return;
                    }
                    self.setPosts(response.data.posts);
                })
                .catch((error) => {
                    console.log('error: ', error);
                })
            }
        },
        mounted() {
            if(!_.isEmpty(this.paginatePost)) {
                this.posts = this.paginatePost;
                return;
            }
            if(!_.isEmpty(this.results)) {
                this.posts = this.results;
                return;
            }
            if(!_.isEmpty(this.blogPosts)) {
                this.posts = this.blogPosts;
                return;
            }
            this.getAllPosts();
        }
    }
</script>

<style lang="css" scoped>
    .wrapper {
        padding: 20px;
    }
</style>
