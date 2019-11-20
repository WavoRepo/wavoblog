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
                if(_.isEmpty($list)) {
                    this.haveList = true;
                    this.pageCount = 0;
                    return
                }
                this.updateData ($list);
            },
            searchResult: function ($list) {
                if(_.isEmpty($list)) {
                    if(!_.isEmpty(this.all) && _.isEmpty(this.searchMeta)) {
                        this.updateData (this.all);
                        return
                    }
                    this.haveList = true;
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
                let pageCount = $list.length / this.perPage;
                this.pageCount = Math.round(pageCount);
                this.page = this.pageNum;
                this.haveList = true;
            },
            changePage ($page) {
                this.setPageNum($page);
            }
        },
        mounted() {
        }

    }
</script>

<style>
    .page-item.active a {
        background-color: #1ab394;
        border-color: #1ab394;
    }
</style>
