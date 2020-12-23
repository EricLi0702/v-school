<template>
    <div>
        <div v-if="isloadingContact" class="row justify-content-center pt-3 m-0" >
            <img src="/img/icon/loadingIcon.gif" style="width: 30px; height:30px;" alt="">
        </div>
        <div v-if="!isloadingContact" class="es-alphabet">
            <div>
                <label :title="key"  v-for="(value, key) in grouped" :key="key"><a :href="`#${key}`">{{key}}</a></label>
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
                                <!-- <Avatar :src="contact.userAvatar" v-if="contact.userAvatar" />
                                <Avatar icon="ios-person" v-else/> -->
                                <avatar :size="40" :src="contact.userAvatar" :username="contact.name" class="pr-0"></avatar>
                                <div class="es-item-info">
                                    <div class="title">{{contact.name}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import lodash from 'lodash';
import Avatar from 'vue-avatar'
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
            isloadingContact : false,
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
            this.$emit('selectedUser',userInfo)
            if(this.currentPath.query.addQuestion  = "classPresident"){
                this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.query.applicationType,questionType:this.currentPath.query.questionType,addQuestion:'publishingRules'}})
            }else{
                // this.$router.push({path:`${this.$route.path}?questionType=${this.currentPath.query.questionType}`})
                this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.applicationType,questionType:this.currentPath.questionType}})
            }
        },
    }
}
</script>