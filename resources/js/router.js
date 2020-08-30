import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import home from './components/pages/home'
import firstPage from './components/pages/myFirstPage'
import newRoutePage from './components/pages/newRoutePage'

//vue hooks
import hooks from './components/pages/basic/hooks'
import methods from './components/pages/basic/methods'
const routes = [
    
    {
        path: '/',
        component: home
    },

    
    {
        path: '/my-new-vue-route',
        component: firstPage
    },

    {
        path: '/new-route',
        component: newRoutePage
    },

    //vue hooks
    {
        path: '/hooks',
        component: hooks
    },

    {
        path: '/methods',
        component: methods
    },
]

export default new Router({
    mode: 'history',
    routes
})