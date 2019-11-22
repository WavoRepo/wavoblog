<template>
    <div  v-show="hasPost" class="wrapper" style="background-color: #fff;">
        <div class="row">
            <div class="col-lg-2">
                <div v-show="hasPost && doPagination" class="input-group input-group-sm block-order-by">
                    <div class="input-group-prepend">
                        <span class="input-group-addon">Display</span>
                    </div>
                    <select class="form-control" v-model="rowNum" @change="displayRowNum">
                        <option>1</option>
                        <option>3</option>
                        <option>6</option>
                        <option>9</option>
                        <option>12</option>
                        <option>24</option>
                        <option>48</option>
                        <option>96</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-10">
                <pagination />
            </div>
        </div>
        <table class="table table-striped table-bordered xtable-responsive table-lg">
            <thead class="thead-light">
            <tr>
                <th v-for="(header, index) of headers">
                    {{ header }}
                    <sorter :sorting="sortBy" :sortby="index" :sortDir="sortDir" @sortBy="orderBy"/>
                </th>
                <th style="width: 185px;">Action</th>
            </tr>
            </thead>
            <tbody class="table-hover">
                <tr v-for="(post, index) of posts">
                    <td>{{ post.id }}</td>
                    <td>{{ post.post_title }}</td>
                    <td>{{ post.owner.name }}</td>
                    <td>Uncategorized</td>
                    <td>{{ post.status }}</td>
                    <td>{{ formatDate(post.created_at) }}</td>
                    <td>
                        <blog-action :post="post" :activeUser="activeUser"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    import blogAction from './Action';
    import Sorter from '../../../../utility/Sorter';
    import Pagination from '../../../../utility/Pagination';

    export default {
        name: 'blog-list-table',
        props: ['posts', 'headers', 'activeUser', 'hasPost'],
        components: {
            Sorter,
            blogAction,
            Pagination
        },
        data() {
            return {
                rowNum: 96,
            }
        },
        computed: {
            ...mapState('POSTS', [
                'sortBy',
                'sortDir',
                'perPage',
                'doPagination'
            ])
        },
        watch: {
            perPage: function ($perPage) {
                this.rowNum = $perPage;
            }
        },
        methods: {
            ...mapActions('POSTS', [
                'sort',
                'changePerPage'
            ]),
            orderBy ($data) {

                if($data.by == 'index') {
                    this.posts = this.posts.reverse();
                }
                this.sort($data);
            },
            formatDate($date) {
                return moment($date).format('Do MM YYYY');
            },
            displayRowNum () {
                this.changePerPage(parseInt(this.rowNum));
            },
        },
        mounted () {
            this.rowNum = this.perPage;
        }
    }
</script>

<style lang="css" scoped>
</style>
