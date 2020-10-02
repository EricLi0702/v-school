<template>
    <div>
        <div v-if="message.text !== null && message.text !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4">
                <div class="msg-container-send">
                    <p v-html="detectUrl(message.text)" class="messagebox_p"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
                <div class="msg-container">
                    <p v-html="detectUrl(message.text)" class="messagebox_p"></p>
                </div>
            </div>
        </div>
        <div v-else-if="message.image !== null && message.image !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4">
                <div class="msg-container-send p-1">
                    <div class="msg-image-container-send" v-viewer>
                        <img :src="message.image" alt="" @click="showSendImage">
                    </div>
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
                <div class="msg-container p-1">
                    <div class="msg-image-container" v-viewer>
                        <img :src="message.image" alt="" @click="showReceiveImage">
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="message.video !== null && message.video !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4">
                <div class="msg-container-send p-1">
                    <div class="msg-video-container-send position-relative">
                        <img src="/img/coverImage/chatVideoCoverImage.jpg" alt="">
                        <Icon @click="playMsgVideo(message)" class="msg-video-play-icon" size="65" color="#2D8CF0"  type="logo-youtube" />
                    </div>
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
                <div class="msg-container p-1">
                    <div class="msg-video-container position-relative">
                        <img src="/img/coverImage/chatVideoCoverImage.jpg" alt="">
                        <Icon @click="playMsgVideo(message)" class="msg-video-play-icon" size="65" color="#2D8CF0" type="logo-youtube" />
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="message.voice !== null && message.voice !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4">
                <div class="msg-container-send msg-voice-send">
                    <audio  :src="message.voice" controls />
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
                <div class="msg-container msg-voice">
                    <audio  :src="message.voice" controls />
                </div>
            </div>
        </div>
        <div v-else-if="message.file !== null && message.file !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4">
                <div class="msg-container-send">
                    <div class="msg-file-container d-flex">
                        <Icon size="22" color="white" type="ios-document-outline" />
                        <p><strong>{{message.file.name}}</strong></p>
                    </div>
                    <p>{{message.file.size}} bytes</p>
                    <a :href="message.file.path" :download="message.file.name">
                        <Icon size="35" class="msg-file-download-ico msg-file-download-ico-send" type="md-download" />
                    </a>
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
                <div class="msg-container">
                    <div class="msg-file-container d-flex">
                        <Icon size="22" type="ios-document-outline" />
                        <p><strong>{{message.file.name}}</strong></p>
                    </div>
                    <p>{{message.file.size}} bytes</p>
                    <a :href="message.file.path" :download="message.file.name">
                        <Icon size="35" class="msg-file-download-ico" type="md-download" />
                    </a>
                </div>
            </div>
        </div>
        <div v-else-if="message.map !== null && message.map !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4">
                <div class="msg-container-send">
                    <div class="msg-map-container d-flex">
                        <Icon size="22" color="white" type="md-pin" />
                        <p><strong>{{message.map.address}}</strong></p>
                    </div>
                    <p>View</p>
                    <Icon @click="viewLocation(message.map)" type="md-eye" size="35" class="msg-map-view-icon-send msg-map-view-icon" />
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
                <div class="msg-container">
                    <div class="msg-map-container d-flex">
                        <Icon size="22" type="md-pin" />
                        <p><strong>{{message.map.address}}</strong></p>
                    </div>
                    <p>View</p>
                    <Icon @click="viewLocation(message.map)" type="md-eye" size="35" class="msg-map-view-icon" />
                </div>
            </div>
        </div>
        
        <Modal
            footer-hide	
            v-model="playMsgSentVideoModal"
            class-name="vertical-center-modal"
            :styles="{top:'140px',left:'-244px'}"
            :mask-closable="false"
            :z-index="1250"
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
        
        <Modal
            footer-hide	
            title="Location"
            v-model="viewLocationMapMessageModal"
            class-name="vertical-center-modal msg-map-view-modal"
            :styles="{top:'140px',left:'-244px'}"
            :mask-closable="false"
            :z-index="1250"
            @on-cancel="closeSendMapModal"
            >
            
            <baidu-map 
                class="map custom-map-view-size"
                :scroll-wheel-zoom="true"
                :center="center"
                :zoom="zoom"
                :mapStyle="mapStyle"
                
                >
                    <bm-marker :position="{ lng: center.lng , lat: center.lat}" :dragging="false" animation="BMAP_ANIMATION_BOUNCE">
                    </bm-marker>
            </baidu-map>
        </Modal>
    </div>
</template>

<script>

import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'

import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'

export default {

    components:{
        videoPlayer,
        Viewer,
    },

    data(){
        return{
            playMsgSentVideoModal:false,
            viewLocationMapMessageModal:false,

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

            //map
            center:{
                lng: 116.404,
                lat: 39.915
            },
            zoom:15,
            mapStyle: {
                styleJson: [
                    {
                        "featureType": "all",
                        "elementType": "geometry",
                        "stylers": {
                            "hue": "#007fff",
                            "saturation": 89
                        }
                    },
                    {
                        "featureType": "water",
                        "elementType": "all",
                        "stylers": {
                            "color": "#ffffff"
                        }
                    }
                ]
            },
        }
    },
    computed:{
        player() {
            return this.$refs.videoPlayer.player
        },
    },
    // watch:{
    //     center:{
    //         handler(val){
    //             if(val.lng !== null && val.lat !== null){
    //                 this.viewLocationMapMessageModal = true;
    //             }
    //         }
    //     }
    // },
    props: {
        message: {
            type: Object,
            required: true,
        },
        chatto: {
            type: Number,
            required: true,
        },
        chatfrom: {
            type: Number,
            required: true,
        },
    },

    methods:{

        //video play method
        // listen event
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

        playMsgVideo(message){
            this.playMsgSentVideoModal = true;
            this.playerOptions.sources[0].src = "http://47.111.233.60" + message.video;
            this.playerOptions.poster = "/img/coverImage/chatVideoCoverImage.jpg";
        },

        showSendImage(){
            const viewer = this.$el.querySelector('.msg-image-container-send').$viewer
            viewer.show()
        },

        showReceiveImage(){
            const viewer = this.$el.querySelector('.msg-image-container').$viewer
            viewer.show()
        },

        detectUrl(text){
            let urlRegex = /(https?:\/\/[^\s]+)/g;
            return text.replace(urlRegex, function(url) {
                return `<a href="${url}" target="_blank">${url}</a>`;
            });
        },

        viewLocation(mapInfo){
            let sammie = this.center;
            this.center.lng = mapInfo.lng;
            this.center.lat = mapInfo.lat;
            this.zoom = mapInfo.zoom;
            // this.viewLocationMapMessageModal = true;
        },

        closeSendMapModal(){
            this.viewLocationMapMessageModal = false;
            this.center.lng = null;
            this.center.lat = null;
            this.zoom = null;
        },
        // syncCenterAndZoom(e){
        //     const {lng, lat} = e.target.getCenter();
        //     this.center.lng = lng;
        //     this.center.lat = lat;
        //     this.zoom = e.target.getZoom();
        // },
    }
}
</script>
