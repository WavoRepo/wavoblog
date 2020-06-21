<template>
    <div class="">
        <div>
            <template  v-if="id === category.id">
                <button class="btn btn-warning btn-xs" type="button" @click="setSelected(null)">
                    <i class="fa fa-ban"> </i>
                </button>
                <button class="btn btn-primary btn-xs" type="button" @click="update">
                    <i class="fa fa-check"> </i>
                </button>
            </template>
            <button v-else class="btn btn-primary btn-xs" type="button" @click="setSelected(id)">
                <i class="fa fa-pencil"> </i>
            </button>
            <button class="btn btn-danger btn-xs" type="button" @click="remove(id)">
                <i class="fa fa-trash"> </i>
            </button>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters,  mapActions } from 'vuex';

export default {
    name: 'category-action',
    props: ['id', 'category'],
    data() {
        return {

        }
    },
    methods: {
        ...mapActions('CATEGORIES', [
            'updateCategory',
            'removeCategory',
            'setSelectedCategory',
            'setSelectedCategoryById',
            'updateChildCategoryAfterParentRemove'
        ]),
        setSelected($id) {
            this.setSelectedCategoryById($id);
        },
        update () {
            let self = this;

            let formData = new FormData();
            Object.keys(this.category).map(function(key) {
                formData.append(key, self.category[key]);
            });


            this.updateCategory(formData)
            .then((response) => {

                self.setSelected(null);

                self.$swal.fire({
                    icon: 'success',
                    title: 'Done Updating',
                });
            })
            .catch((error) => {
                console.log('error ',  error);

                self.$swal.fire({
                    icon: 'warning',
                    title: 'Oops... ',
                    text: 'Something went wrong! ' + error.response.data.msg,
                })
            });
        },
        remove($id) {

            let self = this

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

                    let url = '/api/v1/category/' + $id;

                    client.delete(url, formData)
                    .then((response) => {

                        self.removeCategory($id);
                        self.updateChildCategoryAfterParentRemove($id);

                        self.$swal.fire({
                            icon: 'success',
                            title: 'Deleted',
                            text: response.data.msg,
                            footer: ''
                        });
                    })
                    .catch((error) => {
                        console.log('error ',  error);

                        self.$swal.fire({
                            icon: 'warning',
                            title: 'Oops... ',
                            text: 'Something went wrong! ' + error.response.data.msg,
                            footer: 'The category was remove from the list anyway.'
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
