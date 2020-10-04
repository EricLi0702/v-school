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

// //custom Modal
// import modal from './components/modal.vue'
// Vue.use(modal)

import VueFab from 'vue-float-action-button'
Vue.use(VueFab, /* {
    ----------------------
    // opitons 可选iconfont图标或MaterialIcons
    iconType: 'MaterialDesign'
    // iconType: 'iconfont'
  } */)
  
//vue-recorder
import VueRecord from '@codekraft-studio/vue-record'
Vue.use(VueRecord)

//vue image viewer
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
Vue.use(Viewer)

//vue-Gotop
import GoTop from '@inotom/vue-go-top';
Vue.use(GoTop);

//vue2 perfect scroll bar
import PerfectScrollbar from 'vue2-perfect-scrollbar'
import 'vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css'
 
Vue.use(PerfectScrollbar)

//Vue-Countdown-Timer Component
import VueCountdownTimer from 'vuejs-countdown-timer'
Vue.use(VueCountdownTimer)

//vue-chat-scroll
import VueChatScroll from 'vue-chat-scroll'
Vue.use(VueChatScroll)


//vue infinite Scroll bar//////// for this, installed vue-resource & vue-infinite-loading
Vue.use(require('vue-resource'));
Vue.component('InfiniteLoading', require('vue-infinite-loading'));

//vue video player

import VueVideoPlayer from 'vue-video-player'

// require videojs style
import 'video.js/dist/video-js.css'
// import 'vue-video-player/src/custom-theme.css'

Vue.use(VueVideoPlayer, /* {
  options: global default options,
  events: global videojs events
} */);


import Vue from 'vue'
import BaiduMap from 'vue-baidu-map'
 
Vue.use(BaiduMap, {
  /* Visit http://lbsyun.baidu.com/apiconsole/key for details about app key. */
  ak: 'g7jEzA3TobDaY6a5tSyDbOEGYW3ErTgB'
})

import { EmojiPickerPlugin } from 'vue-emoji-picker'
Vue.use(EmojiPickerPlugin)

import Fragment from 'vue-fragment'
Vue.use(Fragment.Plugin)

// import { Plugin } from 'vue-fragment'
// Vue.use(Plugin)
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
