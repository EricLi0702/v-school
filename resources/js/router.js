import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import home from './components/pages/home'
//admin project pages
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminuser from './admin/pages/adminuser'
import role from './admin/pages/role'
import login from './admin/pages/login'
import assignRole from './admin/pages/assignRole'
const routes = [
    
    {
        path: '/',
        component: home,
        // name:dashboard
    },

    {
        path: '/login',
        component: login,
        name:'login'
    },

    //adminuser
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

]

export default new Router({
    mode: 'history',
    routes
})