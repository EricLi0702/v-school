<template>
    <div class="h-100 container-fluid p-0 position-relative">
        <div class="m-0 row p-3 d-flex justify-content-between add-contact-searchbar">
            <div class="col-8 pl-0">
                <Input v-model="searchContact" class="mr-auto w-100" search placeholder="按名称搜索" />
            </div>
            <div class="col-4 p-0">
                <Button @click="addUserToContact" :loading="isAdding" type="info" class="m-0 w-100 ">加入联络人</Button>
            </div>
        </div>
        <div class="row m-0 mt-3">
            <div 
                class="col-12 userContactListAvatar d-flex align-items-center mb-2"
                v-for="user in filteredContacts"
                v-bind:key="user.id"
                :class="{'selected':willAddToContactUser.contactId == user.id}"
                @click="pushUserToList(user.id)"
            >
                <avatar :username="user.name"></avatar>
                <!-- <img :src="user.userAvatar" alt="" class="add-list-avatar"> -->
                <p class="ml-3">{{user.name}}</p>
                
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
        }
    },
    methods:{
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
    }
}
</script>