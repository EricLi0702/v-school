import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import home from './components/pages/home'
//admin project pages
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminuser from './admin/pages/adminuser'

import login from './admin/pages/login'
const routes = [
    
    {
        path: '/',
        component: home
    },

    {
        path: '/login',
        component: login
    },

    //adminuser
    {
        path: '/tag',
        component: tags
    },

    {
        path: '/category',
        component: category
    },
    {
        path: '/adminuser',
        component: adminuser
    },

]

export default new Router({
    mode: 'history',
    routes
})