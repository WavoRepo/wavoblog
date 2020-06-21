const Vue = require('vue');
const Vuex = require('vuex');
Vue.use(Vuex);

import AppModdule from './modules/app';
import UsersModdule from './modules/users';
import PostsModdule from './modules/posts';
import ServicesModdule from './modules/services';
import CategoriesModdule from './modules/categories';


// export default new Vuex.Store({
const store = new Vuex.Store ({
    modules: {
        APP: AppModdule,
        USERS: UsersModdule,
        POSTS: PostsModdule,
        SERVICES: ServicesModdule,
        CATEGORIES: CategoriesModdule
    }
})

export default store;
