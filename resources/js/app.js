/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import router from './router'
import store from './store'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css';
Vue.use(ViewUI);
import common from './common'
Vue.mixin(common)

import Editor from 'vue-editor-js'
Vue.use(Editor)

import PerfectScrollbar from 'vue2-perfect-scrollbar'
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css'
Vue.use(PerfectScrollbar)
import VueFab from 'vue-float-action-button'
Vue.use(VueFab, /* {
    ----------------------
    // opitons 可选iconfont图标或MaterialIcons
    iconType: 'MaterialDesign'
    // iconType: 'iconfont'
  } */)

import GoTop from '@inotom/vue-go-top';
Vue.use(GoTop);

import Vue from 'vue'
import BaiduMap from 'vue-baidu-map'
 
Vue.use(BaiduMap, {
  /* Visit http://lbsyun.baidu.com/apiconsole/key for details about app key. */
  ak: 'g7jEzA3TobDaY6a5tSyDbOEGYW3ErTgB'
})
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('mainapp-component', require('./components/MainappComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    store
});
