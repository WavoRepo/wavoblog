<template>
    <paginate
        v-show="haveList && pageCount > 1 && doPagination"
        v-model="page"
        :page-count="pageCount"
        :page-range="3"
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
        name: 'post-pagination',
        data () {
            return {
                page: 1,
                pageCount: 0,
                haveList: true
            }
        },
        computed: {
            ...mapState('POSTS',[
                'all',
                'paginate',
                'searchMeta',
                'searchResult',
                'perPage',
                'pageNum',
                'doPagination',
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
                        this.updateData (this.$list);
                        return
                    }
                    this.pageCount = 0;
                    return
                }

                this.updateData ($list);
            },
            paginate: function ($paginatePost) {
                this.haveList = false;
                if(_.isEmpty($paginatePost)) return;
                if(!_.isEmpty(this.searchResult)) return this.updateData (this.searchResult);
                this.updateData (this.all);
            },
        },
        methods: {
            ...mapActions('POSTS', [
                'setPageNum'
            ]),
            updateData ($list) {
                if(!$list) return;

                this.haveList = true;
                let pageCount = $list.length / this.perPage;
                this.pageCount = Math.ceil(pageCount);
                this.page = this.pageNum;
            },
            changePage ($page) {
                this.setPageNum($page);
            }
        },
        mounted() {
            if(!_.isEmpty(this.all)) {
                this.updateData (this.all);
            }
        }

    }
</script>

<style>
    .page-item.active a {
        background-color: #1ab394;
        border-color: #1ab394;
    }
</style>
