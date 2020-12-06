<template>
    <div class="cu-col-30 chat-user-list h-100 p-0 bg-light">
        <div class="h-100">
            <div class="chat-search-user pt-2 d-flex px-2">
                <Input v-model="searchContact" class="search-user-bar mr-auto" search placeholder="按名称搜索" />
                <!-- <Input class="search-user-bar" search enter-button placeholder="Enter something..." /> -->
                <Icon class="pl-1" size="31" color="#2D8CF0" @click="showAddFriendModal"  type="md-add-circle" />
            </div>

            <Modal
            v-model="addFriendModal"
            title="加入联络人"
            class-name="chat-modal"
            :styles="{top:'68px',left:'-245px'}"
            scrollable
            :mask-closable="false"
            @on-cancel="closedAddFriendModal"
            footer-hide
            >
                <div class="h-100 container-fluid overflow-auto">
                    <div class="row p-2 pt-4">
                        <div class="col-6">
                            <Input v-model="searchContact" class="search-user-bar mr-auto" search placeholder="按名称搜索" />
                        </div>
                        <div class="col-6 text-right">
                            <ButtonGroup shape="circle">
                                <Button @click="toggleIsCreatGroupFalse" type="info"><Icon size="20" class="mr-2" type="md-add" />加入联络人</Button>
                                <Button @click="toggleIsCreatGroupTrue" type="success"><Icon size="20" class="mr-2" type="ios-people" />新组</Button>
                            </ButtonGroup>
                        </div>
                    </div>
                    <div v-if="isCreateNewGroup">
                        <Divider dashed class="mb-0">contact list</Divider>
                        <div class="row p-2 justify-content-center">
                            <div class="col-12 d-flex justify-content-end align-items-center mb-3">
                                <Input :disabled="newGroup.length < 2" v-model="groupName" placeholder="请输入群组名称" style="width: 300px" class="mr-3" />
                                <Button :disabled="newGroup.length < 2" @click="createNewGroup" :loading="isCreatingNewGroup" type="info"><Icon size="20" class="mr-2" type="md-add" />create new group</Button>
                            </div>
                            <div 
                                class="col-1 col-150 text-center userContactListAvatar p-3"
                                v-for="user in contactList"
                                v-bind:key="user.user.id"
                                :class="{'selected':newGroup.includes(user.user.id)}"
                                @click="pushUserToNewGroup(user.user.id)"
                            >
                                <avatar :username="user.user.name" class="mx-auto"></avatar>
                                <p>{{user.user.name}}</p>
                                <Icon size="25" type="md-checkmark-circle" class="position-absolute new-group-selected-icon"/>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <Divider dashed class="mb-0">用户清单</Divider>
                        <div class="row p-2 justify-content-center">
                            <div class="col-12 text-right">
                                <Button :disabled="willAddToContactUser.contactId == null" @click="addUserToContact" :loading="isAdding" type="info" class="mb-3"><Icon size="20" class="mr-2" type="md-add" />加入联络人</Button>
                            </div>
                            <div 
                                class="col-1 col-150 text-center userContactListAvatar p-3"
                                v-for="user in users"
                                v-bind:key="user.id"
                                :class="{'selected':willAddToContactUser.contactId == user.id}"
                                @click="pushUserToList(user.id)"
                            >   
                                <avatar :username="user.name" class="mx-auto"></avatar>
                                <p>{{user.name}}</p>
                                <Icon size="25" type="md-checkmark-circle" class="position-absolute new-group-selected-icon"/>
                            </div>
                        </div>
                    </div>
                </div>
            </Modal>
            <div class="chat-contact-list mt-3">
                <ul class="list-group list-group-flush">
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
                    
                    <!-- private chat -->
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
                        <Badge class="align-items-center d-flex" :count="contactuser.new_msg_count" overflow-count="99">
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
                    <p class="text-center pt-3" v-else>请添加新联系人</p>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import Avatar from 'vue-avatar'
export default {
    props:{
        currentUser:{
            type: Object,
            required: true,
        },
        ChatWith: {
            type: Number,
            required: false,
        },
        ChatIn: {
            type: Number,
            required: false,
        },
    },
    components:{
        Avatar,
    },

    mounted(){
        this.listen();
    },

    data(){
        return{
            users:[],
            contactList:[],
            chatGroupList:[],
            newGroup: [],
            addFriendModal:false,
            willAddToContactUser : {
                contactId:null,
            },
            isAdding:false,
            searchContact:'',
            totalNewMessageCount:0,
            isCreateNewGroup:false,
            isCreatingNewGroup:false,
            groupName: '',
            activeUserList : []
        }
    },

    

    async created(){
        const res = await this.callApi('get','/api/chat/userList');
        if(res.status == 200){
            this.users = res.data;
            this.users = this.users.users.filter((user) => user.id !== this.currentUser.id);
        }
        const con = await this.callApi('get', '/api/chat/contactList');
        if(con.status == 200){
            // console.log("con.data", con.data);
            this.chatGroupList = con.data.chatGroups;
            this.contactList = con.data.contactUsers;
            // console.log("this.contactList", this.contactList);
            for(let i = 0; i < this.contactList.length ; i++){
                this.totalNewMessageCount = this.totalNewMessageCount + this.contactList[i].new_msg_count;
            }
            for(let i = 0; i < this.chatGroupList.length ; i++){
                this.totalNewMessageCount = this.totalNewMessageCount + this.chatGroupList[i].new_msg_count;
            }
            this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
        }
    },

    // computed: {
    //     userswithoutsigninuser() {
    //         console.log("beforeFilteringUser", this.users);
    //         return this.users.filter((user) => user.id !== this.currentUser.id);
    //     },
    // },
    
    computed:{
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
    methods: {
        updatechatwith(userInfo, index) {
            let userListItems = $('.list-group-item');
            for(let i = 0; i < userListItems.length ; i++){
                if(userListItems[i].classList.contains('selected')){
                    userListItems[i].classList.remove('selected');
                }
            }
            userListItems[this.chatGroupList.length + index].classList.add('selected');
            this.$emit("updatechatwith", userInfo);
            for(let i = 0; i < this.contactList.length; i++){
                if( userInfo.user.id == this.contactList[i].contactUserId ){
                    this.totalNewMessageCount = this.totalNewMessageCount - this.contactList[i].new_msg_count;
                    this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                    this.contactList[i].new_msg_count = 0;
                    const res = this.callApi('post','/api/chat/newMsgCount',{new_msg_count:this.contactList[i]})
                }
            }
        },
        updatechatIn(group, index) {
            let userListItems = $('.list-group-item');
            for(let i = 0; i < userListItems.length ; i++){
                if(userListItems[i].classList.contains('selected')){
                    userListItems[i].classList.remove('selected');
                }
            }
            userListItems[index].classList.add('selected');
            this.$emit("updatechatIn", group);
            for(let i = 0; i < this.chatGroupList.length; i++){
                if( group.roomId == this.chatGroupList[i].roomId ){
                    this.totalNewMessageCount = this.totalNewMessageCount - this.chatGroupList[i].new_msg_count;
                    this.$store.state.totalNewMsgCnt = this.totalNewMessageCount;
                    this.chatGroupList[i].new_msg_count = 0;
                    const res = this.callApi('post','/api/chat/newMsgCount',{new_msg_count:this.chatGroupList[i]})
                }
            }
        },
        showAddFriendModal(){
            this.addFriendModal = true;
        },
        pushUserToList(id){
            this.willAddToContactUser.contactId = id;
        },
        async addUserToContact(){
            this.isAdding = true;
            if(this.willAddToContactUser == null){
                this.info("请选择将添加到联系人的用户");
            }
            const res = await this.callApi('post', '/api/contact', this.willAddToContactUser)
            if(res.status == 200){
                let addedContact = res.data.addedToContactUser[0];
                this.contactList.unshift(addedContact);
            }
            else if(res.status == 409){
                this.info("您已经将该用户添加为联系人");
            }
            else{
                this.info("出问题了");
            }
            this.isAdding = false;
            this.willAddToContactUser.contactId = null;
            this.addFriendModal = false;
        },

        checkOnline(userId){
            for(let i = 0; i < this.activeUserList.length; i++){
                if(this.activeUserList[i].id == userId){
                    return true;
                }
            }
            return false;
        },

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
                    console.log("********!!!!!!!!***********", message);
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
                })
                
        },

        toggleIsCreatGroupFalse(){
            this.isCreateNewGroup = false;
            this.newGroup = [];
        },

        toggleIsCreatGroupTrue(){
            this.isCreateNewGroup = true;
            this.willAddToContactUser.contactId = null;
        },

        pushUserToNewGroup(userId){
            if(this.newGroup.includes(userId)){
                for( let i = 0; i < this.newGroup.length; i++){ 
                    if ( this.newGroup[i] == userId) { 
                        this.newGroup.splice(i, 1); 
                    }
                }
            }
            else{
                this.newGroup.push(userId);
            }
        },

        createNewGroup(){
            
            if(this.groupName.trim() == ''){
                return this.error("请输入群组名称");
            }
            let payload = {
                newgroup: this.newGroup,
                groupName: this.groupName
            }
            console.log("this.newGroup",payload);
            this.isCreatingNewGroup = true;
            axios.post(`/api/messages/newgroup`, payload)
            .then(res=>{
                console.log(res.data);
                this.chatGroupList.unshift(res.data.newGroup);
                this.isCreatingNewGroup = false;
                this.newGroup = [];
                this.groupName = '';
                this.willAddToContactUser.contactId = null;
                this.isCreateNewGroup = false;
                this.addFriendModal = false;
            })
            .catch(err=>{
                this.isCreatingNewGroup = false;
                console.log(err.response);
            })
        },

        closedAddFriendModal(){
            this.newGroup = [];
            this.willAddToContactUser.contactId = null;
            this.isCreateNewGroup = false;
            this.groupName = '';
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
        }

    },

}
</script>

<style scoped>
    .demo-badge{
        width: 42px;
        height: 42px;
        background: #eee;
        border-radius: 6px;
        display: inline-block;
    }
</style>