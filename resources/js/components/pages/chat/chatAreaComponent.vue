<template>
    <div class="h-75">
        <div class="ch-message-body-container h-100 p-4 bg-white" v-chat-scroll>
            <ChatMessage
                v-for="message in messages"
                :key="message.id"
                :message="message"
                :chatto="chatto"
                :chatfrom="chatfrom"
                @mapInfoToParent="passMapDataFromChild"
                @videoInfoToParent="passVideoDataFromChild"
            />
        </div>
        <div class="modal-container">
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
    </div>
</template>

<script>
import ChatMessage from './chatMessageComponent';

import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'

export default {
    components:{
        ChatMessage,
        videoPlayer,
    },
    props:{
        chatto:{
            type:Number,
            required:true
        },
        chatfrom:{
            type:Number,
            required:true
        },
        messages:{
            type:Array,
            required:true
        }
    },

    computed:{
        player() {
            return this.$refs.videoPlayer.player
        },
    },

    data(){
        return{
            //map
            viewLocationMapMessageModal: false,
            mapInfoFromChild:{},

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

            //video message
            playMsgSentVideoModal: false,

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
        }
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

        closeSendMapModal(){
            this.viewLocationMapMessageModal = false;
            this.center.lng = null;
            this.center.lat = null;
            this.zoom = null;
        },

        passMapDataFromChild(value){
            this.center.lng = value.lng;
            this.center.lat = value.lat;
            this.zoom = value.zoom;
            this.viewLocationMapMessageModal = true;
        },

        passVideoDataFromChild(value){
            console.log("VideoInfoFromChild", value);
            this.playMsgSentVideoModal = true;
            this.playerOptions.sources[0].src = "http://127.0.0.1:8000" + value.video;
            this.playerOptions.poster = "/img/coverImage/chatVideoCoverImage.jpg";
        },
        
    }

}
</script>