<template>
    <div class="container py-4 blog-index">
        <div class="row">
            <div class="col-lg-12 add_btn_wrap">
                <router-link :to="'/admin/blog/add'">
                    <button v-show="isBlog"
                        type="button"
                        class="btn btn-secondary btn-lg xmb-4">

                        Add
                    </button>
                </router-link>
                <div class="input-group admin_search_wrap">
                    <input type="text"
                        placeholder="Search By Title, Post Owner's Name,  Content, Date(yyyy-mm-dd), Slug, And Status"
                        v-model="search"
                        class="form-control form-control-lg">
                    <div class="input-group-btn">
                        <button class="btn btn-lg btn-primary" @click="searchPost">
                            Search
                        </button>
                    </div>
                </div>
                <button class="btn btn-secondary btn-lg pull-right" @click="paginate">
                    Pagination <i :class="isPaginated"></i>
                </button>
            </div>
        </div>
        <div class="row col-lg-8 admin_meta_wrap">
            <div :class="'col-lg-12 mt-4' + hasMeta()">
                <button v-for="meta of metas" type="button" class="btn btn-primary btn-sm mr-2">
                    {{ meta }} <span class="badge badge-light" @click="removeTheMeta(meta)"><i class="fa fa-times"></i></span>
                </button>
            </div>
        </div>
        <div class="row tab_wrap">
            <div class="col-lg-12">
                <div class="tabs-container">
                    <ul class="nav nav-tabs">
                        <li>
                            <a :class="'nav-link' + activeTab('block')" data-toggle="tab" href="#blog-block" @click="setActiveTab('block')">
                                <i class="fa fa-th-large"></i>
                            </a>
                        </li>
                        <li>
                            <a :class="'nav-link' + activeTab('table')" data-toggle="tab" href="#blog-table" @click="setActiveTab('table')">
                                <i class="fa fa-th-list"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="blog-block" :class="'tab-pane' + activeTab('block')">
                            <div class="panel-body">
                                <blog-list-block :posts="posts" :headers="headers" :activeUser="activeUser" :hasPost="hasResult" />
                            </div>
                        </div>
                        <div id="blog-table" :class="'tab-pane' + activeTab('table')">
                            <div class="panel-body">
                                <blog-list-table :posts="posts" :headers="headers" :activeUser="activeUser" :hasPost="hasResult" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div v-show="!hasResult" class="flex-center position-ref admin-no-article-display">
                <div class="content wrapper">
                    <div class="middle-box text-center wrapper">
                        <h3 class="font-bold">Sorry, no result to display.</h3>
                        <div class="error-desc">
                            Start your new blog by clicking the add button. If there is a post already, maybe the search result is empty. Enjoy our new app.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    // import blogListTable from './components/BlogListTable';
    // import BlogListBlock from './components/BlogListBlock';

    export default {
        name: 'blog',
        // components: {
        //     blogListTable,
        //     BlogListBlock
        // },
        data () {
            return {
                hasResult: true,
                search: '',
                posts: {},
                tabActive: (sessionStorage.getItem('tab-active')) ? sessionStorage.getItem('tab-active') : 'table',
                headers: {
                    index: 'Id',
                    post_title: 'Title',
                    owner: 'Owner',
                    category: 'Category',
                    status: 'Status',
                    created_at: 'Date&nbsp;Created'
                },
                isPaginated: 'fa fa-ban text-info'
            }
        },
        computed: {
            ...mapState('POSTS', {
                blogPosts: 'all',
                metas: 'searchMeta',
                sortedPost: 'sorted',
                results: 'searchResult',
                paginatePost: 'paginate',
                doPagination: 'doPagination'
            }),
            ...mapState('USERS', {
                activeUser: 'active'
            })
        },
        watch: {
            blogPosts: function ($post) {
                if (_.isEmpty($post) || !_.isEmpty(this.paginatePost)) return;
                this.hasResult = true;
                this.posts = $post;
            },
            results: function ($post) {
                if (_.isEmpty($post) && _.isEmpty(this.metas)) {
                    this.hasResult = true;
                    this.posts = this.blogPosts;
                    return;
                }
                if (_.isEmpty($post) && !_.isEmpty(this.metas)) {
                    this.hasResult = false;
                    this.posts = $post;
                    return;
                }
                this.hasResult = true;
                this.posts = $post;
            },
            sortedPost: function ($sortedPost) {
                if (_.isEmpty($sortedPost)) return;
                this.hasResult = true;
                this.posts = $sortedPost;
            },
            paginatePost: function ($paginatePost) {
                this.hasResult = true;
                this.posts = $paginatePost;
            },
            doPagination: function ($doPagination) {
                if (!$doPagination) {
                    if (!_.isEmpty(this.sortedPost)) {
                        this.hasResult = true;
                        this.posts = this.sortedPost;
                        return;
                    }
                    if (!_.isEmpty(this.results)) {
                        this.hasResult = true;
                        this.posts = this.results;
                        return;
                    }
                    if (!_.isEmpty(this.blogPosts)) {
                        this.hasResult = true;
                        this.posts = this.blogPosts;
                        return;
                    }
                    this.hasResult = false;
                    return;
                }
            }
        },
        methods: {
            // Config,
            ...mapActions('POSTS', [
                'setPosts',
                'removeMeta',
                'searchPosts',
                'togglePagination'
            ]),
            hasMeta () {
                if (!_.isEmpty(this.metas)) return ' mt-4';
                return '';
            },
            isBlog () {
                if (window.location.href.indexOf('blog') > -1 && this.$router.name != 'Blog Posts') {
                    return true;
                }

                return false;
            },
            setActiveTab ($tab) {
                sessionStorage.setItem('tab-active', $tab);
            },
            activeTab ($tab) {
                if ($tab == this.tabActive) return ' active';
                return ''
            },
            searchPost () {
                this.searchPosts(this.search);
                this.search = '';
            },
            removeTheMeta ($meta) {
                this.removeMeta($meta);
                this.searchPosts('');
            },
            paginate () {
                this.togglePagination();
                if (this.doPagination) {
                    this.isPaginated = 'fa fa-check text-info';
                }
                else {
                    this.isPaginated = 'fa fa-ban text-info';
                }
            },
            setPaginationBtnIcon () {
                if (this.doPagination) {
                    this.isPaginated = 'fa fa-check text-info';
                    return;
                }

                this.isPaginated = 'fa fa-ban text-info';
            },
            postAlreadyFetch() {
                if (!_.isEmpty(this.paginatePost) && this.doPagination) {
                    this.posts = this.paginatePost;
                    return true;
                }
                if (!_.isEmpty(this.$sortedPost)) {
                    this.posts = this.$sortedPost;
                    return true;
                }
                if (!_.isEmpty(this.results)) {
                    this.posts = this.results;
                    return true;
                }
                if (!_.isEmpty(this.blogPosts)) {
                    this.posts = this.blogPosts;
                    return true;
                }
                return false;
            },
            getAllPost () {

                let self = this;
                let url = '/api/v1/post';

                client.get(url)
                .then((response) => {
                    if (_.isEmpty(response.data.posts)) {
                        self.hasResult = true;
                        return;
                    }
                    self.setPosts(response.data.posts);
                })
                .catch((error) => {
                    console.log('error ',  error);
                });
            },
        },
        mounted() {
            this.setPaginationBtnIcon();
            if (!this.postAlreadyFetch()) this.getAllPost();
        }
    }
</script>
