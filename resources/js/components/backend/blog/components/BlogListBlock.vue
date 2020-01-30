<template>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-3">
                    <div v-show="hasPost" class="input-group input-group-sm block-order-by">
                        <div class="input-group-prepend">
                            <span class="input-group-addon">Order By</span>
                        </div>
                        <select class="form-control" v-model="sortedBy">
                            <option v-for="(header, index) of headers" :value="index" v-html="header"></option>
                        </select>
                        <div class="input-group-append bg-primary">
                                <sorter :sorting="sortBy" :sortby="sortedBy" :sortDir="sortDir" @sortBy="orderBy" />
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div v-show="hasPost && doPagination" class="input-group input-group-sm" style="margin-bottom: 30px;">
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
                <div class="col-lg-7">
                    <post-pagination />
                </div>
            </div>
        </div>
        <div v-for="post of posts" class="col-lg-4">
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
                <blog-action :display="'card-footer'" :post="post" :activeUser="activeUser"/>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';
    import blogAction from './Action';
    import Sorter from '../../../../utility/Sorter';
    import PostPagination from '../../../../utility/PostPagination';

    export default {
        name: 'blog-list-block',
        props: ['posts', 'activeUser', 'headers', 'hasPost'],
        components: {
            Sorter,
            blogAction,
            PostPagination
        },
        data() {
            return {
                rowNum: 96,
                sortedBy: 'index'
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
            sortBy: function ($sortBy) {
                this.sortedBy = $sortBy;
            },
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
