<template>
    <div class="hv-100">
        <div class="h-100 chat-message-area">
            <div class="chat-to-user-name p-2 text-center bg-light-gray">
                {{chatToInfo}}
                <span v-if="userTypingNow"><img src="/img/icon/typingColor.gif" alt=""  style="width:20px;"></span> 
                <span v-if=" 3 > groupTypingList.length > 0" v-for="(username, i) in groupTypingList" :key="i">{{username}} <img src="/img/icon/typingColor.gif" alt=""  style="width:20px;"> </span> 
                <span v-if=" 3 < groupTypingList.length" >超过2人在打字</span> 
            </div>
            <div class="ch-message-body-container h-100 p-2 bg-white" v-chat-scroll>
                <ChatMessage
                    v-for="(message, index) in messages"
                    :key="index"
                    :message="message"
                    :chatto="chatto"
                    :chatfrom="chatfrom"
                    :chatin="chatin"
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
                        class="video-player-box vjs-custom-skin w-100 h-100"
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
                    title="位置"
                    v-model="viewLocationMapMessageModal"
                    class-name="vertical-center-modal msg-map-view-modal"
                    :styles="{top:'140px',left:'-244px'}"
                    :mask-closable="false"
                    :z-index="1250"
                    @on-cancel="closeviewLocationMapModal"
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
        <div class="px-3 pt-2 pb-23px bg-light-gray" >
            <div class="emoji-area">
                <Picker v-if="emoStatus" set="emojione" @select="onInput" title="选择你的表情符号..." />
            </div>
            <div class="ch-footer-container position-relative">
                <div class="row m-0 p-0 align-items-center">
                    <textarea 
                        class="custom-textarea mobile-custom-textarea col-10"
                        :disabled="recording.src !== null" 
                        v-model="text" 
                        rows="1"
                        @keydown.enter.exact.prevent 
                        @keyup.enter.exact="newline" 
                        @keydown.enter.shift.exact="submit" 
                        @keydown.enter.shift.exact.prevent
                        @keydown="sendTypingEvent"
                        placeholder="输入内容" 
                        style="padding: 7px 16px 7px; border-radius:30px;">
                    </textarea>
                    <div class="col-2 p-0 pl-3">
                        <Button icon="ios-send" type="primary" class="send-icon-btn mr-0" shape="circle" @click="submit"></Button>
                    </div>
                </div>
                <div class="ch-footer-below row p-0 m-0 mt-2">
                    <div v-if="recording.src == null" class="ch-footer-upload-icon-area pl-3 mr-0 pr-0 col-10 d-flex justify-content-end">
                        <Icon @click="showSendImageModal" class="pr-2 msg-upload-icons" size="25" type="ios-image" />
                        <Icon @click="showSendFileModal" class="pr-2 msg-upload-icons" size="25" type="ios-folder" />
                        <Icon @click="showSendVideoModal" class="pr-2 msg-upload-icons" size="25" type="ios-film" />
                        <Icon @click="showSendMapModal" class="msg-upload-icons" size="25" type="ios-locate" />
                        <!-- <Icon @click="toggleEmo" class="pr-2 msg-upload-icons" size="25" type="md-happy" /> -->
                    </div>
                    <div v-if="recording.src !== null" class="recording-result position-relative d-flex align-items-center">
                        <audio  :src="recording.src" controls/>
                        <Icon class="remove-record-icon" @click="removeRecordedAudio" type="ios-close-circle" size="20" />
                    </div>
                    <!-- <div class="ch-footer-send-area ml-auto d-flex align-items-center">
                        <VueRecordAudio class="mobile-chat-recorder"  mode="press" @stream="onStream" @result="onResult" />
                        
                    </div> -->
                <!------------------------------------------------------------->
                <!----------------------- Image Send Modal -------------------->
                <!------------------------------------------------------------->
                <Modal
                v-model="chatSendImageModal"
                :styles="{top:'75px',left:'-90px'}"
                :mask-closable="false"
                :z-index="1200"
                @on-cancel="closeSendImageModal"
                class-name="chat-send-modal"
                > 
                    <h1 class="p-3">选择图片</h1>
                    <Upload
                        ref="uploads"
                        type="drag"
                        accept="image/png, image/jpeg, image/jpg,image/bmp"
                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                        :format="['jpg','jpeg','png']"
                        :max-size="200000"
                        :on-exceeded-size="handleImageMaxSize"
                        :before-upload="handleImageUpload"
                        :on-format-error="handleImageFormatError"
                        action="/api/messages/image">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>单击或拖动图像以发送（jpg，png）</p>
                        </div>
                    </Upload>
                    <div v-if="sendImagefile !== null">选定的图像文件: {{ sendImagefile.name }} 
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="closeSendImageModal">关</Button>
                        <Button :disabled="sendImagefile == null" type="primary" @click="sendImageViaChat">发送图片</Button>
                    </div>
                </Modal>

                <!------------------------------------------------------------->
                <!----------------------- Video Send Modal -------------------->
                <!------------------------------------------------------------->
                <Modal
                v-model="chatSendVideoModal"
                :styles="{top:'75px',left:'-90px'}"
                :mask-closable="false"
                :z-index="1200"
                @on-cancel="closeSendVideoModal"
                class-name="chat-send-modal"
                > 
                    <h1 class="p-3">选择视频</h1>
                    <Upload
                        ref="uploads"
                        type="drag"
                        accept="video/mp4"
                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                        :format="['mp4']"
                        :max-size="5204288"
                        :on-exceeded-size="handleVideoMaxSize"
                        :before-upload="handleVideoUpload"
                        :on-format-error="handleVideoFormatError"
                        action="/api/messages/video">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>单击或拖动视频以发送（mp4）</p>
                        </div>
                    </Upload>
                    <div v-if="sendVideofile !== null">选定的视频文件: {{ sendVideofile.name }} 
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="closeSendVideoModal">关</Button>
                        <Button :disabled="sendVideofile == null" type="primary" @click="sendVideoViaChat">传送影片</Button>
                    </div>
                </Modal>

                <!------------------------------------------------------------->
                <!----------------------- File Send  Modal -------------------->
                <!------------------------------------------------------------->
                <Modal
                v-model="chatSendFileModal"
                :styles="{top:'75px',left:'-90px'}"
                :mask-closable="false"
                :z-index="1200"
                @on-cancel="closeSendFileModal"
                class-name="chat-send-modal"
                > 
                    <h1 class="p-3">选择文件</h1>
                    <Upload
                        ref="uploads"
                        type="drag"
                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                        :max-size="524288"
                        :on-exceeded-size="handleFileMaxSize"
                        :before-upload="handleFileUpload"
                        action="/api/messages/file">
                        <div style="padding: 20px 0">
                            <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                            <p>单击或拖动文件以发送</p>
                        </div>
                    </Upload>
                    <div v-if="sendFilefile !== null">选定文件: {{ sendFilefile.name }} 
                    </div>

                    <div slot="footer">
                        <Button type="default" @click="closeSendFileModal">关</Button>
                        <Button :disabled="sendFilefile == null" type="primary" @click="sendFileViaChat">发送文件</Button>
                    </div>
                </Modal>

                <!--------------------------------------------------------------->
                <!----------------------- Location Send Modal-------------------->
                <!--------------------------------------------------------------->
                <Modal
                v-model="chatSendMapModal"
                :styles="{top:'75px',left:'-90px'}"
                :mask-closable="false"
                :z-index="1200"
                @on-cancel="closeSendMapModal"
                class-name="chat-send-modal"
                > 
                    <h1 class="p-3">选择位置</h1>
                    <baidu-map 
                    class="map custom-map-size"
                    
                    :scroll-wheel-zoom="true"
                    :center="center"
                    :zoom="zoom"
                    :mapStyle="mapStyle"
                    @moving="syncCenterAndZoom"
                    @moveend="syncCenterAndZoom"
                    @zoomend="syncCenterAndZoom">
                        <bm-marker :position="{lng:sendMapInfo.lng, lat: sendMapInfo.lat}" :dragging="false" animation="BMAP_ANIMATION_BOUNCE">
                        </bm-marker>
                    </baidu-map>
                    <div slot="footer">
                        <Button type="default" @click="closeSendMapModal">关</Button>
                        <Button 
                        :disabled=" sendMapInfo.lng == null ||
                                    sendMapInfo.lat == null ||
                                    sendMapInfo.zoom == null" 
                        type="primary" 
                        @click="sendMapViaChat"
                        >发送位置</Button>
                    </div>
                </Modal>
            </div>
        </div>                  
        </div>
    </div>
</template>

<script>
import ChatMessage from './chatMessageComponent';
import 'video.js/dist/video-js.css'
import { videoPlayer } from 'vue-video-player'
import { Picker } from 'emoji-mart-vue'
export default {
    components:{
        ChatMessage,
        videoPlayer,
        Picker,
    },
    props:{
        chatto:{
            type:Number,
            required:false
        },
        chatin:{
            type:Number,
            required:false
        },
        chatfrom:{
            type:Number,
            required:true
        },
        messages:{
            type:Array,
            required:true
        },
        chatToInfo:{
            type:String,
            required:true
        }
    },
    computed:{
        player() {
            return this.$refs.videoPlayer.player
        },
    },
    created(){
        this.currentUser = this.$store.state.user;
        this.token = window.Laravel.csrfToken;
    },
    mounted(){
        this.listen();
    },
    watch:{
        sendMapInfo: {
            handler(val){
            if(val.address != ''){
                axios
                .post(`/api/messages/map`, {
                lng : this.sendMapInfo.lng,
                lat : this.sendMapInfo.lat,
                zoom : this.sendMapInfo.zoom,
                address : this.sendMapInfo.address,
                from : this.currentUser.id,
                to : this.chatto,
                roomId : this.chatin,
                })
                .then((res) => {
                if(res.errors){
                    this.$Notice.warning({
                    title: 'Something went wrong',
                    desc: res.errors
                    });
                }
                res.data.message.map = JSON.parse(res.data.message.map);
                this.messages.push(res.data.message);
                });
                this.closeSendMapModal();
            }
            },
            deep: true
        }
    },
    data(){
        return{
            currentUser:'',
            token:'',
            //recording
            recording: {
                src:null,
            },
            recordingBlobData:null,      
            //messageText
            text:'',
            //typing now
            userTypingNow : false,
            typingTimer: false,
            groupTypingList: [],
            //emoji
            emoStatus:false,
            //map
            viewLocationMapMessageModal: false,
            mapInfoFromChild:{},

            //sendImageModal
            chatSendImageModal: false,
            sendImagefile: null,
            //sendImageModal
            chatSendVideoModal: false,
            sendVideofile: null,
            //sendFileModal
            chatSendFileModal: false,
            sendFilefile: null,
            //sendMapModal
            chatSendMapModal: false,
            sendMapInfo: {
                lng:null,
                lat:null,
                zoom:null,
                address:""
            },

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
        listen(){
            Echo.join('chats')
                .listenForWhisper('typing', (e) => {
                    if(this.chatto !== null){
                        if(this.chatto == e.id && this.currentUser.id == e.ChatWith){
                            this.userTypingNow = true;
            
                            if(this.typingTimer){
                                clearTimeout(this.typingTimer);
                            }
            
                            this.typingTimer = setTimeout(()=>{
                            this.userTypingNow = false;
                            }, 3000)
                        }
                    }
                    else if (this.chatin !== null){
                        if(this.chatin == e.ChatIn){
                            if(!this.groupTypingList.includes(e.name)){
                                this.groupTypingList.push(e.name);
                            }

                            if(this.typingTimer){
                                clearTimeout(this.typingTimer);
                            }
            
                            this.typingTimer = setTimeout(()=>{
                                this.groupTypingList = [];
                            }, 2000)
                        }
                    }
                });
        },
        //recording
        onResult (data) {
            data.name = "oh.wav";
            data.lastModifiedDate = new Date();
            console.log('The blob data:', data);
            console.log('Downloadable audio', window.URL.createObjectURL(data));
            this.recordingBlobData = data;
            console.log("converted Blob", this.recordingBlobData);
            this.recording.src = window.URL.createObjectURL(data);
        },

        onStream (stream) {
            console.log('Got a stream object:', stream);
        },

        removeRecordedAudio(){
            this.recording.src = null;
            this.recordingBlobData = null;
        },

        sendTypingEvent(){
            if(this.chatto !== null){
                let payload = {
                ChatWith : this.chatto,
                id : this.currentUser.id,
                }
                Echo.join('chats')
                    .whisper('typing', payload);
            }
            else if(this.chatin !== null){
                let payload = {
                ChatIn : this.chatin,
                name : this.currentUser.name
                }
                Echo.join('chats')
                    .whisper('typing', payload);
            }
        },
        //save chat
        submit(){
            if(this.chatin == null){
                if(this.text){
                    this.emoStatus = false;
                    let currentTime = new Date();
                    let from = {}
                    this.$set(from,'id',this.currentUser.id)
                    this.$set(from,'name',this.currentUser.name)
                    
                    let messageData = {
                        text: this.text,
                        to: this.chatto,
                        from: from,
                        created_at:currentTime
                    };
                    this.messages.push(messageData);
                    let messageText = this.text;
                    this.text = "";
    
                    axios
                    .post(`/api/messages`, {
                        text: messageText,
                        to: this.chatto,
                        from: this.currentUser.id,
                    })
                    .then((res) => {
                        console.log("RES DATA", res.data.message);
                        // this.messages.push(res.data.message);
                        // this.text = "";
                    });
                }
                else if(this.recordingBlobData){
                    let formdata = new FormData();
                    formdata.append('voice',this.recordingBlobData);
                    formdata.append('from',this.currentUser.id);
                    formdata.append('to',this.chatto);
                    axios
                    .post(`/api/messages/voice`, formdata ,{
                        headers: {
                        'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((res) => {
                        if(res.errors){
                        this.$Notice.warning({
                            title: 'Something went wrong',
                            desc: res.errors
                        });
                        }
                        console.log("afterSendvoice",res);
                        this.removeRecordedAudio();
                        this.messages.push(res.data.message);
                    });
                }
            }
            else if(this.chatto == null){
                if(this.text){
                this.emoStatus = false;
                let currentTime = new Date();
                let from = {}
                this.$set(from,'id',this.currentUser.id)
                this.$set(from,'name',this.currentUser.name)
                
                let messageData = {
                    text: this.text,
                    to: this.chatto,
                    roomId: this.chatin,
                    from: from,
                    created_at:currentTime
                };
                this.messages.push(messageData);
                let messageText = this.text;
                this.text = "";
        
                axios
                    .post(`/api/messages`, {
                    text: messageText,
                    to: this.chatto,
                    roomId: this.chatin,
                    from: this.currentUser.id,
                    })
                    .then((res) => {
                    // this.messages.push(res.data.message);
                    // this.text = "";
                    });
                }
                else if(this.recordingBlobData){
                    let formdata = new FormData();
                    formdata.append('voice',this.recordingBlobData);
                    formdata.append('from',this.currentUser.id);
                    formdata.append('to',this.chatto);
                    formdata.append('roomId',this.chatin);
                    axios
                        .post(`/api/messages/voice`, formdata ,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then((res) => {
                    if(res.errors){
                        this.$Notice.warning({
                        title: 'Something went wrong',
                        desc: res.errors
                        });
                    }
                    this.removeRecordedAudio();
                    this.messages.push(res.data.message);
                    });
                }
            }
        },

        newline(){
            this.text = `${this.text}\n`
        },
        //emoji
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.text){
                this.text = e.native
            }else{
                this.text = this.text + e.native
            }
        },

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

        closeviewLocationMapModal(){
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
            // let widthOfScreen = (window.innerWidth > 0) ? window.innerWidth : screen.width;
            // this.playerOptions.width = widthOfScreen;
            // this.playerOptions.sources[0].src = "http://127.0.0.1:8000" + value.video;
            this.playerOptions.sources[0].src = "http://47.111.233.60" + value.video;
            this.playerOptions.poster = "/img/coverImage/chatVideoCoverImage.jpg";
        },

        showSendImageModal(){
            this.chatSendImageModal = true;
        },

        showSendVideoModal(){
            this.chatSendVideoModal = true;
        },

        showSendFileModal(){
            this.chatSendFileModal = true;
        },

        showSendMapModal(){
            this.chatSendMapModal = true;
        },

        handleImageUpload(file){
            this.sendImagefile = file;
            return false;
        },

        handleVideoUpload(file){
            this.sendVideofile = file;
            return false;
        },

        handleFileUpload(file){
            this.sendFilefile = file;
            return false;
        },

        handleImageFormatError(file){
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select png or jpeg.'
            });
        },

        handleVideoFormatError(file){
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select mp4.'
            });
        },

        handleImageMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 4M.'
            });
        },
        
        handleVideoMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 512M.'
            });
        },

        handleFileMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 512M.'
            });
        },

        closeSendImageModal(){
            this.chatSendImageModal = false;
            this.sendImagefile = null;
        },

        closeSendVideoModal(){
            this.chatSendVideoModal = false;
            this.sendVideofile = null;
        },

        closeSendFileModal(){
            this.chatSendFileModal = false;
            this.sendFilefile = null;
        },

        closeSendMapModal(){
            this.chatSendMapModal = false;
            this.sendMapInfo.lng = null;
            this.sendMapInfo.lat = null;
            this.sendMapInfo.zoom = null;
            this.sendMapInfo.address = '';
        },
        sendImageViaChat(){
        if(this.sendImagefile){
            let formdata = new FormData();
            formdata.append('file',this.sendImagefile)
            formdata.append('from',this.currentUser.id)
            formdata.append('to',this.chatto)
            formdata.append('roomId',this.chatin)
            axios
            .post(`/api/messages/image`, formdata ,{
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then((res) => {
                if(res.errors){
                this.$Notice.warning({
                    title: 'Something went wrong',
                    desc: res.errors
                });
                }
                this.messages.push(res.data.message);
            });
        }
        this.closeSendImageModal();
        },

        sendVideoViaChat(){
        if(this.sendVideofile){
            let formdata = new FormData();
            formdata.append('file',this.sendVideofile);
            formdata.append('from',this.currentUser.id);
            formdata.append('to',this.chatto);
            formdata.append('roomId',this.chatin);
            axios
            .post(`/api/messages/video`, formdata ,{
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then((res) => {
                if(res.errors){
                this.$Notice.warning({
                    title: 'Something went wrong',
                    desc: res.errors
                });
                }
                this.messages.push(res.data.message);
            });
        }
        this.closeSendVideoModal();
        },

        sendFileViaChat(){
        if(this.sendFilefile){
            let formdata = new FormData();
            formdata.append('file',this.sendFilefile);
            formdata.append('from',this.currentUser.id);
            formdata.append('to',this.chatto);
            formdata.append('roomId',this.chatin);
            axios
            .post(`/api/messages/file`, formdata ,{
                headers: {
                'Content-Type': 'multipart/form-data'
                }
            })
            .then((res) => {
                if(res.errors){
                this.$Notice.warning({
                    title: 'Something went wrong',
                    desc: res.errors
                });
                }
                console.log("afterSendFile",res);
                res.data.message.file = JSON.parse(res.data.message.file);
                console.log("afterconvertTOjson", res.data.message)
                this.messages.push(res.data.message);
            });
        }
        this.closeSendFileModal();
        },

        syncCenterAndZoom(e){
            const {lng, lat} = e.target.getCenter()
            this.sendMapInfo.lng = lng
            this.sendMapInfo.lat = lat
            this.sendMapInfo.zoom = e.target.getZoom()
        },

        sendMapViaChat(){
            let point = new BMap.Point(this.sendMapInfo.lng, this.sendMapInfo.lat);
            let gc = new BMap.Geocoder();
            let currentAddress = "";
            let self = this
            gc.getLocation(point, function(rs){
                self.sendMapInfo.address = rs.address;
                console.log("ttt", self.sendMapInfo);
            });
        },
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
.msg-container, .msg-container-send{
    max-width: 75%;
}
.emoji-area{
    position: absolute;
    z-index: 1;
    bottom: 90px;
    right: 0px;
}

.hv-100{
    height: 100vh;
}

.vertical-center-modal .ivu-modal-content {
    height: unset!important;
    width: unset!important;
}

.vertical-center-modal .ivu-modal-body {
    height: 100%!important;
}
.vjs-custom-skin > .video-js {
    height: 100%;
    width: 100%;
}

@media only screen and (max-width: 360px) {
    .recording-result audio{
        width: 200px!important;
        height: 30px!important;
    }
}

</style>