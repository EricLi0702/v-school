<template>
    <div class="hv-100 container-fluid p-0 position-relative">
        <div class="h-100 add-contact-area">
            <div class="add-contact-search-area bg-light-gray">
                <Input v-model="searchContact" class="mx-auto w-100" search placeholder="按名称搜索" />
            </div>
            <div v-if="isCreateNewGroup" class="row m-0 h-100 p-0 bg-white overflow-auto py-3">
                <div 
                    class="col-12 userContactListAvatar d-flex align-items-center mb-2"
                    v-for="user in contactList"
                    v-bind:key="user.user.id"
                    :class="{'selected':newGroup.includes(user.user.id)}"
                    @click="pushUserToNewGroup(user.user.id)"
                >
                    <avatar :username="user.user.name"></avatar>
                    <!-- <img :src="user.userAvatar" alt="" class="add-list-avatar"> -->
                    <p class="ml-3">{{user.user.name}}</p>
                    <Icon size="25" type="md-checkmark-circle" class="ml-auto new-group-selected-icon-app"/>
                </div>
            </div>
            <div v-else class="row m-0 h-100 p-0 bg-white overflow-auto py-3">
                <div 
                    class="col-12 userContactListAvatar d-flex align-items-center mb-2"
                    v-for="(user,i) in filteredContacts"
                    :key="i"
                    :class="{'selected':willAddToContactUser.contactId == user.id}"
                    @click="pushUserToList(user.id)"
                >
                    <avatar :username="user.name"></avatar>
                    <!-- <img :src="user.userAvatar" alt="" class="add-list-avatar"> -->
                    <p class="ml-3">{{user.name}}</p>
                    <Icon size="25" type="md-checkmark-circle" class="ml-auto new-group-selected-icon-app"/>
                </div>
            </div>
        </div>
        <div class="px-3 pt-2 bg-light-gray row m-0 p-0">
            <!-- <Button @click="addUserToContact" :loading="isAdding" type="info" class="m-0 w-100 ">加入联络人</Button> -->
            <div class="col-12 d-flex justify-content-end align-items-center mb-3"  v-if="isCreateNewGroup">
                <Input :disabled="newGroup.length < 2" v-model="groupName" placeholder="请输入群组名称" style="width: 300px" class="mr-3" />
                <Button :disabled="newGroup.length < 2" @click="createNewGroup" :loading="isCreatingNewGroup" type="info"><Icon size="20" class="mr-2" type="md-add" />新组</Button>
            </div>
            <div v-else class="col-12 text-right">
                <Button :disabled="willAddToContactUser.contactId == null" @click="addUserToContact" :loading="isAdding" type="info" class="mb-3"><Icon size="20" class="mr-2" type="md-add" />加入联络人</Button>
            </div>
            <div class="col-12 row m-0 p-0">
                <div class="col-6 d-flex justify-content-center align-items-center p-3" @click="toggleIsCreatGroupFalse"><Icon size="20" class="mr-2" type="md-add" />加入联络人</div>
                <div class="col-6 d-flex justify-content-center align-items-center p-3" @click="toggleIsCreatGroupTrue"><Icon size="20" class="mr-2" type="ios-people" />新组</div>
            </div>
        </div>
        
    </div>
</template>

<script>
import Avatar from 'vue-avatar'
export default {
    props:{
        users:{
            type: Array,
            required: true,
        },
        contactList: {
            type: Array,
            required: true,
        },
    },
    mounted(){
        console.log(this.contactList);
        console.log(this.users);
    },
    components:{
        Avatar,
    },
    computed:{
        filteredContacts(){
            if(this.searchContact){
                console.log(this.searchContact);
                return this.users.filter((item) => {
                    return (item.name.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.subject.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.description.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        // ||(item.created_at.toUpperCase().startsWith(this.searchContact.toUpperCase()))
                        
                });
            } else{
                return this.users;
            }
        },
    },
    data(){
        return{
            isAdding:false,
            willAddToContactUser : {
                contactId:null,
            },
            searchContact:'',
            isCreateNewGroup : false,
            isCreatingNewGroup: false,
            newGroup:[],
            groupName: '',
        }
    },
    methods:{
        toggleIsCreatGroupFalse(){
            this.isCreateNewGroup = false;
            this.newGroup = [];
        },

        toggleIsCreatGroupTrue(){
            this.isCreateNewGroup = true;
            this.willAddToContactUser.contactId = null;
        },

        pushUserToList(id){
            if(this.willAddToContactUser.contactId == id){
                this.willAddToContactUser.contactId = null
            }
            else{
                this.willAddToContactUser.contactId = id;
            }
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

        async addUserToContact(){
            this.isAdding = true;
            if(this.willAddToContactUser == null){
                this.info("请选择将添加到联系人的用户");
            }
            const res = await this.callApi('post', '/api/contact', this.willAddToContactUser)
            if(res.status == 200){
                let addedContact = res.data.addedToContactUser[0];
                // this.contactList.unshift(addedContact);
                this.$emit("contactLists", addedContact);
                //redirect to chat address...
                this.$router.push({path:'/chat'})
            }
            else if(res.status == 409){
                this.info("您已经将该用户添加为联系人");
            }
            else{
                this.info("出问题了");
            }
            this.isAdding = false;
            this.willAddToContactUser.contactId = null;
            
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
                this.$emit("chatGroupListPush", res.data.newGroup);
                // this.chatGroupList.unshift(res.data.newGroup);
                this.isCreatingNewGroup = false;
                this.newGroup = [];
                this.groupName = '';
                this.willAddToContactUser.contactId = null;
                this.isCreateNewGroup = false;
                this.$router.push({path:'/chat'})
            })
            .catch(err=>{
                this.isCreatingNewGroup = false;
                console.log(err.response);
            })
        },
    }
}
</script>