<template>
    <div class="wrapper" style="background-color: #fff;">
        <pagination />
        <table class="table table-striped table-bordered table-lg">
            <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Owner</th>
                <th>Category</th>
                <th>Status</th>
                <th>DAte Created</th>
                <th style="width: 185px;">Action</th>
            </tr>
            </thead>
            <tbody class="table-hover">
                <tr v-for="(post, index) of posts" v-show="showOwnerPost(post.owner.email)">
                    <td>{{ ++index }}</td>
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
import blogAction from './Action';
import Pagination from '../../../../utility/Pagination';

export default {
    name: 'blog-list-table',
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
