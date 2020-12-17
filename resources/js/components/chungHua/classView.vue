<template>
<div>
    <div v-if="currentPath.query.actionName == undefined">
        <div class="es-space es-branch ml-0">
            <div class="es-contacts">
                <div class="es-main-header"></div>
                <div class="es-info-container">
                    <div class="es-scroller" style="width:100%;height:695px;overflow:hidden;outline:none;">
                        <div class="scroll-content">
                            <div class="scroll-container">
                                <div v-for="contact in contactJson.menu" :key="contact.item" >
                                    <router-link :to="`${currentPath.path}?gradeName=${currentPath.query.gradeName}&className=${currentPath.query.className}&actionName=${contact.item}`">
                                        <div class="es-item es-item-list" @click="test(contact)">
                                            <div class="es-item-left">
                                                <img :src="contact.imgUrl" alt="" class="avatar">
                                                <div class="es-item-info">
                                                    <div class="title">{{contact.item}}</div>
                                                    <div class="main"></div>
                                                </div>
                                            </div>
                                            <div class="es-item-right">
                                                <i class="ivu-icon ivu-icon-ios-arrow-forward"></i>
                                            </div>    
                                        </div>
                                    </router-link>
                                </div>
                                <div>
                                    <div class="category-title">
                                        <span>师</span>
                                    </div>
                                    <div class="contact-user es-item es-item-list" v-for="contact in contacts" :key="contact.name">
                                        <div class="es-item-left">
                                            <avatar :size="40" :src="contact.userAvatar" :username="contact.name" class="pr-0"></avatar>
                                            <div class="es-item-info">
                                                <div class="title">
                                                    <span>{{contact.name}}</span>
                                                </div>
                                                <div class="main">
                                                    <span>{{contact.phoneNumber}}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="es-item-right">
                                            
                                        </div>
                                    </div>
                                </div>
                                <div v-for="secondMenu in contactJson.secondMenu" :key="secondMenu.item" >
                                    <router-link :to="{path:currentPath.path,query:{gradeName:currentPath.query.gradeName,className:currentPath.query.className,actionName:'移除成员'}}">
                                        <div class="es-item es-item-list" >
                                            <div class="es-item-left" @click="delMember">
                                                <img :src="secondMenu.imgUrl" alt="" class="avatar">
                                                <div class="es-item-info">
                                                    <div class="title">{{secondMenu.item}}</div>
                                                    <div class="main"></div>
                                                </div>
                                            </div>
                                            <div class="es-item-right">
                                                <i class="ivu-icon ivu-icon-ios-arrow-forward"></i>
                                            </div>
                                        </div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else-if="currentPath.query.actionName == '邀请成员'">
        <actionView></actionView>
    </div>
    <div v-else-if="currentPath.query.actionName == '移除成员'">
        <div class="contact-user es-item es-item-list" v-for="contact in contacts" :key="contact.phoneNumber" @click="selUser(contact)">
            <div class="es-item-left">
                <Checkbox v-model="contact.active"></Checkbox>
                <avatar :size="40" :src="contact.userAvatar" :username="contact.name" class="pr-0"></avatar>
                <div class="es-item-info">
                    <div class="title">
                        <span>{{contact.name}}</span>
                    </div>
                    <div class="main">
                        <span>{{contact.phoneNumber}}</span>
                    </div>
                </div>
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward"></Icon>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="delMember" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</div>
</template>

<script>
import Avatar from 'vue-avatar'
import {mapGetters,mapActions} from 'vuex'
import notConnect from '../pages/notConnect'
import contactJson from '../../json/chungHua/contact.json'
import classViewJson from '../../json/chungHua/classView.json'
import actionView from '../../components/chungHua/actionView'
export default {
    props:['classData'],
    data(){
        return{
            classView:false,
            contactJson,
            classViewJson,
            contacts:[],
            isLoading:false,
        }
    },
    components:{
        notConnect,
        actionView,
        Avatar,
    },
    computed:{
        currentPath(){
            return this.$route
        },
        ...mapGetters(['getActionView','getClassView'])
    },
    watch:{
        currentPath:{
            handler(val){
                if(val.query.actionName == '移除成员'){
                    for(let i=0;i<this.contacts.length;i++){
                        this.$set(this.contacts[i],'active',false)
                    }
                }
            },
            deep:true
        }
    },
    created(){
        axios.get('/api/lessonMember',{params:{
            id:this.currentPath.query.className
        }}).then(res=>{
            this.contacts = res.data
        }).catch(err=>{
            console.log(err)
        })
    },
    methods:{
        test(item){
            // this.$store.commit('setActionView',true);
        },
        async delMember(){
            let delusers = []
            for(let i=0;i<this.contacts.length;i++){
                if(this.contacts[i].active == true){
                    delusers.push(this.contacts[i].id)
                }
            }
            this.isLoading = true
            const res = await this.callApi('delete','/api/member',{id:delusers})
            this.isLoading = false
            if(res.status == 200){
                this.success('操作成功')
                let contacts = this.contacts
                this.contacts = []
                for(let i=0;i<contacts.length;i++){
                    if(contacts[i].active == false){
                        this.contacts.push(contacts[i])
                    }
                }

            }
        },
        selUser(contact){
            contact.active = ! contact.active
        }
    }
}
</script>