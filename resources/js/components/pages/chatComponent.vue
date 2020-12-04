<template>
  <div class="container-fluid row p-0 m-0 h-100">
    <ChatList
      :currentUser="currentUser" 
      :ChatWith="ChatWith"
      :ChatIn="ChatIn"
      @updatechatwith="updatechatwith"
      @updatechatIn="updatechatIn"
    />
    <div class="cu-col-70 chat-message-area h-100 p-0">
      <p class="p-1 bg-light-gray m-1 border-radius-50 now-contact-Info px-3">
        {{contactNow}} 
        <span v-if="userTypingNow">typing...</span> 
        <span v-if=" 3 > groupTypingList.length > 0" v-for="(username, i) in groupTypingList" :key="i">{{username}} is typing. </span> 
        <span v-if=" 3 < groupTypingList.length" >超过2人在打字</span> 
      </p>
      <ChatArea :chatto="ChatWith" :chatin="ChatIn" :messages="messages" :chatfrom="currentUser.id" />
      
      <div class="ch-message-footer h-25 bg-white">
        <div class="emoji-area-popup">
          <Picker v-if="emoStatus" set="emojione" @select="onInput" title="选择你的表情符号..." />
        </div>
        <div class="ch-footer-container p-4">
          <textarea 
            class="custom-textarea"
            :disabled="recording.src !== null" 
            v-model="text" 
            :rows="4" 
            @keydown.enter.exact.prevent 
            @keyup.enter.exact="newline" 
            @keydown.enter.shift.exact="submit" 
            @keydown.enter.shift.exact.prevent
            @keydown="sendTypingEvent"
            placeholder="Enter message..." ></textarea>
          <div class="ch-footer-below row px-3 pt-3">
            <div v-if="recording.src == null" class="pt-2 ch-footer-upload-icon-area mr-auto">
              <Icon @click="showSendImageModal" class="pr-2 msg-upload-icons" size="25" type="ios-image" />
              <Icon @click="showSendFileModal" class="pr-2 msg-upload-icons" size="25" type="ios-folder" />
              <Icon @click="showSendVideoModal" class="pr-2 msg-upload-icons" size="25" type="ios-film" />
              <Icon @click="showSendMapModal" class="pr-2 msg-upload-icons" size="25" type="ios-locate" />
              <Icon @click="toggleEmo" class="pr-2 msg-upload-icons" size="25" type="md-happy" />
            </div>
            <div v-if="recording.src !== null" class="recording-result ml-auto position-relative">
              <audio  :src="recording.src" controls />
              <Icon class="remove-record-icon" @click="removeRecordedAudio" type="ios-close-circle" />
            </div>
            <div class="ch-footer-send-area ml-auto">
              <VueRecordAudio  mode="press" @stream="onStream" @result="onResult" />
              <Button class="mb-5 ml-2" shape="circle" @click="submit">发送</Button>
            </div>
          </div>
        </div>
      </div>
    </div>
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
        <h1 class="p-3">Choose Image</h1>
          <Upload
              ref="uploads"
              type="drag"
              accept="image/png, image/jpeg, image/jpg,image/bmp"
              :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
              :format="['jpg','jpeg','png']"
              :max-size="4096"
              :on-exceeded-size="handleImageMaxSize"
              :before-upload="handleImageUpload"
              :on-format-error="handleImageFormatError"
              action="/api/messages/image">
              <div style="padding: 20px 0">
                  <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                  <p>Click or drag image here to send(jpg, png)</p>
              </div>
          </Upload>
          <div v-if="sendImagefile !== null">Selected Image File: {{ sendImagefile.name }} 
          </div>

          <div slot="footer">
              <Button type="default" @click="closeSendImageModal">Close</Button>
              <Button :disabled="sendImagefile == null" type="primary" @click="sendImageViaChat">Send Image</Button>
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
        <h1 class="p-3">Choose Video</h1>
          <Upload
              ref="uploads"
              type="drag"
              accept="video/mp4"
              :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
              :format="['mp4']"
              :max-size="524288"
              :on-exceeded-size="handleVideoMaxSize"
              :before-upload="handleVideoUpload"
              :on-format-error="handleVideoFormatError"
              action="/api/messages/video">
              <div style="padding: 20px 0">
                  <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                  <p>单击或拖动视频以发送（mp4）</p>
              </div>
          </Upload>
          <div v-if="sendVideofile !== null">Selected Video File: {{ sendVideofile.name }} 
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
        <h1 class="p-3">Choose File</h1>
          <Upload
              ref="uploads"
              type="drag"
              :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
              :max-size="524288"
              :on-exceeded-size="handleFileMaxSize"
              :before-upload="handleFileUpload"
              accept=".doc, .docx, .zip, .pdf, .xls, .xlsx, .rp, .mp3, .rp, .ppt, .pptx, .pptm, .apk, .rar"
              action="/api/messages/file">
              <div style="padding: 20px 0">
                  <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                  <p>Click or drag file here to send</p>
              </div>
          </Upload>
          <div v-if="sendFilefile !== null">Selected File: {{ sendFilefile.name }} 
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
      class-name="chat-send-modal h-long"
      > 
        <h1 class="p-3">Select Location</h1>
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
            <Button type="default" @click="closeSendMapModal">Close</Button>
            <Button 
              :disabled=" sendMapInfo.lng == null ||
                          sendMapInfo.lat == null ||
                          sendMapInfo.zoom == null" 
              type="primary" 
              @click="sendMapViaChat"
            >Send Location</Button>
        </div>
    </Modal>




    
  </div>
</template>

<script>
import ChatList from "./chat/chatListComponent";
import ChatArea from "./chat/chatAreaComponent";
import { Picker } from 'emoji-mart-vue'


export default {

  components:{
    ChatList,
    ChatArea,
    Picker,
  },
  data(){
    return{
      token:'',
      text:'',
      currentUser:'',
      users:[],
      ChatWith: null,
      ChatIn: null,
      contactNow: null,
      messages:[],
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
      // getAddress:{
      //   lng:null,
      //   lat:null,
      // },
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

      //recording
      recording: {
        src:null,
      },
      recordingBlobData:null,
      //emoji
      emoStatus:false,

      //typing now
      userTypingNow : false,
      typingTimer: false,
      groupTypingList: [],

    }
  },
  created(){
    this.currentUser = this.$store.state.user;
    this.ChatWith = this.currentUser.id;
    this.token = window.Laravel.csrfToken;
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
              to : this.ChatWith,
              roomId : this.ChatIn,
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
  mounted(){
    this.listen();
  },
  methods:{
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


    //chat partner select
    updatechatwith(userInfo) {
      this.ChatWith = userInfo.user.id;
      this.contactNow = userInfo.user.name;
      this.ChatIn = null;
      this.getMessage();
    },
    //chat room select
    updatechatIn(group) {
      this.ChatIn = group.roomId;
      this.contactNow = group.room_id.roomName;
      this.ChatWith = null;
      this.getMessageGroup();
    },

    //chat history
    getMessage() {
      axios
        .get(`/api/messages`, {
          params: {
            to: this.ChatWith,
            from: this.currentUser.id,
          },
        })
        .then((res) => {
          for(let i = 0; i < res.data.messages.length ; i++){
            if(res.data.messages[i].file){
              res.data.messages[i].file = JSON.parse(res.data.messages[i].file);
            }
            if(res.data.messages[i].map){
              res.data.messages[i].map = JSON.parse(res.data.messages[i].map);
            }
          }
          this.messages = res.data.messages;
          
        });
    },
    //groupchat history
    getMessageGroup() {
      axios
        .get(`/api/messages/group`, {
          params: {
            to: this.ChatIn,
            from: this.currentUser.id,
          },
        })
        .then((res) => {
          console.log(res.data);
          for(let i = 0; i < res.data.messages.length ; i++){
            if(res.data.messages[i].file){
              res.data.messages[i].file = JSON.parse(res.data.messages[i].file);
            }
            if(res.data.messages[i].map){
              res.data.messages[i].map = JSON.parse(res.data.messages[i].map);
            }
          }
          this.messages = res.data.messages;
          
        });
    },

    sendTypingEvent(){
      if(this.ChatWith !== null){
        let payload = {
          ChatWith : this.ChatWith,
          id : this.currentUser.id,
        }
        Echo.join('chats')
            .whisper('typing', payload);
      }
      else if(this.ChatIn !== null){
        let payload = {
          ChatIn : this.ChatIn,
          name : this.currentUser.name
        }
        Echo.join('chats')
            .whisper('typing', payload);
      }
    },
    //save chat
    submit(){
      console.log("this.ChatWith", this.ChatWith);
      console.log("this.ChatIn", this.ChatIn);
      if(this.text.trim() == '' && this.recordingBlobData == null){
        return this.error("please write something.");
      }
      if(this.ChatIn == null){
        if(this.text){
          this.emoStatus = false;
          let currentTime = new Date();
          let from = {}
          this.$set(from,'id',this.currentUser.id)
          this.$set(from,'name',this.currentUser.name)
          
          let messageData = {
            text: this.text,
            to: this.ChatWith,
            from: from,
            created_at:currentTime
          };
          this.messages.push(messageData);
          let messageText = this.text;
          this.text = "";
  
          axios
            .post(`/api/messages`, {
              text: messageText,
              to: this.ChatWith,
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
          formdata.append('to',this.ChatWith);
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
      else if(this.ChatWith == null){
        if(this.text){
          this.emoStatus = false;
          let currentTime = new Date();
          let from = {}
          this.$set(from,'id',this.currentUser.id)
          this.$set(from,'name',this.currentUser.name)
          
          let messageData = {
            text: this.text,
            to: this.ChatWith,
            roomId: this.ChatIn,
            from: from,
            created_at:currentTime
          };
          this.messages.push(messageData);
          let messageText = this.text;
          this.text = "";
  
          axios
            .post(`/api/messages`, {
              text: messageText,
              to: this.ChatWith,
              roomId: this.ChatIn,
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
          formdata.append('to',this.ChatWith);
          formdata.append('roomId',this.ChatIn);
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

    //listen event
    listen(){
      Echo.join('chats')
          .listenForWhisper('typing', (e) => {
            if(this.ChatWith !== null){
              if(this.ChatWith == e.id && this.currentUser.id == e.ChatWith){
                this.userTypingNow = true;
  
                if(this.typingTimer){
                  clearTimeout(this.typingTimer);
                }
  
                this.typingTimer = setTimeout(()=>{
                  this.userTypingNow = false;
                }, 3000)
              }
            }
            else if (this.ChatIn !== null){
              if(this.ChatIn == e.ChatIn){
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
          })
          .listen('NewMessage', (message) => {
            if (
              message.message.to == this.currentUser.id &&
              message.message.from.id == this.ChatWith
            ) {
              if(message.message.file){
                message.message.file = JSON.parse(message.message.file);
              }
              if(message.message.map){
                message.message.map = JSON.parse(message.message.map);
              }
              
              this.messages.push(message.message);
            }
            if(message.message.roomId == this.ChatIn){
              if(message.message.file){
                message.message.file = JSON.parse(message.message.file);
              }
              if(message.message.map){
                message.message.map = JSON.parse(message.message.map);
              }
              this.messages.push(message.message);
            }
          });
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

    //recording
    onResult (data) {
      data.name = "oh.wav";
      data.lastModifiedDate = new Date();
      this.recordingBlobData = data;
      this.recording.src = window.URL.createObjectURL(data);
    },

    onStream (stream) {
    },

    removeRecordedAudio(){
      this.recording.src = null;
      this.recordingBlobData = null;
    },


    sendImageViaChat(){
      console.log("this.ChatWith", this.ChatWith);
      console.log("this.ChatIn", this.ChatIn);
      if(this.sendImagefile){
        let formdata = new FormData();
        formdata.append('file',this.sendImagefile)
        formdata.append('from',this.currentUser.id)
        formdata.append('to',this.ChatWith)
        formdata.append('roomId',this.ChatIn)
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
        formdata.append('to',this.ChatWith);
        formdata.append('roomId',this.ChatIn);
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
        formdata.append('to',this.ChatWith);
        formdata.append('roomId',this.ChatIn);
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
            res.data.message.file = JSON.parse(res.data.message.file);
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
      });
    },

    newline(){
      this.text = `${this.text}\n`
    },
  }
}
</script>