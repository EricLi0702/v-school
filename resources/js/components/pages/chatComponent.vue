<template>
  <div class="container-fluid row p-0 m-0 h-100">
    <ChatList
      :currentUser="currentUser" 
      :ChatWith="ChatWith"
      @updatechatwith="updatechatwith"
    />
    <div class="col-9 chat-message-area h-100 p-0">
      <ChatArea :chatto="ChatWith" :messages="messages" :chatfrom="currentUser.id" />
      
      <div class="ch-message-footer h-25">
        <div class="ch-footer-container p-4">
          <Input v-model="text" type="textarea" :rows="4" @keyup.enter="submit" placeholder="Enter message..." />
          <div class="ch-footer-below row px-3 pt-3">
            <div class="ch-footer-upload-icon-area mr-auto">
              <Icon class="pr-2" size="25" color="white" type="ios-image" />
              <Icon class="pr-2" size="25" color="white" type="ios-folder" />
              <Icon class="pr-2" size="25" color="white" type="ios-film" />
              <Icon class="pr-2" size="25" color="white" type="ios-locate" />
            </div>
            <div class="ch-footer-send-area ml-auto">
              <Icon size="25" color="white" type="ios-mic" />
              <Button shape="circle" @click="submit">Send</Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ChatList from "./chat/chatListComponent";
import ChatArea from "./chat/chatAreaComponent";



export default {

  components:{
    ChatList,
    ChatArea,
  },
  data(){
    return{
      text:'',
      currentUser:'',
      users:[],
      ChatWith: null,
      messages:[],
    }
  },
  created(){
    this.currentUser = this.$store.state.user;
    this.ChatWith = this.currentUser.id;
  },
  mounted(){
    this.listen();
  },
  methods:{

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
          this.messages = res.data.messages;
          console.log("getCurrentMessages",this.messages);
        });
    },

    //save chat
    submit(){
      if(this.text){
        axios
          .post(`/api/messages`, {
            text: this.text,
            to: this.ChatWith,
            from: this.currentUser.id,
          })
          .then((res) => {
            this.messages.push(res.data.message);
          });
      }
      this.text = "";
    },
    listen(){
      Echo.private('chats')
          .listen('NewMessage', (message) => {
            if (
              message.message.to == this.currentUser.id &&
              message.message.from.id == this.ChatWith
            ) {
              this.messages.push(message.message);
            }
          });
    }     
  }
}
</script>