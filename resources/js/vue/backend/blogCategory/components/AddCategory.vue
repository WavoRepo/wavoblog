<template>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                Add Category
            </div>
            <div class="card-body">
                <form class="no-borders">
                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">Name</label>
                        <div class="col-md-8">
                            <input required="required" class="form-control" v-model="category.name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-8">
                            <textarea class="form-control" v-model="category.description"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Parent</label>
                        <div class="col-md-8">
                            <select class="form-control" v-model="category.parent_id">
                                <option value="0">No Parent</option>
                                <option :value="category.id" v-for="category in categories"> {{category.name}}</option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="form-group row mb-2">
                    <div class="col-md-6 offset-md-4">
                        <button class="btn btn-primary" @click="submitForm">
                            Add New
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapState, mapActions} from 'vuex'

export default {
    data () {
        return {
            category: {
                parent_id: 0,
                taxonomy: 'post-category'
            },
        }
    },
    computed: {
        ...mapState('CATEGORIES', {
            categories: 'all'
        }),
    },
    methods: {
        ...mapActions('CATEGORIES', [
            'addCategory'
        ]),
        submitForm: function () {
            let self = this;

            let formData = new FormData();
            Object.keys(this.category).map(function(key) {
                formData.append(key, self.category[key]);
            });

            this.addCategory(this.category)
            .then(response => {
                self.$swal.fire({
                    icon: 'success',
                    title: 'Done Adding New Category',
                });
            })
            .catch(error => {
                let html = error.response.data.message;
                if(error.response.data.errors){
                    html += '<ul style="list-style-type: none;">';
                    const err = Object.values(error.response.data.errors);
                    for (const _err of err) {
                        html += `<li>${_err}</li>`;
                    }
                    html += '</ul>';
                }
                self.$swal.fire({
                    icon: 'warning',
                    title: 'Opsss...',
                    html: html
                })
            })
        }
    }
}
</script>

<style lang="css" scoped>
</style>
