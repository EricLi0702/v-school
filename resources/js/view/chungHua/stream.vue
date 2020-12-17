<template>
    <div class="w-100 es-view mt-md-2 p-md-2">
        <div class="bg-navbar-area" v-if="$isMobile()">
        </div>
        <div class="es-item">
            <Button class="btnclass" @click="addStream" v-if="isWritePermitted"><Icon type="md-add" /> 发布 </Button>
        </div>
        
        <div v-if="streamData == null"> </div>
        <div v-for="(item,index) in streamData" :key="index" v-else class="es-item">
            <div class="es-item-left" v-if="item.addData.type == 'text'">
                {{item.addData.text}}
            </div>
            <div class="es-item-left" v-else-if="item.addData.type == 'image'">
                <div v-for="img in item.addData.imgUrl" :key="img.fileName" class="col-12 col-md-4 p-0 mb-1 m-0 row">
                    <div v-if="item.addData.imgUrl.length == 1" class="image-viewer one-image" v-viewer>
                        <img :src="img" alt="" @click="showSendImage">
                    </div>
                    <div v-else class="ct-3-img-container image-viewer col-12 m-0 pl-0" v-viewer>
                        <img :src="img" alt="" class="w-100" @click="showSendImage">
                    </div>
                </div>
            </div>
            <div class="es-item-left" v-else-if="item.addData.type == 'video'">
                <div v-for="video in item.addData.videoUrl" :key="video.fileName">
                    <div class="video-box video-cover">
                        <div class="vb-bg"></div>
                        <div class="vb-play"><Icon  type="ios-play-outline" class="play-icon" @click="playSmsVideo(video)"/></div>
                    </div>
                    <Modal
                        footer-hide	
                        v-model="playSmsVideoModal"
                        class-name="vertical-center-modal"
                        :styles="{top:'140px',left:'-244px'}"
                        :mask-closable="false"
                        >
                        <video-player  
                            class="video-player-box"
                            ref="videoPlayer"
                            :options="playerOptions"
                            :playsinline="true"
                            @play="onPlayerPlay($event)"
                            @pause="onPlayerPause($event)"
                            @ended="onPlayerEnded($event)"
                            @loadeddata="onPlayerLoadeddata($event)"
                            @waiting="onPlayerWaiting($event)"
                            @playing="onPlayerPlaying($event)"
                            @timeupdate="onPlayerTimeupdate($event)"
                            @canplay="onPlayerCanplay($event)"
                            @canplaythrough="onPlayerCanplaythrough($event)"
                            @ready="playerReadied"
                            @statechanged="playerStateChanged($event)"
                            >
                        </video-player>
                    </Modal> 
                </div>
            </div>
            <div class="es-item-right"  @click="editStream(item.addData)">
                {{item.addData.timeRange[0]}}~{{item.addData.timeRange[1]}}
            </div>
        </div>
        <Modal
            footer-hide
            :value="newStream"
            title="发布"
            :styles="{top:'75px',left:'-90px'}"
            :mask-closable="false"
            class-name="question-view-modal"
            @on-cancel="cancel"
        >
            <a @click="$router.go(-1)"><Icon class="question-view-modal-back-icon" type="ios-arrow-back" /></a>
            <div class="p-modal-scroll">
                <streamComponent :propsData="editData"></streamComponent>
            </div>
        </Modal>
    </div>
</template>

<script>
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'

import streamComponent from '../../components/chungHua/streamComponent'
export default {
    components:{
        streamComponent,
        videoPlayer,
        Viewer,
    },
    data(){
        return{
            streamData:null,
            count:1,
            newData:{
                type:'',
                text:'',
                imgUrl:[],
                videoUrl:[],
                tiemRange:[],
            },
            newStream:false,
            playSmsVideoModal:false,
            playerOptions: {
                width:'1010',
                height: '610',
                autoplay: false,
                muted: false,
                language: 'en',
                playbackRates: [0.7, 1.0, 1.5, 2.0],
                sources: [{
                    type: "video/mp4",
                    src: "https://cdn.theguardian.tv/webM/2015/07/20/150716YesMen_synd_768k_vp8.webm"
                }],
                poster: "https://surmon-china.github.io/vue-quill-editor/static/images/surmon-1.jpg",
            },
            editData:{
                type:'text',
                text:'',
                imgUrl:[],
                videoUrl:[],
                tiemRange:[],
            }
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    watch:{
        currentPath:{
            handler(val){
                console.log(val)
                if(val.query.addData){
                    this.newStream = false
                    // console.log(this.streamData)
                    // console.log(val.query.addData)
                    this.streamData.unshift(val.query.addData)
                }
            },
            deep:true
        }
    },
    async created(){
        if(JSON.stringify(this.currentPath.query) != '{}'){
            this.$router.push(this.$route.path)
        }
        const res = await this.callApi('get','/api/streamData')
        console.log(res)
        if(res.status == 200){
            for(let i=0;i<res.data.length;i++){
                res.data[i].addData = JSON.parse(res.data[i].addData);
            }
            this.streamData = res.data;
        }
        console.log(this.streamData)
    },
    methods:{
        addStream(){
            this.newStream = true
            this.editData = {
                type:'text',
                text:'',
                imgUrl:[],
                videoUrl:[],
                tiemRange:[],
            }
            this.$router.push({path:this.$route.path,query:{postView:'add'}})
        },
        editStream(item){
            this.editData = item
            console.log('editData',this.editData)
            this.newStream = true
            this.$router.push({path:this.$route.path,query:{postView:'update'}})
        },
        childData(val){
            console.log(val)
            this.newData = val
        },
        async submit(){
            const res = await this.callApi('post','stramData',this.newData)
        },
        showSendImage(){
            const viewer = this.$el.getElementsByClassName('.image-viewer').$viewer;
            viewer.show();
        },
        onPlayerPlay(player) {
            // console.log('player play!', player)
        },
        onPlayerPause(player) {
            // console.log('player pause!', player)
        },
        onPlayerEnded(player) {
            // console.log('player ended!', player)
        },
        onPlayerLoadeddata(player) {
            // console.log('player Loadeddata!', player)
        },
        onPlayerWaiting(player) {
            // console.log('player Waiting!', player)
        },
        onPlayerPlaying(player) {
            // console.log('player Playing!', player)
        },
        onPlayerTimeupdate(player) {
            // console.log('player Timeupdate!', player.currentTime())
        },
        onPlayerCanplay(player) {
            // console.log('player Canplay!', player)
        },
        onPlayerCanplaythrough(player) {
            // console.log('player Canplaythrough!', player)
        },
        // or listen state event
        playerStateChanged(playerCurrentState) {
            // console.log('player current update state', playerCurrentState)
        },
        // player is ready
        playerReadied(player) {
            // seek to 10s
            // console.log('example player 1 readied', player)
            player.currentTime(10)
            // console.log('example 01: the player is readied', player)
        },

        //playVideo
        playSmsVideo(video){
            this.playSmsVideoModal = true;
            this.playerOptions.sources[0].src = "http://127.0.0.1:8000" + video.imgUrl;
            // this.playerOptions.sources[0].src = "http://47.111.233.60" + video.imgUrl;
            // this.playerOptions.sources[0].src = "http://vjs.zencdn.net/v/oceans.mp4";
            this.playerOptions.poster = "/img/icon/default_video.png";
        },
        cancel(){
            this.$router.push({path:this.$route.path})
            this.newStream = false
        }
    }
}
</script>

<style>

</style>