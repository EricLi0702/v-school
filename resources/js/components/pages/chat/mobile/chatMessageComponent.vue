<template>
    <div>
        <div v-if="message.text !== null && message.text !== undefined">
            <div v-if="checkIfCreateRoomInfo(message)" class="text-center">
                <p class="pt-5"><strong style="font-size: 25px">{{message.text.createdUser}}</strong>在{{TimeViewHMS(message.created_at)}}创建了一个称为{{message.text.roomName}}的房间</p>
                <p class="pb-4">
                    受邀的人:&nbsp;&nbsp;
                    <span 
                        v-for="(userName, i) in message.text.invitedUser" 
                        :key="'A'+ i"
                    >
                        <strong>{{userName}}</strong> &nbsp;&nbsp;
                    </span>
                </p>
            </div>

            <div v-else-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4 position-relative">
                <div class="msg-container-send">
                    <p v-html="detectUrl(message.text)" class="messagebox_p"></p>
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <avatar :size="30" :username="message.from.name"></avatar>
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <avatar :size="40" :username="message.from.name"></avatar>
                </div>
                <div class="ch-name-and-time d-flex">
                    <p class="ch-name-view">{{message.from.name}}</p>
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="msg-container">
                    <p v-html="detectUrl(message.text)" class="messagebox_p"></p>
                </div>
            </div>

        </div>
        <div v-else-if="message.image !== null && message.image !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4 position-relative">
                <div class="msg-container-send p-1">
                    <div class="msg-image-container-send" v-viewer>
                        <img :src="message.image" alt="" @click="showSendImage">
                    </div>
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <avatar :size="30" :username="message.from.name"></avatar>
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <avatar :size="40" :username="message.from.name"></avatar>
                </div>
                <div class="ch-name-and-time d-flex">
                    <p class="ch-name-view">{{message.from.name}}</p>
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="msg-container p-1">
                    <div class="msg-image-container" v-viewer>
                        <img :src="message.image" alt="" @click="showReceiveImage">
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="message.video !== null && message.video !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4 position-relative">
                <div class="msg-container-send p-1">
                    <div class="msg-video-container-send position-relative">
                        <img src="/img/coverImage/chatVideoCoverImage.jpg" alt="">
                        <Icon @click="sendVideoInfoToParent(message)" class="msg-video-play-icon" size="43" color="#2D8CF0"  type="logo-youtube" />
                    </div>
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <avatar :size="30" :username="message.from.name"></avatar>
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <avatar :size="40" :username="message.from.name"></avatar>
                </div>
                <div class="ch-name-and-time d-flex">
                    <p class="ch-name-view">{{message.from.name}}</p>
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="msg-container p-1">
                    <div class="msg-video-container position-relative">
                        <img src="/img/coverImage/chatVideoCoverImage.jpg" alt="">
                        <Icon @click="sendVideoInfoToParent(message)" class="msg-video-play-icon" size="65" color="#2D8CF0" type="logo-youtube" />
                    </div>
                </div>
            </div>
            
        </div>
        <div v-else-if="message.voice !== null && message.voice !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4 position-relative">
                <div class="msg-container-send msg-voice-send">
                    <audio  :src="message.voice" controls />
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <avatar :size="30" :username="message.from.name"></avatar>
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <avatar :size="40" :username="message.from.name"></avatar>
                </div>
                <div class="ch-name-and-time d-flex">
                    <p class="ch-name-view">{{message.from.name}}</p>
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="msg-container msg-voice">
                    <audio  :src="message.voice" controls />
                </div>
            </div>
        </div>
        <div v-else-if="message.file !== null && message.file !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4 position-relative">
                <div class="msg-container-send">
                    <div class="msg-file-container d-flex">
                        <Icon size="22" type="ios-document-outline" />
                        <p><strong>{{message.file.name}}</strong></p>
                    </div>
                    <p>{{message.file.size}} bytes</p>
                    <a class="msg-file-download-ico-send-a" :href="message.file.path" :download="message.file.name">
                        <Icon size="25" class="msg-file-download-ico msg-file-download-ico-send" type="md-download" />
                    </a>
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <avatar :size="30" :username="message.from.name"></avatar>
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <avatar :size="40" :username="message.from.name"></avatar>
                </div>
                <div class="ch-name-and-time d-flex">
                    <p class="ch-name-view">{{message.from.name}}</p>
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="msg-container">
                    <div class="msg-file-container d-flex">
                        <Icon size="22" color="white" type="ios-document-outline" />
                        <p><strong>{{message.file.name}}</strong></p>
                    </div>
                    <p>{{message.file.size}} bytes</p>
                    <a class="msg-file-download-ico-a" :href="message.file.path" :download="message.file.name">
                        <Icon size="25" class="msg-file-download-ico" type="md-download" />
                    </a>
                </div>
                
            </div>
        </div>
        <div v-else-if="message.map !== null && message.map !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4 position-relative">
                <div class="msg-container-send">
                    <Icon @click="sendLocationInfoToParent(message.map)" type="md-eye" size="25" class="p-8px msg-map-view-icon msg-map-view-icon-send" />
                    <div class="msg-map-container d-flex">
                        <Icon size="22" type="md-pin" />
                        <p><strong>{{message.map.address}}</strong></p>
                    </div>
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <avatar :size="30" :username="message.from.name"></avatar>
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <avatar :size="40" :username="message.from.name"></avatar>
                </div>
                <div class="ch-name-and-time d-flex">
                    <p class="ch-name-view">{{message.from.name}}</p>
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="msg-container">
                    <div class="msg-map-container d-flex">
                        <Icon size="22" color="white" type="md-pin" />
                        <p><strong>{{message.map.address}}</strong></p>
                    </div>
                    <Icon @click="sendLocationInfoToParent(message.map)" type="md-eye" size="25" class="p-8px msg-map-view-icon" />
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>

import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'
import Avatar from 'vue-avatar'

export default {
components:{
       
    },
    components:{
        Viewer,
        Avatar,
    },

    data(){
        return{
        }
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
            required: false,
        },
        chatfrom: {
            type: Number,
            required: true,
        },
        chatin:{
            type:Number,
            required:false
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
            if(typeof text == "object"){
                return;
            }
            let urlRegex = /(https?:\/\/[^\s]+)/g;
            return text.replace(urlRegex, function(url) {
                return `<a href="${url}" target="_blank">${url}</a>`;
            });
        },
        sendLocationInfoToParent(mapInfo){
            this.$emit('mapInfoToParent', mapInfo)
        },
        sendVideoInfoToParent(videoInfo){
            this.$emit('videoInfoToParent', videoInfo)
        },

        checkIfCreateRoomInfo(message){
            if((typeof message.text == "string" && (message.text[0] == "{" && message.text[message.text.length - 1] == "}"))){
                message.text = JSON.parse(message.text);
                return true;
            }
            else if(typeof message.text == "string"){
                return false;
            }
            else if('createdUser' in message.text){
                return true;
            }
            else{
                return false;
            }
        },

        isJson(str) {
            try {
                JSON.parse(str);
            } catch (e) {
                return false;
            }
            return true;
        }
    }
}
</script>


<style scoped>
.recording-result audio{
    width: 237px!important;
    height: 30px!important;
}
.msg-video-container-send img{
    max-width: 200px;
}
.msg-image-container-send img{
    max-width: 200px;
}
.msg-video-container img{
    max-width: 200px;
}
.msg-image-container img{
    max-width: 200px;
}
.msg-container-send{
    max-width: 70%;
}
.msg-container-send{
    max-width: 70%;
}
.msg-container{
    max-width: 70%;
}
.msg-voice-send audio{
    width: 210px!important;
}
.msg-container-send audio{
    width: 210px!important;
}
.msg-container audio{
    width: 210px!important;
}

@media only screen and (max-width: 360px) {
    .recording-result audio{
        width: 200px!important;
        height: 30px!important;
    }
}

</style>