<template>
    <div v-if="getActionView == false">
        <div class="es-space es-branch">
            <div class="es-main">
                <Tabs name="classView" value="name1">
                    <TabPane label="最新" name="name1">
                        <notConnect></notConnect>
                    </TabPane>
                    <TabPane label="应用" name="name2">
                        <!-- <div class="app-title app-title-margin">
                            <span class="name">最近使用</span>
                        </div>
                        <div class="es-item s">
                            <div class="logo">
                                <img src="/img/icon/分享.png" class="avatar" alt="">
                            </div>
                            <div class="main">
                                <div class="info">
                                    <div class="title">
                                        <span>班牌发布</span>
                                    </div>
                                </div>
                                <div class="detail">
                                    <div class="content">
                                        <span>发布、查看学校和班级动态和相关信息</span>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div v-for="(menuList,i) in classViewJson.application" :key='i'>
                            <div class="app-title app-title-margin">
                                <span class="name">{{menuList.name}}</span>
                            </div>
                            <div v-for="(subMenu,j) in menuList.subMenu" :key="j">
                                <div class="es-item s">
                                    <div class="logo">
                                        <img :src="subMenu.imgUrl" class="avatar" alt="">
                                    </div>
                                    <div class="main">
                                        <div class="info">
                                            <div class="title">
                                                <span>{{subMenu.title}}</span>
                                            </div>
                                        </div>
                                        <div class="detail">
                                            <div class="content">
                                                <span>{{subMenu.detail}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TabPane>
                    <TabPane label="关于" name="name3">
                        <div class="es-item p-3" v-for="(menuList,i) in classViewJson.on" :key="i">
                            <div class="es-item-left">
                                <span>{{menuList.name}}</span>
                            </div>
                            <div class="es-right-item">
                                
                            </div>
                        </div>
                    </TabPane>
                    <TabPane label="提示" name="name4">
                        <notConnect></notConnect>
                    </TabPane>
                </Tabs>
            </div>
            <div class="es-contacts">
                <div class="es-main-header"></div>
                <div class="es-info-container">
                    <div class="es-scroller" style="width:100%;height:695px;overflow:hidden;outline:none;">
                        <div class="scroll-content">
                            <div class="scroll-container">
                                <div v-for="contact in contactJson.menu" :key="contact.item" >
                                    <router-link :to="`${currentPath.path}?modalName=${currentPath.query.modalName}&className=${currentPath.query.className}&actionName=${contact.item}`">
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
                                    <div class="contact-user es-item es-item-list" v-for="contact in contactJson.contact" :key="contact.name">
                                        <div class="es-item-left">
                                            <img :src="contact.imgUrl" alt="" class="avatar">
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
                                <div class="es-item es-item-list" v-for="secondMenu in contactJson.secondMenu" :key="secondMenu.item">
                                    <div class="es-item-left">
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
    data(){
        return{
            classView:false,
            contactJson,
            classViewJson,
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
        ...mapGetters(['getActionView'])
    },
    watch:{
        getActionView(value){
            console.log('getactionview',value)
        },
    },
    created(){
        console.log(this.getActionView)
    },
    methods:{
        test(item){
            this.$store.commit('setActionView',true);
        }
    }
}
</script>