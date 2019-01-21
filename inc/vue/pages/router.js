import Vue from 'vue';
import Router from 'vue-router';

// import Redirects from '../pages/Redirects'

import DemoContent from '../pages/CreatePosts/CreatePosts';
import ParsePosts from '../pages/ParsePosts/ParsePosts';

Vue.use(Router);

export default new Router({
    // routes: [
    //     {
    //         path: '/redirect',
    //         name: 'redirect',
    //         component: Redirects
    //     },
    // ]
    mode: 'hash',
    base: '',
    routes: [
        // { path: 'wp-admin/admin.php',
        //     // You could also have named views at tho top
        //     component: App,
        //     children: [{
        //         path: '?page=admin_pages',
        //         component: DemoContent
        //     }, {
        //         path: '?page=parse_posts',
        //         component: ParsePosts
        //     }]
        // }
        {
            path: '/demo-creator',
            name: 'DemoContent',
            component: DemoContent
        },
        {
            path: '/parse_posts',
            name: 'ParsePosts',
            component: ParsePosts,
        }
    ],
    // Prevents window from scrolling back to top
    // when navigating between components/views
    scrollBehavior (to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return { x: 0, y: 0 }
        }
    }
})