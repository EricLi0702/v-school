<template>
    <div>
        <div v-if="isloadingContact" class="row justify-content-center pt-3 m-0" >
            <img src="/img/icon/loadingIcon.gif" style="width: 30px; height:30px;" alt="">
        </div>
        <div v-if="!isloadingContact" class="es-alphabet">
            <div>
                <label :title="key"  v-for="(value, key) in grouped" :key="key"><a :href="`#${key}`">{{key}}</a></label>
                <!-- <a :href="`#${key}`"></a> -->
            </div>
        </div>
        <div v-if="!isloadingContact" v-for="(value, key) in grouped" :key="key">
            <div class="category-title">
                <span :id="key">{{ key }}</span>
            </div>
            <div v-for="(contactName,i) in value" :key="i">
                <div v-for="(contact,j) in contacts" :key="j">
                    <div v-if="contact.name == contactName.name">
                        <div class="es-item" @click="selUser(contact)">
                            <div class="es-item-left">
                                 <Checkbox v-model="contact.isSelected"></Checkbox>
                                <!-- <Avatar src="contact.userAvatar" v-if="contact.userAvatar" />
                                <Avatar icon="ios-person" v-else/> -->
                                <avatar :size="40" :src="contact.userAvatar" :username="contact.name" class="pr-0"></avatar>
                                <div class="es-item-info">
                                    <div class="title">{{contact.name}}</div>
                                    <div class="main">{{contact.phoneNumber}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="submit">提交</Button>
        </div>
    </div>
</template>

<script>
import Avatar from 'vue-avatar'
import lodash from 'lodash';
export default {
    components:{
        Avatar,
    },
    data(){
        return{
            contacts:[],
            contactsName:[],
            selUsers:[],
            isLoading:false,
            isloadingContact:false,
        }
    },
    async created(){
        let payload = {};
        if ('className' in this.currentPath.params){
            payload = {
                schoolId : parseInt(this.currentPath.params.schoolName),
                classId : parseInt(this.currentPath.params.className),
            }
        }
        if(!('className' in this.currentPath.params)){
            payload = {
                schoolId : parseInt(this.currentPath.params.schoolName),
                classId : null,
            }
        }
        this.isloadingContact = true;
        await axios.get('/api/contact',{params:payload})
        .then(res=>{
            this.contacts = res.data.user;
            this.contactsName = res.data.userName;
        })
        .catch(err=>{
            console.log(err.response);
        })
        this.isloadingContact = false;
    },
    computed:{
        grouped(){
            return lodash.groupBy(this.contactsName,(item)=>{
                return item.name.charAt(0)
            })
        },
        currentPath(){
            return this.$route
        }
    },
    methods:{
        selUser(userInfo){
            if(userInfo.isSelected == undefined){
                this.$set(userInfo,'isSelected',true)
            }else{
                userInfo.isSelected = !userInfo.isSelected
            }
            if(userInfo.isSelected == true){
                this.selUsers.push(`${userInfo.name}`)
            }else{
                this.selUsers.pop(`${userInfo.name}`)
            }
        },
        submit(){
            if(this.selUsers.length == 0){
                return this.error('还未选择任何用户');
            }
            this.$emit('selectedUser',this.selUsers)
            // this.$router.push({path:this.$route.path,query:{questionType:'短信'}})
            if(this.currentPath.query.questionType == '评价'){
                this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType,selType:'student'}})
            }else{
                this.$router.push({path:`${this.$route.path}?questionType=${this.currentPath.query.questionType}`})
            }
        }
    }
}
</script>