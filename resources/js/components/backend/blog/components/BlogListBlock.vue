<template>
    <div class="row">
        <div class="col-lg-12">
                <pagination />
        </div>
        <div v-for="post of posts" class="col-lg-4" v-show="showOwnerPost(post.owner.email)">
            <div  v-show="showOwnerPost(post.owner.email)" class="card">
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
import blogAction from './Action';
import Pagination from '../../../../utility/Pagination';

export default {
    name: 'blog-list-block',
    props: ['posts', 'activeUser', 'postOwner'],
    components: {
        blogAction,
        Pagination
    },
    data() {
        return {}
    },
    methods: {
        formatDate($date) {
            return moment($date).format('Do MM YYYY');
        },
        showOwnerPost ($email) {
            if($email != this.activeUser.email && this.postOwner) {
                return false;
            }
            return true;
        },
    }
}
</script>

<style lang="css" scoped>
</style>
