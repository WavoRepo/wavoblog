
import Blog from './components/frontend/blog/Index';
import SinglePost from './components/frontend/blog/SinglePost';

import PageNotFound from './components/PageNotFound';


let routesBlog = [
    { path: '/blog', component: Blog, name: 'Blog Posts' },
    { path: '/blog/:post', component: SinglePost, name: 'Single Post' },
    // PageNotFound always last
    { path: "*", component: PageNotFound, name: 'Page Not Found' }
]

export const routes = routesBlog;
