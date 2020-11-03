<template>
    <div class="h-100 container-fluid overflow-auto">
        <div class="row p-2 pt-4 d-flex justify-content-between">
            <Input v-model="searchContact" class="mr-auto w-50" search placeholder="按名称搜索" />
            <Button @click="addUserToContact" :loading="isAdding" type="info">加入联络人</Button>
        </div>
        <Divider dashed >用户清单</Divider>
        <div class="row p-2 justify-content-center">
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
            const res = await this.callApi('post', 'api/contact', this.willAddToContactUser)
            if(res.status == 200){
                let addedContact = res.data.addedToContactUser[0];
                this.contactList.unshift(addedContact);
                this.$emit("contactLists", addedContact);
                //redirect to chat address...
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