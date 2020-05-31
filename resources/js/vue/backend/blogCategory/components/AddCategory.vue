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
                        <div class="col-md-6">
                            <input required="required" class="form-control" v-model="category.name">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Description</label>
                        <div class="col-md-6">
                            <textarea class="form-control" v-model="category.description"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Parent</label>
                        <div class="col-md-6">
                            <select class="form-control" v-model="category.parent_id">
                                <option value="" v-for="category in categories"> category.name</option>
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
export default {

    data () {
        return {
            category: {},
            categories: {}
        }
    },
    methods: {
        submitForm: function () {
            let self = this;
            console.log(this.category);

            let formData = new FormData();
            Object.keys(this.category).map(function(key) {
                formData.append(key, self.category[key]);
            });

            client.post('/api/v1/category', formData)
            .then(response => {
                console.log(response);
            })
            .catch(error => {
                console.log(error);
            })
        }
    }
}
</script>

<style lang="css" scoped>
</style>
