<template>
  <div class="container-fluid row p-0 m-0 h-100">
    <ChatList
      :currentUser="currentUser" 
      :ChatWith="ChatWith"
      @updatechatwith="updatechatwith"
    />
    <div class="cu-col-70 chat-message-area h-100 p-0">
      <ChatArea :chatto="ChatWith" :messages="messages" :chatfrom="currentUser.id" />
      
      <div class="ch-message-footer h-25 bg-white">
        <div class="emoji-area-popup">
          <Picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji..." />
        </div>
        <div class="ch-footer-container p-4">
          <Input :disabled="recording.src !== null" v-model="text" type="textarea" :rows="4" @keyup.enter="submit" placeholder="Enter message..." />
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
              <Button class="mb-5 ml-2" shape="circle" @click="submit">Send</Button>
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
              action="api/messages/image">
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
              action="api/messages/video">
              <div style="padding: 20px 0">
                  <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                  <p>Click or drag video here to send(mp4)</p>
              </div>
          </Upload>
          <div v-if="sendVideofile !== null">Selected Video File: {{ sendVideofile.name }} 
          </div>

          <div slot="footer">
              <Button type="default" @click="closeSendVideoModal">Close</Button>
              <Button :disabled="sendVideofile == null" type="primary" @click="sendVideoViaChat">Send Video</Button>
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
              action="api/messages/file">
              <div style="padding: 20px 0">
                  <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                  <p>Click or drag file here to send</p>
              </div>
          </Upload>
          <div v-if="sendFilefile !== null">Selected File: {{ sendFilefile.name }} 
          </div>

          <div slot="footer">
              <Button type="default" @click="closeSendFileModal">Close</Button>
              <Button :disabled="sendFilefile == null" type="primary" @click="sendFileViaChat">Send File</Button>
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
    updatechatwith(value) {
      this.ChatWith = value;
      this.getMessage();
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

    //save chat
    submit(){
      if(this.text){
        this.emoStatus = false;
        let currentTime = new Date();
        let from = {}
        this.$set(from,'id',this.currentUser.id)
        
        let messageData = {
          text: this.text,
          to: this.ChatWith,
          from: from,
          created_at:currentTime
        };
        console.log("Push message", messageData);
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
            console.log("RES DATA", res.data.message);
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
            console.log("afterSendvoice",res);
            this.removeRecordedAudio();
            this.messages.push(res.data.message);
          });
      }
      
    },

    //listen event
    listen(){
      Echo.private('chats')
          .listen('NewMessage', (message) => {
            if (
              message.message.to == this.currentUser.id &&
              message.message.from.id == this.ChatWith
            ) {
              if(message.message.file){
                message.message.file = JSON.parse(message.message.file);
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


    sendImageViaChat(){
      if(this.sendImagefile){
        let formdata = new FormData();
        formdata.append('file',this.sendImagefile)
        formdata.append('from',this.currentUser.id)
        formdata.append('to',this.ChatWith)
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
      });
    },

  }
}
</script>