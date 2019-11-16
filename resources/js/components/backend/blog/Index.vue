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
                        placeholder="Search By Title, Content, Date, Slug, And Status"
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
        <div class="row">
            <div class="col-lg-12 mt-4 mb-4">
                <button v-for="meta of metas" type="button" class="btn btn-primary btn-sm mr-2">
                    {{ meta }} <span class="badge badge-light" @click="removeTheMeta(meta)"><i class="fa fa-times"></i></span>
                </button>
            </div>
        </div>
        <div class="row">
            <div v-for="post of posts" class="col-lg-4">
                <div  v-show="showOwnerPost(post.owner.email)" class="card">
                    <div class="card-header">
                        <h4>{{ post.post_title }}</h4>
                    </div>
                    <div class="card-content wrapper">
                        <div class="small m-b-xs">
                            <h4>
                                {{ post.owner.name }}
                                <span class="text-muted">
                                    <i class="fa fa-clock-o"></i>
                                    {{ formatDate(post.created_at) }}
                                </span>
                            </h4>
                        </div>
                        <!-- <div v-html="post.post_content"></div> -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="small text-left">
                                    <h5>Category:</h5>
                                    Uncategorized
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="small text-right">
                                    <h5>Status:</h5>
                                    {{ post.status }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div  v-if="post.owner.email == activeUser.email" class="card-footer">
                        <router-link :to="'/admin/blog/edit'">
                            <button class="btn btn-primary btn-xs"
                                type="button"
                                @click="setSelected(post.id)">

                                <i class="fa fa-pencil"> </i>
                                Edit
                            </button>
                        </router-link>
                        <router-link :to="'/blog/' + post.post_slug">
                            <button class="btn btn-info btn-xs"
                                type="button"
                                @click="setSelected(post.id)">

                                <i class="fa fa-eye"> </i>
                                View
                            </button>
                        </router-link>
                        <button class="btn btn-white btn-xs"
                            type="button"
                            @click="remove(post.id)">

                            <i class="fa fa-trash"> </i>
                            Trash
                        </button>
                    </div>
                    <div  v-else class="card-footer">
                        <button class="btn btn-primary btn-xs"
                            type="button"
                            disabled>

                            <i class="fa fa-pencil" > </i>
                            Edit
                        </button>
                        <button class="btn btn-info btn-xs"
                            type="button"
                            disabled>

                            <i class="fa fa-eye"> </i>
                            View
                        </button>
                        <button class="btn btn-white btn-xs"
                            type="button"
                            disabled>

                            <i class="fa fa-trash"> </i>
                            Trash
                        </button>
                    </div>
                </div>
            </div>
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

    export default {
        name: 'blog',
        data () {
            return {
                hasResult: true,
                postOwner: false,
                postOwnerText: 'Owner\'s Posts',
                search: '',
                posts: {}
            }
        },
        computed: {
            ...mapState('POSTS', {
                blogPosts: 'all',
                metas: 'searchMeta',
                results: 'searchResult'
            }),
            ...mapState('USERS', {
                activeUser: 'active'
            })
        },
        watch: {
            blogPosts: function ($post) {
                if(_.isEmpty($post)) return;
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
            }
        },
        methods: {
            ...mapActions('POSTS', [
                'setPosts',
                'removePost',
                'removeMeta',
                'searchPosts',
                'setSelectedPostById'
            ]),
            formatDate($date) {
                // return moment($date).format('ll');
                return moment($date).format('Do MM YYYY');
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
            ownersPost () {
                this.postOwner = !this.postOwner;
                if(!this.postOwner) {
                    this.postOwnerText = 'Owner\'s Posts';
                } else {
                    this.postOwnerText = 'Show All Posts';
                }
                this.CheckResultsOwnerIsActive();
            },
            showOwnerPost ($email) {
                if($email != this.activeUser.email && this.postOwner) {
                    return false;
                }
                return true;
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
                    if(!visible && self.postOwner) {
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
            },
            setSelected($id) {
                this.setSelectedPostById($id);
            },
            remove($id) {

                let self = this;

                this.$swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {

                        let formData = new FormData();
                        formData.append('-method', 'delete');

                        axios.delete('/api/v1/post/' + $id, formData)
                        .then((response) => {

                            self.removePost($id);

                            self.$swal.fire(
                                'Deleted!',
                                'Post has been deleted.',
                                'success'
                            )
                        })
                        .catch((error) => {
                            console.log('error: ', error);
                        })
                    }
                });
            }
        },
        mounted() {
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
