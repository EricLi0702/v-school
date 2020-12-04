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
                <!-- group chat -->
                <router-link :to="{path:currentpath.path,query:{questionType:currentpath.query.questionType,addQuestion:'chatSpec'}}">
                    <!-- group chat -->
                    <li 
                        v-if="chatGroupList.length"
                        class="list-group-item d-flex"
                        v-for="(group, i) in chatGroupList" 
                        :key="'A'+ i"
                        :class="{'selected':ChatIn === group.roomId}"
                        @click="updatechatIn(group, i)"
                    >
                        <div class="ch-user-avatar">
                            <avatar :username="group.room_id.roomName" :rounded="false"></avatar>
                        </div>
                        <div class="ch-user-info">
                            <p class="ch-user-info-name ellipsis p-3">
                            {{group.room_id.roomName}}
                            </p>
                        </div>
                        <Badge class="align-items-center d-flex" :count="group.new_msg_count" overflow-count="99">
                        </Badge>
                        <div class="dots-menu btn-group chat-contact-item-three-dot-icon">
                            <Icon size="25" type="ios-more" class="" data-toggle="dropdown"/>
                            <ul class="dropdown-menu">
                                <li class="d-flex p-2" @click="leaveGroup(group)">
                                    <Icon size="25" type="ios-undo" class="mr-2"/>
                                    <p class="m-0 p-0">离开团体</p>
                                </li>
                                <li class="d-flex p-2" v-if="group.room_id.userId == currentUser.id" @click="removeGroup(group)">
                                    <Icon size="25" type="ios-trash" class="mr-2"/>
                                    <p class="m-0 p-0">删除群组</p>
                                </li>
                            </ul>
                        </div>
                    </li>
                </router-link>
                <!-- private chat -->
                <router-link :to="{path:currentpath.path,query:{questionType:currentpath.query.questionType,addQuestion:'chatSpec'}}">
                    <li 
                        v-if="filteredContacts.length"
                        class="list-group-item d-flex"
                        v-for="(contactuser, i) in filteredContacts"
                        :key="i"
                        :class="{'selected':ChatWith === contactuser.user.id}"
                        @click="updatechatwith(contactuser, i)"
                    >
                        <div v-if="checkOnline(contactuser.user.id)" class="ch-user-avatar">
                            <Badge dot type="success" class="online-user-status">
                                <avatar :username="contactuser.user.name"></avatar>
                            </Badge>
                        </div>
                        <div v-else class="ch-user-avatar">
                            <Badge dot type="error" class="offline-user-status">
                                <avatar :username="contactuser.user.name"></avatar>
                            </Badge>
                        </div>

                        <div class="ch-user-info">
                            <p class="ch-user-info-name ellipsis p-3">
                            {{contactuser.user.name}}
                            </p>
                        </div>
                        <Badge class="ml-auto align-items-center d-flex" :count="contactuser.new_msg_count" overflow-count="99">
                        </Badge>
                        <div class="dots-menu btn-group chat-contact-item-three-dot-icon">
                            <Icon size="25" type="ios-more" class="" data-toggle="dropdown"/>
                            <ul class="dropdown-menu">
                                <li class="d-flex p-2">
                                    <Icon size="25" type="ios-trash" class="mr-2"/>
                                    <p class="m-0 p-0">删除</p>
                                </li>
                            </ul>
                        </div>
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
        :chatin="ChatIn"
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
            chatGroupList:[],
            activeUserList: [],
            totalNewMessageCount:0,
            ChatWith: null,
            ChatIn:null,
            messages:[],
            chatWithUserInfo:''
        }
    },

    mounted(){
        this.listen();
    },

    async created(){
        this.currentUser = this.$store.state.user;
        this.ChatWith = this.currentUser.id;
        this.token = window.Laravel.csrfToken;

        const res = await this.callApi('get','/api/chat/userList');
        if(res.status == 200){
            this.users = res.data;
            this.users = this.users.users.filter((user) => user.id !== this.currentUser.id);
        }
        const con = await this.callApi('get', '/api/chat/contactList');
        if(con.status == 200){
            this.chatGroupList = con.data.chatGroups;
            this.contactList = con.data.contactUsers;
            for(let i = 0; i < this.contactList.length ; i++){
                this.totalNewMessageCount = this.totalNewMessageCount + this.contactList[i].new_msg_count;
            }
            for(let i = 0; i < this.chatGroupList.length ; i++){
                this.totalNewMessageCount = this.totalNewMessageCount + this.chatGroupList[i].new_msg_count;
            }
            this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
        }
    },

    computed:{
         currentpath(){
            return this.$route
        },
        filteredContacts(){
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

        listen(){
            Echo.private('group')
                .listen('NewGroup', (e) => {
                    if(e.group.room_id.invited !== null){
                        let invitedArr = JSON.parse(e.group.room_id.invited);
                        if(invitedArr.includes(this.currentUser.id)){
                            this.chatGroupList.unshift(e.group);
                        }
                    }
                    else if(e.group.room_id.invited == null){
                        let removedGroupId = e.group.roomId;
                        for (let i = 0; i < this.chatGroupList.length ; i++){
                            if( this.chatGroupList[i].roomId == removedGroupId){
                                this.chatGroupList.splice(i, 1);
                            }
                        }
                    }
                });
            Echo.join('chats')
                .here(user=>{
                    this.activeUserList = user;
                })
                .joining(user=>{
                    this.activeUserList.push(user);
                })
                .leaving(user=>{
                    this.activeUserList = this.activeUserList.filter(u => u.id != user.id);
                })
                .listen('NewMessage', (message) => {
                    console.log("@@@@@@@@@@@@@@@@@@@@@@@@@s", message);
                    if ( message.message.to == this.currentUser.id ) {
                        console.log("Badge", message.message.from.id);
                        for(let i = 0; i < this.contactList.length; i++){
                            if( message.message.from.id == this.contactList[i].contactUserId ){
                                this.totalNewMessageCount = this.totalNewMessageCount + 1;
                                this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                                this.contactList[i].new_msg_count = this.contactList[i].new_msg_count + 1;
                                const res = this.callApi('post','/api/chat/newMsgCount',{new_msg_count:this.contactList[i]})
                            }
                        }
                    }
                    else if ( (JSON.parse(message.message.room_id.invited)).includes(this.currentUser.id) || message.message.room_id.userId == this.currentUser.id ) {
                        console.log("Badge", message.message.from.id);
                        for(let i = 0; i < this.chatGroupList.length; i++){
                            if( message.message.roomId == this.chatGroupList[i].roomId ){
                                this.totalNewMessageCount = this.totalNewMessageCount + 1;
                                this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                                this.chatGroupList[i].new_msg_count = this.chatGroupList[i].new_msg_count + 1;
                                const res = this.callApi('post','/api/chat/newMsgCount',{new_msg_count:this.chatGroupList[i]})
                            }
                        }
                    }
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
                    else if(message.message.roomId == this.ChatIn){
                        if(message.message.file){
                            message.message.file = JSON.parse(message.message.file);
                        }
                        if(message.message.map){
                            message.message.map = JSON.parse(message.message.map);
                        }
                        this.messages.push(message.message);
                    }
                })
                
        },
        //add friends
        contactLists(value) {
            this.contactList.unshift(value);
            console.log("emit", this.contactList);
        },

        updatechatwith(userInfo, index){
            let userListItems = $('.list-group-item');
            for(let i = 0; i < userListItems.length ; i++){
                if(userListItems[i].classList.contains('selected')){
                    userListItems[i].classList.remove('selected');
                }
            }
            userListItems[this.chatGroupList.length + index].classList.add('selected');
            let userid = userInfo.user.id;
            this.ChatWith = userid;
            this.ChatIn = null;
            this.chatWithUserInfo = userInfo.user.name;
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

        updatechatIn(group, index) {
            let userListItems = $('.list-group-item');
            for(let i = 0; i < userListItems.length ; i++){
                if(userListItems[i].classList.contains('selected')){
                    userListItems[i].classList.remove('selected');
                }
            }
            userListItems[index].classList.add('selected');
            this.ChatWith = null;
            this.ChatIn = group.roomId;
            this.chatWithUserInfo = group.room_id.roomName;
            for(let i = 0; i < this.chatGroupList.length; i++){
                if( group.roomId == this.chatGroupList[i].roomId ){
                    this.totalNewMessageCount = this.totalNewMessageCount - this.chatGroupList[i].new_msg_count;
                    this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                    this.chatGroupList[i].new_msg_count = 0;
                    const res = this.callApi('post','/api/chat/newMsgCount',{new_msg_count:this.chatGroupList[i]})
                }
            }
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


        leaveGroup(group){
            axios.post(`/api/messages/leavegroup`, {roomId : group.roomId})
            .then(res=>{
                console.log(res);
                if(res.data.msg == 1){
                    let removedGroupId = res.data.roomId;
                    for (let i = 0; i < this.chatGroupList.length ; i++){
                        if( this.chatGroupList[i].roomId == removedGroupId){
                            this.chatGroupList.splice(i, 1);
                        }
                    }
                }
            })
            .catch(err=>{
                console.log(err.response);
            })
        },

        removeGroup(group){
            let payload = {
                roomId : group.roomId
            }
            axios.delete(`/api/messages/removeGroup`, {data : payload})
            .then(res=>{
                console.log(res);
                if(res.data.msg == 1){
                    let removedGroupId = group.roomId;
                    for (let i = 0; i < this.chatGroupList.length ; i++){
                        if( this.chatGroupList[i].roomId == removedGroupId){
                            this.chatGroupList.splice(i, 1);
                        }
                    }
                }
            })
            .catch(err=>{
                console.log(err.response);
            })
        },

        checkOnline(userId){
            for(let i = 0; i < this.activeUserList.length; i++){
                if(this.activeUserList[i].id == userId){
                    return true;
                }
            }
            return false;
        },


    }
}
</script>