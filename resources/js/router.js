import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import home from './view/home/index'
//admin project pages
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminuser from './admin/pages/adminuser'
import role from './admin/pages/role'
import login from './view/login/index'
import assignRole from './admin/pages/assignRole'
import blog from './admin/pages/blog'
const routes = [
    
    {
        path: '/',
        component: home,
        // name:dashboard
    },
    //user management
    {
        path: '/login',
        component: login,
        name:'login'
    },
    {
        path: '/adminuser',
        component: adminuser,
        name:'adminuser'
    },
    {
        path: '/role',
        component: role,
        name:'role'
    },
    {
        path: '/assignRole',
        component: assignRole,
        name:'assignRole'
    },

    //blog
    {
        path: '/tags',
        component: tags,
        name:'tags'
    },

    {
        path: '/category',
        component: category,
        name:'category'
    },
    {
        path: '/blog',
        component: blog,
        name:'blog'
    },

]

export default new Router({
    mode: 'history',
    routes
})