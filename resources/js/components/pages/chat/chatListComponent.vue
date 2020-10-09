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
            footer-hide
            >
                <div class="h-100 container-fluid">
                    <div class="row p-2 pt-4">
                        <div class="col-9">
                            <Input v-model="searchContact" class="search-user-bar mr-auto" search placeholder="按名称搜索" />
                        </div>
                        <div class="col-3">
                            <Button @click="addUserToContact" :loading="isAdding" type="info">加入联络人</Button>
                        </div>
                    </div>
                    <Divider dashed >用户清单</Divider>
                    <div class="row p-2 justify-content-center">
                        <div 
                            class="col-1 col-150 text-center userContactListAvatar"
                            v-for="user in users"
                            v-bind:key="user.id"
                            :class="{'selected':willAddToContactUser.contactId == user.id}"
                            @click="pushUserToList(user.id)"
                        >
                            <img src="/img/icon/我的.png" alt="" class="w-100">
                            <p>{{user.name}}</p>
                        </div>
                    </div>
                </div>
            </Modal>
            <div class="chat-contact-list mt-3">
                <ul class="list-group list-group-flush">
                    
                    <li 
                        v-if="filteredContacts.length"
                        class="list-group-item d-flex"
                        v-for="contactuser in filteredContacts"
                        v-bind:key="contactuser.user.id"
                        :class="{'selected':ChatWith === contactuser.user.id}"
                        @click="updatechatwith(contactuser.user.id)"
                    >
                        <div class="ch-user-avatar">
                            <img class="rounded-circle border-primary" src="/img/icon/我的.png" alt="">
                        </div>
                        <div class="ch-user-info">
                            <p class="ch-user-info-name ellipsis p-3">
                            {{contactuser.user.name}}
                            </p>
                        </div>
                    </li>
                    <p class="text-center pt-3" v-else>请添加新联系人</p>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
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
    },

    data(){
        return{
            users:[],
            contactList:[],
            addFriendModal:false,
            willAddToContactUser : {
                contactId:null,
            },
            isAdding:false,
            searchContact:'',

        }
    },

    async created(){
        const res = await this.callApi('get','api/chat/userList');
        if(res.status == 200){
            this.users = res.data;
            this.users = this.users.users.filter((user) => user.id !== this.currentUser.id);
        }
        const con = await this.callApi('get', 'api/chat/contactList');
        if(con.status == 200){
            console.log("jajaja", con);
            this.contactList = con.data.contactUsers;
            console.log("jajaja", this.contactList);
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
    methods: {
        updatechatwith(userid) {
            this.$emit("updatechatwith", userid);
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
            const res = await this.callApi('post', 'api/contact', this.willAddToContactUser)
            if(res.status == 200){
                let addedContact = res.data.addedToContactUser[0];
                this.contactList.unshift(addedContact);
                console.log("gagaga",this.contactList);
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
        }
    },

}
</script>