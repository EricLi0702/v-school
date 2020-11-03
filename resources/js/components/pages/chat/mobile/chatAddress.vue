<template>
<div>
    <div v-if="currentpath.query.addQuestion == undefined" class="container-fluid h-100 p-0 position-relative">
        <div class="chat-search-user d-flex p-3 align-items-center">
            <Input v-model="searchContact" class="search-user-bar mr-auto" search placeholder="按名称搜索" />
            <router-link :to="{path:currentpath.path,query:{questionType:currentpath.query.questionType,addQuestion:'addContact'}}">
                <Icon class="pl-3" size="40" color="#2D8CF0" type="md-add-circle" />
            </router-link>
        </div>
        <div class="chat-contact-list mt-3">
            <ul class="list-group list-group-flush">
                <router-link :to="{path:currentpath.path,query:{questionType:currentpath.query.questionType,addQuestion:'chatSpec'}}">
                    <li 
                        v-if="filteredContacts.length"
                        class="list-group-item d-flex"
                        v-for="contactuser in filteredContacts"
                        v-bind:key="contactuser.user.id"
                        :class="{'selected':ChatWith === contactuser.user.id}"
                        @click="updatechatwith(contactuser)"
                    >
                        <div class="ch-user-avatar">
                            <avatar :username="contactuser.user.name"></avatar>
                            <!-- <img class="rounded-circle border-primary" src="/img/icon/我的.png" alt=""> -->
                        </div>
                        <div class="ch-user-info">
                            <p class="ch-user-info-name ellipsis p-3">
                            {{contactuser.user.name}}
                            </p>
                        </div>
                        <Badge class="ml-auto align-items-center d-flex" :count="contactuser.new_msg_count" overflow-count="99">
                        </Badge>
                    </li>
                </router-link>
                <p class="text-center pt-3" v-if="filteredContacts.length == 0">请添加新联系人</p>
            </ul>
        </div>
    </div>
    <div v-else-if="currentpath.query.addQuestion == 'addContact'">
        <addContact
        :contactList="contactList" 
        :users="users"
        @contactLists="contactLists"
        ></addContact>
    </div>
    <div v-else-if="currentpath.query.addQuestion == 'chatSpec'">
        <chatSpec
        :chatto="ChatWith"
        :chatfrom="currentUser.id"
        :messages="messages"
        :chatToInfo="chatWithUserInfo"
        ></chatSpec>
    </div>
</div>
</template>

<script>
import addContact from './addContact'
import chatSpec from './chatSpec'
import Avatar from 'vue-avatar'
export default {
    components:{
        addContact,
        chatSpec,
        Avatar,
    },
    data(){
        return{
            currentUser:'',
            users:[],
            searchContact:'',
            contactList:[],
            totalNewMessageCount:0,
            ChatWith: null,
            messages:[],
            chatWithUserInfo:{}
        }
    },
    async created(){
        this.currentUser = this.$store.state.user;
        this.ChatWith = this.currentUser.id;
        this.token = window.Laravel.csrfToken;

        const res = await this.callApi('get','api/chat/userList');
        if(res.status == 200){
            this.users = res.data;
            this.users = this.users.users.filter((user) => user.id !== this.currentUser.id);
        }
        const con = await this.callApi('get', 'api/chat/contactList');
        if(con.status == 200){
            this.contactList = con.data.contactUsers;
            for(let i = 0; i < this.contactList.length ; i++){
                this.totalNewMessageCount = this.totalNewMessageCount + this.contactList[i].new_msg_count;
            }
            this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
        }
    },

    computed:{
         currentpath(){
            return this.$route
        },
        filteredContacts(){
            console.log('-----',this.contactList)
            if(this.searchContact){
                console.log(this.searchContact);
                return this.contactList.filter((item) => {
                    return (item.user.name.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.subject.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.description.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.created_at.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                });
            } else{
                return this.contactList;
            }
        },
    },

    methods:{
        //add friends
        contactLists(value) {
            this.contactList.unshift(value);
            console.log("emit", this.contactList);
        },

        updatechatwith(user){
            console.log("wwww", user);
            let userid = user.user.id;
            this.ChatWith = userid;
            this.chatWithUserInfo = user;
            for(let i = 0; i < this.contactList.length; i++){
                if( userid == this.contactList[i].contactUserId ){
                    this.totalNewMessageCount = this.totalNewMessageCount - this.contactList[i].new_msg_count;
                    this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                    this.contactList[i].new_msg_count = 0;
                    const res = this.callApi('post','/api/chat/newMsgCount',{new_msg_count:this.contactList[i]})
                }
            }
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
            console.log("messages", this.messages);
            });
        },


    }
}
</script>