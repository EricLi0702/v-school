<template>
    <div v-if="getActionView == false">
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
                                            <img :src="contact.userAvatar" alt="" class="avatar" v-if="contact.userAvatar">
                                            <Avatar icon="ios-person"  v-else/>
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
                                    <router-link :to="{path:currentPath.path,query:{gradeName:currentPath.query.gradeName,className:currentPath.query.className,delMember:true}}">
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
    <div v-else>
        <actionView></actionView>
    </div>
</template>

<script>
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
        }
    },
    components:{
        notConnect,
        actionView,
    },
    computed:{
        currentPath(){
            return this.$route
        },
        ...mapGetters(['getActionView','getClassView'])
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
            this.$store.commit('setActionView',true);
        },
        delMember(){

        }
    }
}
</script>