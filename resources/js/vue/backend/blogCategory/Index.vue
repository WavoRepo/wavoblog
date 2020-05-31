<template>
    <div  class="wrapper">
        <add-category />
        <table class="table table-striped table-bordered xtable-responsive table-lg">
            <thead class="thead-light">
            <tr>
                <th v-for="(header, index) of headers" :id="'header_' + header.toLowerCase()">
                    <!-- <span class="header_text" v-html="header"></span>
                    <sorter :sorting="sortBy" :sortby="index" :sortDir="sortDir" @sortBy="orderBy"/> -->
                </th>
                <th id="header_action">Action</th>
            </tr>
            </thead>
            <tbody class="table-hover">
                <tr v-for="(category, index) of categories">
                    <td>{{ category.id }}</td>
                    <td>{{ category.title }}</td>
                    <td>{{ category.owner.name }}</td>
                    <td>Uncategorized</td>
                    <td>{{ category.status }}</td>
                    <td>{{ formatDate(category.created_at) }}</td>
                    <td>
                        <!-- <blog-action :category="category" :activeUser="activeUser"/> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
// import camelCase from 'lodash/camelCase';

// function requireModule () {
//     const requireModule = require.context('./components', true, /\.vue/i)
//     const modules = {};
//
//     requireModule.keys().map(fileName => {
//         if (fileName == './index.js') return;
//
//         const mudoleName = camelCase(
//             fileName.replace(/(\.\/|\.js)/g, '')
//         )
//
//         modules[mudoleName] = requireModule(fileName    )
//     });
//
//     return modules;
// }

export default {
    name: 'blog-category',
    data () {
        return {
            headers: [
                'ID', 'Name', 'Slug', 'Date Created'
            ],
            categories: {}
        }
    },
    methods: {
        getAllCategory: function () {
            let self = this;
            let url = '/api/v1/category';

            // client.get(url)
            // .then((response) => {
            //     console.log(response);
            // })
            // .catch((error) => {
            //     console.log('error ',  error);
            // });
        }
    },
    mounted () {
        this.getAllCategory();
    }
}
</script>

<style lang="css" scoped>
</style>
