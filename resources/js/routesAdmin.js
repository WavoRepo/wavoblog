
import Dashboard from './components/backend/Dashboard';
import Users from './components/backend/users/Index';
import Blog from './components/backend/blog/Index';
import AddBlog from './components/backend/blog/Add';
import EditBlog from './components/backend/blog/Edit';
import Security from './components/backend/Security';
import PageNotFound from './components/PageNotFound';

let routesAdmin = [
    { path: '/admin', component: Dashboard, name: 'Dashboard' },
    //
    { path: '/admin/users', component: Users, name: 'Users' },
    { path: '/admin/blog', component: Blog, name: 'Blog Posts' },
    { path: '/admin/blog/add', component: AddBlog, name: 'Add New Blog' },
    { path: '/admin/blog/edit', component: EditBlog, name: 'Edit Blog' },
    { path: '/admin/security', component: Security, name: 'Security' },
    // PageNotFound always last
    { path: "*", component: PageNotFound, name: 'Page Not Found' }
];

export const routes = routesAdmin;
