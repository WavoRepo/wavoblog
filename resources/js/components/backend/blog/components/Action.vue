<template>
    <div class="">
        <div  v-if="post.owner.email == activeUser.email" :class="display">
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
        <div  v-else :class="display">
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
</template>

<script>
import { mapState, mapGetters,  mapActions } from 'vuex';

export default {
    name: 'blog-action',
    props: ['post', 'activeUser', 'display'],
    data() {
        return {}
    },
    methods: {
        ...mapActions('POSTS', [
            'removePost',
            'setSelectedPostById'
        ]),
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
    }
}
</script>

<style lang="css" scoped>
</style>
