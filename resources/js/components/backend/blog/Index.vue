<template>
    <div class="container blog-index">
        <div class="row">
            <div class="col-lg-12">
                <router-link :to="'/admin/blog/add'">
                    <button v-show="isBlog"
                        type="button"
                        class="btn btn-secondary btn-lg mb-4">

                        Add
                    </button>
                </router-link>
            </div>
        </div>
        <div class="row">
            <div v-for="post of blogPosts" class="col-lg-4">
                <div class="card">
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

                                <i class="fa fa-eye"> </i>
                                Edit
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

                            <i class="fa fa-eye" > </i>
                            Edit
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
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default {
        name: 'blog',
        data () {
            return {

            }
        },
        computed: {
            ...mapState('POSTS', {
                blogPosts: 'all'
            }),
            ...mapState('USERS', {
                activeUser: 'active'
            })
        },
        methods: {
            ...mapActions('POSTS', [
                'setPosts',
                'removePost',
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
            getAllPosts () {

                if(!_.isEmpty(this.blogPosts)) return;

                let self = this;
                let url = '/api/v1/post';

                axios.get(url)
                .then((response) => {
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
            this.getAllPosts();
        }
    }
</script>

<style lang="css" scoped>
    .wrapper {
        padding: 20px;
    }
</style>
