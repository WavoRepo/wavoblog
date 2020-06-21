<template>
    <div  class="container py-4">
        <div class="row">
            <div class="col-md-4">
<add-category :categories="categories" />
            </div>
            <div class="col-md-8">
                <table class="table table-striped table-bordered table-responsive table-lg bg-white">
                    <thead class="thead-light">
                    <tr>
                        <th v-for="(header, index) of headers" :id="'header_' + header.toLowerCase()">
                            <span class="header_text" v-html="header"></span>
                            <!-- <sorter :sorting="sortBy" :sortby="index" :sortDir="sortDir" @sortBy="orderBy"/> -->
                        </th>
                        <th id="header_action">Action</th>
                    </tr>
                    </thead>
                    <tbody class="table-hover">
                        <tr v-for="(category, index) in categories">
                            <td>{{ category.id }}</td>
                            <td>
                                <span v-show="selected.id != category.id" >{{ category.name }}</span>
                                <div  v-show="selected.id == category.id" contentEditable class="editable-name" v-text="selected.name" @blur="onEdit"></div>
                            </td>
                            <td style="width: 220px">
                                <span v-show="selected.id != category.id">{{ category.description }}</span>
                                <div  v-show="selected.id == category.id" contentEditable class="editable-description"  v-text="selected.description" @blur="onEdit"></div>
                            </td>
                            <td>
                                <span v-show="selected.id != category.id">{{ category.parent_id }}</span>
                                <select v-show="selected.id == category.id" class="form-control" v-model="categoryToUpdate.parent_id">
                                    <option value="0">No Parent</option>
                                    <option v-for="category in categories" :value="category.id"> {{category.name}}</option>
                                </select>
                            </td>
                            <td>{{ category.slug }}</td>
                            <td>{{ formatDate(category.created_at) }}</td>
                            <td style="width:98px; text-align: center;">
                                <category-action :id="category.id" :category="categoryToUpdate"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';
import {mapActions, mapState, mapGetters} from 'vuex';
// import camelCase from 'lodash/camelCase';

// function requireModule () {
//     const requireModule = require.context('./components', true, /\.vue$/)
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
                'ID', 'Name', 'Description', 'Parent ID', 'Slug', 'Date Created'
            ],
            categoryToUpdate: {},
        }
    },
    computed: {
        ...mapState('CATEGORIES', {
            categories: 'all',
            selected: 'selected'
        }),
    },
    watch: {
        selected: function ($selected) {
            this.categoryToUpdate = $selected;
        }
    },
    methods: {
        ...mapActions('CATEGORIES', [
            'setcategories'
        ]),
        formatDate($date) {
            return moment($date).format('Do MMM, YYYY');
        },
        onEdit(evt){
            if (evt.target.className == 'editable-name') this.categoryToUpdate.name = evt.target.innerText;
            if (evt.target.className == 'editable-description') this.categoryToUpdate.description = evt.target.innerText;
        }
    },
    mounted () {
        this.setcategories();
    }
}
</script>

<style lang="css" scoped>
.editable-name,
.editable-description {
    outline: none;
    display: block;
    padding: 8px 6px;
    border: 1px solid #1ab394;
}
.editable-name:focus,
.editable-description:focus {
    border: 1px solid #1ab394;
}
</style>
