
import Dashboard from './vue/backend/Dashboard';
import Users from './vue/backend/users/Index';
import Blog from './vue/backend/blog/Index';
import AddBlog from './vue/backend/blog/Add';
import EditBlog from './vue/backend/blog/Edit';
import BlogCategories from './vue/backend/blogCategory/Index';
import Security from './vue/backend/Security';
import SinglePost from './vue/frontend/blog/SinglePost';
import PageNotFound from './vue/PageNotFound';

let routesAdmin = [
    { path: '/admin', component: Dashboard, name: 'Dashboard' },
    //
    { path: '/admin/users', component: Users, name: 'Users' },
    { path: '/admin/blog', component: Blog, name: 'Blog Posts' },
    { path: '/admin/blog/add', component: AddBlog, name: 'Add New Blog' },
    { path: '/admin/blog/edit', component: EditBlog, name: 'Edit Blog' },
    { path: '/admin/blog/categories', component: BlogCategories, name: 'Blog Categories' },
    { path: '/admin/security', component: Security, name: 'Security' },
    // Frontend
    { path: '/blog/:post', component: SinglePost, name: 'Single Post' },
    // PageNotFound always last
    { path: "*", component: PageNotFound, name: 'Page Not Found' }
];

export const routes = routesAdmin;
