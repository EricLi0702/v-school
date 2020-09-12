<template>
    <div class="es-space es-branch">
        <div v-if="this.$store.state.classView" class="es-main">
            <Tabs name="classView" value="name1">
                <TabPane label="最新" name="name1">
                    <notConnect></notConnect>
                </TabPane>
                <TabPane label="应用" name="name2">
                    <div class="app-title app-title-margin">
                        <span class="name">最近使用</span>
                    </div>
                    <div class="es-item">
                        <div class="logo">
                            <img src="" class="avatar" alt="">
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
                    </div>
                </TabPane>
                <TabPane label="关于" name="name3">关于</TabPane>
                <TabPane label="提示" name="name4">提示</TabPane>
            </Tabs>
        </div>
        <div class="es-contacts">
            <div class="es-main-header"></div>
            <div class="es-info-container">
                <div class="es-scroller" style="width:100%;height:695px;overflow:hidden;outline:none;">
                    <div class="scroll-content">
                        <div class="scroll-container">
                            <div class="es-item es-item-list" v-for="contact in contactJson.menu" :key="contact.item">
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
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
import notConnect from '../pages/notConnect'
import contactJson from '../../json/chungHua/contact.json'
export default {
    data(){
        return{
            classView:false,
            contactJson
        }
    },
    components:{
        notConnect
    },
    computed:{
        currentPath(){
            return this.$route
        },
        ...mapGetters['getClassView']
    },
    watch:{
        getClassView(value){
            console.log('@@@@@@',value)
        }
    },
    created(){
        // this.clasView = true
    }
}
</script>