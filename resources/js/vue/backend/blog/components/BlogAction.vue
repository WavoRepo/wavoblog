<template>
    <div class="">
        <div :class="display">
            <router-link v-if="post.owner.email == activeUser.email" :to="'/admin/blog/edit'">
                <button class="btn btn-primary btn-xs"
                    type="button"
                    @click="setSelected(post.id)">

                    <i class="fa fa-pencil"> </i>

                </button>
            </router-link>
            <button v-else class="btn btn-white btn-xs"
                type="button"
                disabled>

                <i class="fa fa-pencil" > </i>

            </button>
            <router-link :to="'/blog/' + post.slug">
                <button class="btn btn-info btn-xs"
                    type="button"
                    @click="setSelected(post.id)">

                    <i class="fa fa-eye"> </i>

                </button>
            </router-link>
            <button v-if="post.owner.email == activeUser.email" class="btn btn-danger btn-xs"
                type="button"
                @click="remove(post.id)">

                <i class="fa fa-trash"> </i>

            </button>
            <button v-else class="btn btn-white btn-xs"
                type="button"
                disabled>

                <i class="fa fa-trash"> </i>

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
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {

                    let formData = new FormData();
                    formData.append('-method', 'delete');

                    let url = '/api/v1/post/' + $id;

                    client.delete(url, formData)
                    .then((response) => {
                        self.removePost($id);

                        self.$swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'The selected blog post was deleted.'
                        })
                    })
                    .catch((error) => {
                        console.log('error ',  error);
                        self.$swal.fire({
                            icon: 'warning',
                            title: 'Oops... ',
                            text: 'Something went wrong! ' + response.data.msg,
                            footer: 'The blog was remove from the list anyway.'
                        })
                    });
                }
            });
        }
    }
}
</script>

<style lang="css" scoped>
</style>
