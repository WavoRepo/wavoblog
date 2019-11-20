<template>
    <paginate
        v-show="haveList && pageCount"
        v-model="page"
        :page-count="pageCount"
        :page-range="3"
        :margin-pages="perPage"
        :click-handler="changePage"
        :prev-text="'Prev'"
        :next-text="'Next'"
        :container-class="'pagination justify-content-end'"
        :page-class="'page-item'">
    </paginate>
</template>

<script>
    import { mapState, mapGetters,  mapActions } from 'vuex';

    export default {
        name: 'pagination',
        data () {
            return {
                page: 1,
                pageCount: 0,
                haveList: false
            }
        },
        computed: {
            ...mapState('POSTS',[
                'all',
                'searchMeta',
                'searchResult',
                'perPage',
                'pageNum',
            ])
        },
        watch: {
            all: function ($list) {
                this.haveList = false;
                if(_.isEmpty($list)) {
                    this.pageCount = 0;
                    return
                }

                this.updateData ($list);
            },
            searchResult: function ($list) {
                this.haveList = false;
                if(_.isEmpty($list)) {
                    if(!_.isEmpty(this.all) && _.isEmpty(this.searchMeta)) {
                        this.updateData (this.all);
                        return
                    }
                    this.pageCount = 0;
                    return
                }

                this.updateData ($list);
            }
        },
        methods: {
            ...mapActions('POSTS', [
                'setPageNum'
            ]),
            updateData ($list) {
                this.haveList = true;
                let pageCount = $list.length / this.perPage;
                this.pageCount = Math.round(pageCount);
                this.page = this.pageNum;
            },
            changePage ($page) {
                this.setPageNum($page);
            }
        },
        mounted() {
            if(!_.isEmpty(this.paginatePost)) {
            this.haveList = true;
                this.posts = this.paginatePost;
                return;
            }
            if(!_.isEmpty(this.results)) {
            this.haveList = true;
                this.posts = this.results;
                return;
            }
            if(!_.isEmpty(this.all)) {
                this.haveList = true;
                this.updateData (this.all);
                return;
            }
            this.haveList = false;
        }

    }
</script>

<style>
    .page-item.active a {
        background-color: #1ab394;
        border-color: #1ab394;
    }
</style>
