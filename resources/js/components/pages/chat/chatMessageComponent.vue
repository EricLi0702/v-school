<template>
    <div>
        <div v-if="message.text !== null && message.text !== undefined">
            <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4 position-relative">
                <div class="msg-container-send">
                    <p v-html="detectUrl(message.text)" class="messagebox_p"></p>
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
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
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
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
                        <Icon @click="sendVideoInfoToParent(message)" class="msg-video-play-icon" size="65" color="#2D8CF0"  type="logo-youtube" />
                    </div>
                </div>
                <div class="ch-time">
                    <p v-html="TimeViewHMS(message.created_at)"></p>
                </div>
                <div class="ch-user-avatar-sender">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
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
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
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
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
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
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                </div>
            </div>

            <div v-else class="d-flex justify-content-start mb-4 position-relative">
                <div class="ch-user-avatar">
                    <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
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

export default {

    components:{
        Viewer,
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
            required: true,
        },
        chatfrom: {
            type: Number,
            required: true,
        },
    },

    methods:{
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
        sendLocationInfoToParent(mapInfo){
            this.$emit('mapInfoToParent', mapInfo)
        },
        sendVideoInfoToParent(videoInfo){
            this.$emit('videoInfoToParent', videoInfo)
        }
    }
}
</script>