<template>
    <div class="es-index">
        <div class="logged"  v-if="$store.state.user">
            <div class="es-header">
                <div class="es-container row">
                    <div class="es-header-logo">
                        <img class="header-logo-img" src="/img/logo.png"/>
                    </div>
                    <div class="es-header-main">
                        <Input suffix="ios-search" placeholder="Enter text" style="width: auto" />
                    </div>
                    <div @click="showProfileModal" class="es-header-profile d-flex">
                        <div  class="clickable-profile-container ml-auto">
                            <img :src="$store.state.user.userAvatar" class="avatar" alt="" v-if="$store.state.user.userAvater != ''">
                            <Avatar icon="ios-person"  v-else/>
                            <span>{{user.name}}</span>
                        </div>
                        <span><a href="/logout" style="color:#fff!important"> | 退出</a></span>
                    </div>
                </div>
            </div>
            <div class="es-container container-shadow">
                <fab
                    :position="positionTopLeft"
                    :bg-color="bgColor"
                    :actions="fabActions"
                    @chat="chat"
                    @map="map"
                ></fab>
                <perfect-scrollbar>
                <div class="es-menu" v-if="$store.state.user">
                    <Menu active-name="1-1" :open-names="['1']">
                        <Submenu :name="i" v-for="(permissionList , i) in permission" :key="i">
                            <template slot="title">
                                <Icon type="ios-analytics" />
                                {{permissionList.schoolName}}
                            </template>
                            <router-link :to="`/${menuItem.name}/index`" v-for="(menuItem,j) in permissionList.menuList" :key="j" v-if="permissionList.menuList.length && menuItem.read">
                                <MenuItem  :name="`${i}-${j}`">
                                    {{ menuItem.resourceName }}
                                </MenuItem>
                            </router-link>
                            <!-- <router-link :to="menuItem.name" v-for="(menuItem,j) in permissionList.menuList" :key="j" v-if="permissionList.menuList.length && menuItem.read" :name="`${i}-${j}`">
                                <MenuItem>{{ menuItem.resourceName }}</MenuItem>
                            </router-link> -->
                        </Submenu>
                    </Menu>
                </div>
                </perfect-scrollbar>
                <div class="es-router">
                    <router-view/>
                </div>
                <!-- <fab
                    :position="positionBottomRight"
                    :bg-color="bgColor"
                    :actions="fabActions"
                    @chat="chat"
                    @map="map"
                ></fab> -->
            </div>
            <div class="es-footer">
                copyright &#169; All reserved school
            </div>
        </div>
        <div class="login-page" v-else>
            <div class="header">
                <div class="header-box">
                    <a href="#" class="header-logo"><img src="/img/logo_original.png" alt=""></a>
                    <div class="header-nav">
                        <a href="">iOS</a>
                        <a href="">Android</a>
                        <a href="">Windows</a>
                        <a href="">Mac</a>
                    </div>
                </div>
            </div>
            <div class="main">
                <div class="describe">
                    <p>成若天性，习惯如自然。</p>
                    <p>成若天性，习惯如自然。</p>
                </div>
                <div class="login">
                    <Tabs name="main" value="name2">
                        <TabPane label="扫码登录" name="name1">
                            标签一的内容
                        </TabPane>
                        <TabPane label="账户登录" name="name2">
                            <div class="mb-3 mt-3 login-input">
                                <Input type="text" v-model="data.phoneNumber" placeholder="Phone Number">
                                    <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                                </Input>
                                <Input type="password" v-model="data.password" placeholder="******">
                                    <Icon type="ios-lock-outline" slot="prepend" style="font-size:30px"></Icon>
                                </Input>
                            </div>
                            <div class="mb-2">
                                <Checkbox v-model="policy"></Checkbox>
                                <span>已阅读并同意<a href="#">《用户服务协议》和《隐私》</a></span>
                            </div>
                            <div class="login_footer mb-2">
                                <Button type="primary" long @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? '登录...' : '登录'}}</Button>
                            </div>
                            <div style="width:100%;height:25px" class="mb-3">
                                <span class="float-right">忘记密码?</span>
                            </div>
                            <div class="thirdparty-title mb-2" style="">
                                <span>—————</span>
                                <span>使用第三方账号登录</span>
                                <span>—————</span>
                            </div>
                            <div class="thirdparty-box">
                                <a class="box-one">
                                <img src="/img/login-wechat.png" alt="">
                                <span>企业微信</span></a>
                            </div>
                        </TabPane>
                    </Tabs>
                </div>
            </div>
        </div>
        <Modal
            v-model="chatModal"
            title="chat"
            class-name="chat-modal"
            :styles="{top:'68px',left:'-245px'}"
            scrollable
            :mask-closable="false"
            footer-hide
        >
            <chatComponent></chatComponent>
        </Modal>
        <modal
            v-show="profileModal"
            @close="closeProfileModalModal"
        >
            <template v-slot:title>
                Profile
            </template> 
          
            <template v-slot:body>
               <profile></profile>
            </template> 
        </modal>
    </div>

</template>
<script>
import profile from './profile/profile'
import modal from './modal'
import fab from 'vue-fab'
import chatComponent from './pages/chatComponent'
export default {
    props:['user','permission'],
    components:{
        fab,
        chatComponent,
        modal,
        profile,
    },
    data(){
        return{
            isLoggedin:false,
            // loginView:false,
            positionTopLeft:'top-left',
            positionBottomRight:'bottom-right',
            bgColor: '#2d8cf0',
            bottomRight:'bottom-right',
            topLeft:"top-left",
            fabActions: [
                {
                    name: 'chat',
                    icon: 'chat'
                },
                {
                    name: 'map',
                    icon: 'map'
                }
            ],
            data : {
                phoneNumber : '', 
                password: ''
            }, 
            isLogging: false,
            policy:true,
            chatModal:false, 

            //profile
            profileModal : false,
        }
    },
    created(){
        this.$store.commit('setUpdateUser',this.user);
        this.$store.commit('setUserPermission',this.permission);
    },
    methods:{
        chat(){
            console.log('chat');
            this.chatModal = true;
        },
        map(){
            console.log('map');
            this.$router.push('/baidumap/index')
        },
        async login(){
            if(this.data.phoneNumber.trim()=='') return this.error('PhoneNumber is required')
            if(this.data.password.trim()=='') return this.error('Password is required')
            if(this.data.password.length < 6) return this.error('Incorrect login details')
            this.isLogging = true
            const res = await this.callApi('post', 'api/login', this.data)
            if(res.status===200){
                //console.log(res)
                this.success(res.data.msg)
                window.location = '/#/schoolSpace/index'
            }else{
                if(res.status===401){
                    this.info(res.data.msg)
                }else if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }
                else{
                    this.swr()
                }
            }
            this.isLogging = false
        },
        showProfileModal(){
            this.profileModal = true;
        },
        closeProfileModalModal(){
            this.profileModal = false;
        },
    }
}
</script>

<style>
     .thirdparty-box{
        display: flex;
        justify-content: space-around;
    }
    .box-one{
        display: flex;
        align-items: center;
    }
    .thirdparty-box .box-one img{
        display: inline-block;
        width: 22px;
        height: 22px;
        margin-right: 4px;
    }
    .thirdparty-box .box-one span{
        height: 22px;
        color: #999;
        font-size: 14px;
    }
    #top-left-wrapper{
        left: 19vw!important;
        top:10vh!important;
    }
    #bottom-right-wrapper{
        right: 18vw!important;
        bottom:8vh!important;
    }
</style>