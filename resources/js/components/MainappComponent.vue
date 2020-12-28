<template>
    <div class="es-index">
        <div class="logged"  v-if="$store.state.user">
            <div v-if="!$isMobile()">
                <div class="es-header">
                    <div class="es-container row">
                        <div class="es-header-logo">
                            <img class="header-logo-img" src="/img/logo.png" @click="goHome()">
                        </div>
                        <div class="es-header-main">
                            <div v-if="isOverlay" class="overlay-search-pop"></div>
                            <Poptip placement="bottom" width="400" trigger="focus" @on-popper-show="isOverlay = true" @on-popper-hide="isOverlay = false">
                                <Input @on-enter="postSearchWord()" v-model="searchWord" suffix="ios-search" placeholder="搜索成员、应用" style="width: auto; z-index:1001" />
                                <div slot="content" class="text-dark" style="max-height: 450px; overflow:auto;">
                                    <div class="row m-0 p-0" >
                                        <div class="col-12 p-0 content-search-result-container mb-2">
                                            <div class="w-100 content-search-title bg-light-gray pl-2">
                                                <p class="text-secondary">应用</p>
                                            </div>
                                            <div v-if="noSearchResult_Content" class="noSearchResult px-2">
                                                <p>搜索结果为空</p>
                                            </div>
                                            <div v-else-if="isWaitingSearchResult" class="waitingSearchResult px-2 pt-2 d-flex justify-content-center align-content-center">
                                                <img src="/img/icon/loadingIcon.gif" alt="waiting..." style="width:32px; height:32px;">
                                            </div>
                                            <div v-else class="search-content-result w-100 row m-0 p-0">
                                                <div v-for="(content, index) in searchContentResultList" :key="index" @click="showSearchedContentItem(content)" class="searched-user-result-item-con col-3 p-2 d-flex justify-content-center align-content-center">
                                                    <div class="searched-user-result-item text-center">
                                                        <img :src="content.imgUrl" alt="content image..." style="width:45px; height:45px;">
                                                        <p>{{content.contentName}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 p-0 content-search-result-container mb-2">
                                            <div class="w-100 content-search-title bg-light-gray pl-2" >
                                                <p class="text-secondary">成员</p>
                                            </div>
                                            <div v-if="noSearchResult_User" class="noSearchResult px-2">
                                                <p>搜索结果为空</p>
                                            </div>
                                            <div v-else-if="isWaitingSearchResult" class="waitingSearchResult px-2 pt-2 d-flex justify-content-center align-content-center">
                                                <img src="/img/icon/loadingIcon.gif" alt="waiting..." style="width:32px; height:32px;">
                                            </div>
                                            <div v-else class="search-user-result w-100 row m-0 p-0">
                                                <div v-for="(user, index) in searchUserResultList" :key="index" @click="showSearchedUserItem(user)" class="searched-user-result-item-con col-3 p-2 d-flex justify-content-center align-content-center">
                                                    <div class="searched-user-result-item text-center">
                                                        <avatar :size="45" :src="user.userAvatar" :username="user.name" class=""></avatar>
                                                        <p class="search-result-user-item-name-p">{{user.name}}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </Poptip>
                            <Modal 
                                v-model="searchedSelectedUserInfoModal" 
                                width="360"
                                footer-hide
                                class-name= "show-medal-detail-modal"
                                @on-visible-change="initeSearchResultData"
                            >
                                <div v-if="searchedSelectedUserInfoModal" class="container-fluid m-0 p-0">
                                    <div class="row m-0 p-0">
                                        <div class="col-12 p-4 searchedUser-infoCon d-flex justify-content-center align-content-center position-relative overflow-hidden">
                                            <div class="banner-ribbon-user-role position-absolute">
                                                <h3>{{searchedSelectedUserInfo.role.roleName}}</h3>
                                            </div>
                                            <div class="text-center">
                                                <div class="searched-user-info-avatar text-center position-relative">
                                                    <avatar :size="200" :src="searchedSelectedUserInfo.userAvatar" :username="searchedSelectedUserInfo.name" class=""></avatar>
                                                    <Tag class="tag-user-info position-absolute" size="medium" v-if="searchedSelectedUserInfo.status == 1" color="primary">在办公室</Tag>
                                                    <Tag class="tag-user-info position-absolute" size="medium" v-else-if="searchedSelectedUserInfo.status == 2" color="success">上课中</Tag>
                                                    <Tag class="tag-user-info position-absolute" size="medium" v-else-if="searchedSelectedUserInfo.status == 3" color="warning">会议中</Tag>
                                                    <Tag class="tag-user-info position-absolute" size="medium" v-else-if="searchedSelectedUserInfo.status == 4" color="magenta">待客中</Tag>
                                                    <Tag class="tag-user-info position-absolute" size="medium" v-else-if="searchedSelectedUserInfo.status == 5" color="error">忙碌中</Tag>
                                                    <Tag class="tag-user-info position-absolute" size="medium" v-else-if="searchedSelectedUserInfo.status == 6" color="red">外出中</Tag>
                                                </div>
                                                <div class="searched-user-info-name p-3">
                                                    <p>{{searchedSelectedUserInfo.name}}</p>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </Modal>
                        </div>
                        <div class="es-header-profile d-flex">
                            <div class="clickable-profile-container ml-auto d-flex align-items-center" @click="showProfileModal">
                                <avatar :size="32" :src="$store.state.user.userAvatar" :username="$store.state.user.name" class="pr-0 mr-2"></avatar>
                                <span class="mr-2">{{$store.state.user.name}}</span>
                            </div>
                            <span><a href="/logout" style="color:#fff!important" onclick="return confirm('是否退出登录？')"> | 退出</a></span>
                        </div>
                    </div>
                </div>
                
                <div class="es-container">
                    <fab
                        :position="positionTopLeft"
                        :bg-color="bgColor"
                        :actions="fabActions"
                        @chat="chat"
                        @map="map"
                        @liveLecture="liveLecture"
                    ></fab>
                    
                    <div class="es-menu" v-if="$store.state.user">
                        <!-- <Menu>
                            <Submenu :name="i" v-for="(permissionList , i) in permission" :key="i" v-if="permissionList.schoolName.read">
                                <template slot="title">
                                    <Icon type="ios-analytics" />
                                    {{permissionList.schoolName.resourceName}}
                                </template>
                                <MenuItem  :name="`${i}-${j}`" :to="`/${menuItem.name}`" v-for="(menuItem,j) in permissionList.data" :key="j" v-if="permissionList.data.length && menuItem.read">
                                    {{ menuItem.resourceName }}
                                </MenuItem>
                            </Submenu>
                        </Menu> -->
                        <Menu>
                            <Submenu :name="`系统设置`" v-if="$store.state.user.roleId == 1">
                                <template slot="title">
                                    <Icon type="ios-anlytics"></Icon>系统设置
                                </template>
                                <MenuItem :name="`${menu.label}`" :to="`/${menu.router}`" v-for="(menu,j) in systemMenu" :key="j">
                                    {{menu.label}}
                                </MenuItem>
                            </Submenu>
                            <Submenu :name="`系统设置`" v-if="$store.state.user.roleId == 2">
                                <template slot="title">
                                    <Icon type="ios-anlytics"></Icon>系统设置
                                </template>
                                <MenuItem :name="`${menu.label}`" :to="`/${menu.router}`" v-for="(menu,j) in managerMenu" :key="j">
                                    {{menu.label}}
                                </MenuItem>
                            </Submenu>
                            <Submenu :name="i" v-for="(permissionList,i) in permission" :key="i">
                                <template slot="title">
                                    <Icon type="ios-anlytics"></Icon>{{permissionList.schoolName}}
                                </template>
                                <MenuItem :name="`/schoolSpace/${permissionList.schoolId}`" :to="`/schoolSpace/${permissionList.schoolId}`">
                                   学校空间
                                </MenuItem>
                                <MenuItem :name="j" :to="`/class/${permissionList.schoolId}/${menuItem.lessonId}`" v-for="(menuItem,j) in permissionList.lessons" :key="j">
                                    {{menuItem.lessonName}}
                                </MenuItem>
                            </Submenu>
                        </Menu>
                    </div>
                    
                    <div class="es-router">
                        <router-view :key="$route.path"></router-view>
                    </div>
                </div>
                <div class="es-footer">
                    copyright &#169; All reserved school
                </div>

                
            </div>
            <div class="container-fluid w-100 hv-100" v-else>
                <nav class="navbar navbar-dark navbar-scroll fix-top-app-menu bg-transparent">
                    <div class="container p-0">
                        <ul class="navbar-nav mr-auto">
                            <Icon @click="openMenu" size="25" type="md-menu" class="open-draw-icon color-white-top"/>
                            <Drawer placement="left" :closable="false" v-model="isOpenMenu" class-name="hamburger-menu-left">
                                <slot name="header">
                                <div class="w-100 text-center d-flex justify-content-start align-items-center pb-4">
                                    <avatar :size="55" :src="$store.state.user.userAvatar" :username="$store.state.user.name" class="pr-0 mr-2"></avatar>
                                    <p>{{$store.state.user.name}}</p>
                                </div>
                                </slot>
                                <div @click="openchatDrawer()" :class="{ active : active_el == 'chat' }" class="d-flex m-1 p-2 drawer-menu-item">
                                    <Icon size="25" class="mr-1" type="ios-chatbubbles-outline" />
                                    信息
                                </div>
                                <div @click="openMapDrawer()" :class="{ active : active_el == 'chat' }" class="d-flex m-1 p-2 drawer-menu-item">
                                    <Icon size="25" class="mr-1" type="ios-locate-outline" />
                                    电子围栏
                                </div>
                                <Menu>
                                    <Submenu :name="`系统设置`" v-if="$store.state.user.roleId == 1">
                                        <template slot="title">
                                            <Icon type="ios-anlytics"></Icon>系统设置
                                        </template>
                                        <MenuItem :name="`${menu.label}`" :to="`/${menu.router}`" v-for="(menu,j) in systemMenu" :key="j">
                                            {{menu.label}}
                                        </MenuItem>
                                    </Submenu>
                                    <Submenu :name="`系统设置`" v-if="$store.state.user.roleId == 2">
                                        <template slot="title">
                                            <Icon type="ios-anlytics"></Icon>系统设置
                                        </template>
                                        <MenuItem :name="`${menu.label}`" :to="`/${menu.router}`" v-for="(menu,j) in managerMenu" :key="j">
                                            {{menu.label}}
                                        </MenuItem>
                                    </Submenu>
                                    <Submenu :name="i" v-for="(permissionList,i) in permission" :key="i">
                                        <template slot="title">
                                            <Icon type="ios-anlytics"></Icon>{{permissionList.schoolName}}
                                        </template>
                                        <MenuItem :name="`/schoolSpace/${permissionList.schoolId}`" :to="`/schoolSpace/${permissionList.schoolId}`">
                                        学校空间
                                        </MenuItem>
                                        <MenuItem :name="j" :to="`/class/${permissionList.schoolId}/${menuItem.lessonId}`" v-for="(menuItem,j) in permissionList.lessons" :key="j">
                                            {{menuItem.lessonName}}
                                        </MenuItem>
                                    </Submenu>
                                </Menu>
                            </Drawer>
                            <!--chat drawer-->
                            <Drawer @on-close="closeChatDrawer" placement="right" width="100" :closable="false" v-model="isOpenChat" class-name="chat-drawer">
                                <slot name="header">
                                    <a class="chat-drawer-back-icon" @click="$router.go(-1)"><Icon size="25" type="ios-arrow-dropleft-circle" /></a>
                                    <Icon size="25" class="chat-drawer-close-icon" type="ios-close-circle" @click="closeChatDrawer"/>
                                </slot>
                                <chatmobile></chatmobile>
                            </Drawer>
                            <!--map drawer-->
                            <Drawer @on-close="closeMapDrawer" placement="right" width="100" :closable="false" v-model="isOpenMap" class-name="map-drawer">
                                <slot name="header">
                                    <Icon size="25" class="map-drawer-close-icon" type="ios-close-circle" @click="closeMapDrawer"/>
                                </slot>
                                <Baidumap></Baidumap>
                            </Drawer>
                        </ul>
                        <div class="navbar-brand mx-auto color-white-top open-draw-icon">
                            问卷标题
                        </div>
                        <ul class="navbar-nav ml-auto d-flex align-items-center">
                            <Icon @click="openMenuProfile" size="25" type="md-more" class="open-draw-icon color-white-top"/>
                            <Drawer @on-close="closeProflieDrawer" placement="right" :closable="false" v-model="isOpenMenuProfile" class="profile-drawer" width="100">
                                <slot name="header">
                                    <div class="p-3 text-center position-relative text-center">
                                        <a class="profile-drawer-back-icon" @click="$router.go(-1)"><Icon size="25" type="ios-arrow-dropleft-circle" /></a>
                                        <p>{{this.profileModalTitle}}</p>
                                        <Icon size="25" class="profile-drawer-close-icon" type="ios-close-circle" @click="closeProflieDrawer"/>
                                    </div> 
                                </slot>
                                <profile
                                    @updateProfileMenu="updateProfileMenu"
                                ></profile>
                                <!-- <div class="d-flex m-1 p-2 drawer-menu-item">
                                    profile
                                </div> -->
                            </Drawer>
                        </ul>
                    </div>
                </nav>
                <router-view></router-view>
                <!-- <div class="container-fluid app-footer-navigate-container bg-light-gray m-0 p-0">
                    <div class="row m-0 p-0">
                        <router-link to="/" class="col-4 m-0 p-0 d-flex justify-content-center align-items-center text-center bg-primary">
                            <Icon size="25" color="#FFFFFF" type="ios-home" class="p-2" />
                        </router-link>
                        <router-link to="/chat" class="col-4 m-0 p-0 d-flex justify-content-center align-items-center text-center bg-primary">
                            <Icon size="25" color="#FFFFFF" type="ios-chatboxes" class="p-2" />
                        </router-link>
                        <router-link to="/profile" class="col-4 m-0 p-0 d-flex justify-content-center align-items-center text-center bg-primary">
                            <Icon size="25" color="#FFFFFF" type="ios-people" class="p-2" />
                        </router-link>
                    </div>
                </div> -->
            </div>
        </div>
        <div class="login-page" v-else>
            <div v-if="!$isMobile()">
                <div class="header">
                    <div class="header-box">
                        <a href="#" class="header-logo"><img src="/img/logo_original.png" alt=""></a>
                        <div class="header-nav">
                            <a href="">iOS</a>
                            <a href="/downloads/apk/ESchool.apk">Android</a>
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
                                <div class="mb-3 mt-3 login-input">
                                    <Input type="text" v-model="register.name" placeholder="fullName">
                                        <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                    <Input type="text" v-model="register.phoneNumber" placeholder="Phone Number">
                                        <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                    <Input type="password" v-model="register.password" placeholder="******">
                                        <Icon type="ios-lock-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                </div>
                                <div class="login_footer mb-2">
                                    <Button type="primary" long @click="userRegister" :disabled="isAdding" :loading="isAdding">{{isAdding ? '登录...' : '登录'}}</Button>
                                </div>
                            </TabPane>
                            <TabPane label="账户登录" name="name2">
                                <div class="mb-3 mt-3 login-input">
                                    <Input @on-enter="login" type="text" v-model="data.phoneNumber" placeholder="Phone Number">
                                        <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                                    </Input>
                                    <Input @on-enter="login" type="password" v-model="data.password" placeholder="******">
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
                                <div class="thirdparty-title mb-2 text-center" style="">
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
                            <TabPane label="二维码扫描" name="name3">
                                <div class="qrcode h-100 d-flex align-items-center justify-content-center" >
                                    <qrcode value="http://47.111.233.60" :options="{ width: 300 }"></qrcode>
                                </div>
                            </TabPane>
                        </Tabs>
                    </div>
                </div>
            </div>
            <!-- <div class="main" v-else>
                <a href="/downloads/apk/ESchool.apk" style="font-size: 24px;color: #2d8cf0!important;">Android 下载 </a>
            </div> -->
            <div v-else class="container-fluid row m-0 p-0 hv-100 d-flex justify-content-center align-items-center app-login-main-content">
                <div>
                    <div class="app-login-logo-container container text-center">
                        <img src="/img/logo_original.png" alt="" class="w-75">
                    </div>
                    <div class="app-login-crd-input-aa ">
                        <div class="mb-3 mt-3 login-input">
                            <Input @on-enter="login" type="text" v-model="data.phoneNumber" placeholder="Phone Number">
                                <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                            </Input>
                            <Input @on-enter="login" type="password" v-model="data.password" placeholder="******">
                                <Icon type="ios-lock-outline" slot="prepend" style="font-size:30px"></Icon>
                            </Input>
                        </div>
                        <div class="mb-2">
                            <Checkbox v-model="policy"></Checkbox>
                            <span>已阅读并同意<a href="#">《用户服务协议》和《隐私》</a></span>
                        </div>
                        <div class="login_footer mb-2">
                            <Button type="success" long @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? '登录...' : '登录'}}</Button>
                        </div>
                        <div style="width:100%;height:25px" class="mb-3">
                            <span class="float-right">忘记密码?</span>
                        </div>
                        <div class="thirdparty-title mb-2 text-center" style="">
                            <span>—————</span>
                            <span>使用第三方账号登录</span>
                            <span>—————</span>
                        </div>
                        <div class="thirdparty-box">
                            <a class="box-one">
                            <img src="/img/login-wechat.png" alt="">
                            <span>企业微信</span></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <Modal
        v-model="setLiveLectureTitleModal"
        class-name="set-live-lecture-title-modal"
        :mask-closable="false"
        @on-ok="addStreamModal"
        @on-cancel="formatTitle"
        :styles="{top:'140px',left:'42px'}"
        >
        <div class="container text-center">
            <h4 class="mb-2">演讲题目</h4>
            <Input v-model="lectureTitle" placeholder="输入演讲题目。" style="width: 300px" />
        </div>
        </Modal>

        <Modal
        footer-hide	
        v-model="showVideoModal"
        :styles="{top:'140px',left:'-244px'}"
        class-name="live-streaming-modal"
        :mask-closable="false"
        @on-cancel="endLecture"
        >   
            <div class="recording-btn-area">
                <!-- <ButtonGroup><Icon type="ios-cloud-upload" />
                    <Button type="primary" icon="ios-play"></Button>
                    <Button type="primary" icon="ios-pause"></Button>
                    <Button type="primary" icon="md-square"></Button>
                    <Button type="primary" icon="ios-cloud-upload"></Button>
                </ButtonGroup> -->

                <Button class="btnclass" @click="recordStart" :disabled="isRecord">{{isRecord ? 'Recording...': 'Record'}}</Button>
                <Button class="btnclass" @click="recordPause" :disabled="isPause">暂停</Button>
                <Button class="btnclass" @click="recordStop" :disabled="isStop">停止</Button>
                <Button class="btnclass" @click="recordSave" :disabled="isSave">保存    </Button>
            </div>
            <div id="meeting"></div>
        </Modal>

        <Modal
            v-model="chatModal"
            title="消息"
            class-name="chat-modal"
            :styles="{top:'68px',left:'-245px'}"
            scrollable
            :mask-closable="false"
            footer-hide
        >
            <chatComponent></chatComponent>
        </Modal>
        <Modal
            v-model="viewLiveLectureModal"
            title="视频讲座"
            class-name="liveLecture-modal"
            :styles="{top:'68px',left:'-245px'}"
            scrollable
            :mask-closable="false"
            footer-hide
        >
            <lectureComponent></lectureComponent>
        </Modal>
        <modal
            v-show="profileModal"
            @close="closeProfileModalModal"
        >
            <template v-slot:title>
                <p v-if="profileModalTitle == null">我的信息</p>
                <p v-else>{{profileModalTitle}}</p>
            </template> 
          
            <template v-slot:body>
               <profile
               @updateProfileMenu="updateProfileMenu"
               ></profile>
            </template> 
        </modal>
        <Modal
            v-model="viewBaiduMap"
            title="baidu"
            class-name="baidumap-modal"
            scrollable
            :mask-closable="false"
            footer-hide
            @on-cancel="mapCancel()"
            :styles="{top:'50px',left:'-435px'}"
        >
            <Baidumap></Baidumap>
        </Modal>
    </div>

</template>
<script>
import profile from './profile/profile'
import modal from './modal'
import fab from 'vue-fab'
import chatComponent from './pages/chatComponent'
import lectureComponent from './pages/lectureComponent'
import Avatar from 'vue-avatar'
import Baidumap from './pages/baidumap.vue'
import chatmobile from './pages/chat/mobile/chatAddress'
export default {
    props:['user','member', 'role'],
    components:{
        fab,
        chatComponent,
        modal,
        profile,
        lectureComponent,
        Avatar,
        Baidumap,
        chatmobile,
        
    },
    mounted(){
        this.listen();
        
    },
    data(){
        return{
            alarm:'',
            active_el : '',
            isOpenMenu:false,
            isOpenChat:false,
            isOpenMap:false,
            isOpenMenuProfile:false,
            isLoggedin:false,
            logoutModal:false,
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
                    icon: 'location_on'
                },
                {
                    name: 'liveLecture',
                    icon: 'videocam'
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

            //liveLecture
            showVideoModal:false,
            domain : "118.31.71.41",
            videoOptions:{
                roomName: "testLecture",
                // width: 1010,
                height: 610,
                parentNode: undefined,
                // configOverwrite: {
                // },
                // interfaceConfigOverwrite: {
                //     filmStripOnly: true
                // },
            },
            setLiveLectureTitleModal:false,
            viewLiveLectureModal:false,
            lectureTitle : "",
            LiveMeeting:{},
            recordingData: [],
            recorder:'',
            recorderStream:'',
            isRecord: false,
            isPause: true,
            isStop: true,
            isSave: true,
            isAdding:false,
            register:{
                name:'',
                phoneNumber:'',
                password:'',
                roleId:5
            },
            schoolList:[],
            profileModalTitle : null,
            viewBaiduMap:false,
            baseUrl:window.Laravel.base_url,
            searchWord:'',
            isWaitingSearchResult : false,
            isOverlay : false,
            noSearchResult_Content:false,
            noSearchResult_User:false,
            searchUserResultList : [],
            searchContentResultList : [],
            searchedSelectedUserInfo : {},
            searchedSelectedContentInfo : {},
            searchedSelectedUserInfoModal : false,
            permission:[],
            systemMenu:[
                {
                    label:'名单',
                    router:'adminUser'
                },
                {
                    label:'角色',
                    router:'role'
                },
                // {
                //     label:'分配角色',
                //     router:'assignRole'
                // },
                {
                    label:'学校',
                    router:'School'
                },
                {
                    label:'年级',
                    router:'Grade'
                },
                {
                    label:'班级',
                    router:'Lesson'
                },
                {
                    label:'stream',
                    router:'stream'
                },
                {
                    label:'imei管理',
                    router:'imeiManage'
                },
            ],
            managerMenu:[
                {
                    label:'名单',
                    router:'adminUser'
                },
                {
                    label:'学生档案',
                    router:'student'
                },
                // {
                //     label:'分配角色',
                //     router:'assignRole'
                // },
                // {
                //     label:'学校',
                //     router:'School'
                // },
                {
                    label:'年级',
                    router:'Grade'
                },
                {
                    label:'班级',
                    router:'Lesson'
                },
                {
                    label:'stream',
                    router:'stream'
                },
                {
                    label:'imei管理',
                    router:'imeiManage'
                },
            ],
            roleMenu:[],
            adminInfo:[],
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },

    watch:{
        currentPath(value){
            if(JSON.stringify(value.query) === '{}'){
                this.isOpenMenu = false;
                this.isOpenChat = false;
                this.isOpenMap = false;
                this.isOpenMenuProfile = false;
            }
        },
    },

    async created(){
        if(JSON.stringify(this.currentPath.query) != '{}'){
            this.$router.push(this.$route.path)
        }
        this.role.permission = JSON.parse(this.role.permission)
        this.$set(this.user,'role',this.role)
        this.$store.commit('setUpdateUser',this.user);
        this.UserPermission()
        this.alarm = new Audio(`${this.baseUrl}/img/alarm.mp3`);
    },
    methods:{
        async UserPermission(){
             if(this.user.role.id == 1){
                await axios.get('/api/schoolTree')
                        .then(res=>{
                            this.setPermission(res.data)
                            
                        })
                        .catch(err=>{
                            console.log(err)
                        })
            }else if(this.user.role.id == 2){
                await axios.get('/api/managerSchool',{params:{
                    id:this.user.schoolId
                }}).then(res=>{
                    this.setPermission(res.data)
                }).catch(err=>{
                    console.log(err)
                })
            }
            else{
                let permission = []
                let school = {}
                school.schoolName = this.member.member.lesson.schools.schoolName
                school.schoolId = this.member.member.schoolId
                school.lessons = []
                let lesson = {}
                lesson.lessonName = this.member.member.lesson.lessonName
                lesson.lessonId = this.member.member.lesson.id
                school.lessons.push(lesson)
                permission.push(school)
                this.permission = permission
                this.$store.commit('setUserPermission',permission)
            }
            console.log('--------',this.permission)
        },
        setPermission(schoolData){
            this.adminInfo = schoolData
            let permission = []
            for(let i=0;i<this.adminInfo.length;i++){
                let school = {}
                school.schoolName = this.adminInfo[i].schoolName
                school.schoolId = this.adminInfo[i].id
                school.imgUrl = this.adminInfo[i].imgUrl
                school.lessons = []
                for(let j=0;j<this.adminInfo[i].grades.length;j++){
                    for(let k=0;k<this.adminInfo[i].grades[j].lessons.length;k++){
                        let lesson = {}
                        lesson.lessonName = this.adminInfo[i].grades[j].lessons[k].lessonName
                        lesson.lessonId = this.adminInfo[i].grades[j].lessons[k].id
                        school.lessons.push(lesson)
                    }
                }
                permission.push(school)
                
                this.$store.commit('setUserPermission',permission)
            }
            this.permission = permission
        },

        mapCancel(){
            this.viewBaiduMap = false
            this.$router.push({path:this.$route.path})
        },
        updateProfileMenu(val){
            this.profileModalTitle = val;
        },
        chat(){
            this.chatModal = true;
        },
        map(){
            // this.$router.push('/baidumap')
            this.$router.push({path:this.$route.path,query:{mapView:true}})
            this.viewBaiduMap = true
        },
        liveLecture(){
            this.viewLiveLectureModal = true;
        },
        clickMenu(item){
            this.$router.push({path:item})
        },
        async login(){
            if(this.data.phoneNumber.trim()=='') return this.error('电话号码为必填项。')
            if(this.data.password.trim()=='') return this.error('密码是必需的。')
            if(this.data.password.length < 6) return this.error('错误的登录详细信息。')
            this.isLogging = true
            const res = await this.callApi('post', '/api/login', this.data)
            if(res.status===200){
                if(res.data.msg == undefined){
                    this.info('您的帐户未被允许。')
                }else{
                    this.success('操作成功')
                    // this.UserPermission()
                    if(!this.$isMobile()){
                        // console.log('this.permission',this.permission)
                        // window.location = `/schoolSpace/${this.permission[0].schoolId}`
                        window.location = '/'
                    }else{
                        console.log('this.permission',this.permission)
                        window.location = '/'
                    }
                }
            }else{
                this.error('错误的登录详细信息')
                // if(res.status===401){
                //     this.info(res.data.msg)
                // }else if(res.status==422){
                //     for(let i in res.data.errors){
                //         this.error(res.data.errors[i][0])
                //     }
                // }
                // else{
                //     this.swr()
                // }
            }
            this.isLogging = false
        },
        showProfileModal(){
            this.profileModal = true;
            this.$router.push({path:this.$route.path,query:{profile:'我的信息'}})
        },
        closeProfileModalModal(){
            if(JSON.stringify(this.currentPath.query) != '{}'){
                this.$router.push(this.$route.path)
            }
            this.profileModal = false;
        },

        //liveLecture Methods
        async addStreamModal(){
            if(this.lectureTitle == ""){
                this.$Message.info('请写标题。');
                return;
            }
            this.videoOptions.roomName = this.lectureTitle;
            this.lectureTitle = "";
            this.showVideoModal = true;
            this.videoOptions.parentNode = document.querySelector('#meeting');
            this.LiveMeeting = new JitsiMeetExternalAPI( this.domain, this.videoOptions);
        },
        endLecture(){
            this.LiveMeeting.dispose();
        },
        formatTitle(){
            this.lectureTitle = "";
        },
        async recordStart(){
            let gumStream, gdmStream;
            // try {
            gumStream = await navigator.mediaDevices.getUserMedia({video: false, audio: true});
            gdmStream = await navigator.mediaDevices.getDisplayMedia({video: {displaySurface: "browser"}, audio: true});

            
            this.recorderStream = gumStream ? this.mixer(gumStream, gdmStream) : gdmStream;
            this.recorder = new MediaRecorder(this.recorderStream, {mimeType: 'video/webm'});

            this.recorder.ondataavailable = e => {

                if (e.data && e.data.size > 0) {
                    this.recordingData.push(e.data);
                }
            };

            this.recorder.onStop = () => {
                this.recorderStream.getTracks().forEach(track => track.stop());
                gumStream.getTracks().forEach(track => track.stop());
                gdmStream.getTracks().forEach(track => track.stop());
            };

            this.recorderStream.addEventListener('inactive', () => {
                stopCapture();
            });

            this.recorder.start();
            
            this.isRecord = true;
            this.isPause = false;
            this.isStop = false;
            this.isSave = true;

        },

        recordPause(){
            if(this.recorder.state ==='paused'){
                this.recorder.resume();
            }
            else if (this.recorder.state === 'recording'){
                this.recorder.pause();
            }
        },

        recordStop(){
            this.recorder.stop();
            
            this.isRecord = false;
            this.isPause = true;
            this.isStop = true;
            this.isSave = false;
        },
        goHome(){
            this.$router.push({path:'/'})
        },
        recordSave(){
            const blob = new Blob(this.recordingData, {type: 'video/webm'});
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = `${this.getFilename()}.webm`;
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }, 100);
        },


        /**
         * Returns a filename based ono the Jitsi room name in the URL and timestamp
         * */
        getFilename(){
            const now = new Date();
            const timestamp = now.toISOString();
            const room = new RegExp(/(^.+)\s\|/).exec(document.title);
            if(room && room[1]!=="")
                return `${room[1]}_${timestamp}`;
            else
                return `recording_${timestamp}`;
        },
        mixer(stream1, stream2){
            const ctx = new AudioContext();
            const dest = ctx.createMediaStreamDestination();

            if(stream1.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream1).connect(dest);

            if(stream2.getAudioTracks().length > 0)
                ctx.createMediaStreamSource(stream2).connect(dest);

            let tracks = dest.stream.getTracks();
            tracks = tracks.concat(stream1.getVideoTracks()).concat(stream2.getVideoTracks());

            return new MediaStream(tracks)
        },
        async userRegister(){
            this.isAdding = true;
            const res = await this.callApi('post', '/api/users',this.register)
            if(res.status === 200){
                this.success('已成功添加管理员用户，但不允许！');
                this.register.name = '';
                this.register.phoneNumber = '';
                this.register.password = '';
                this.register.roleId = null;
                 window.location = '/#/schoolSpace/index'

            }else{
                if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0]);
                    }
                }else{
                    this.swr()
                }
               
            }
            this.isAdding = false;
        },

        listen(){
            Echo.join('fence')
                .listenForWhisper('outFence', (e) => {
                    this.error(`IMEI号码为${e}的学生已离开电围栏。`);
                    this.alarm.play();
                })
        },
        openMenu(){
            this.isOpenMenu = !this.isOpenMenu;
        },
        openMenuProfile(){
            this.$router.push({path:this.currentPath.path,query:{profileDrawer:true}})
            this.isOpenMenuProfile = !this.isOpenMenuProfile;
        },

        openchatDrawer(){
            this.$router.push({path:this.currentPath.path,query:{chatDrawer:true}})
            this.isOpenMenu = false;
            this.isOpenChat = true;
        },
        openMapDrawer(){
            this.$router.push({path:this.currentPath.path,query:{mapDrawer:true}})
            this.isOpenMenu = false;
            this.isOpenMap = true;
        },
        navigateToLink(menu){
            this.isOpenMenu = false;
            this.$router.push({ path: `/${menu}` })
        },

        async postSearchWord(){
            this.noSearchResult_Content = false;
            this.noSearchResult_User = false;
            if( this.searchWord.trim() == '' ){
                this.searchUserResultList = [];
                this.searchContentResultList = [];
                return this.error('请输入您的搜索词');
            }
            this.isWaitingSearchResult = true;
            this.isOverlay = true;

            const res = await this.callApi('post', '/api/search', {searchWord : this.searchWord.trim()})
            if(res.status == 200){
                this.isWaitingSearchResult = false;
                
                if(res.data.userList.length == 0){
                    this.noSearchResult_User = true;
                }
                else{
                    this.searchUserResultList = res.data.userList;
                    this.searchUserResultList = this.searchUserResultList.filter((item) => {return item.id != this.$store.state.user.id});
                }

                if(res.data.contentList.length == 0){
                    this.noSearchResult_Content = true;
                }
                else{
                    this.searchContentResultList = res.data.contentList;
                }
            }
            else{
                this.isWaitingSearchResult = false;
                this.swr();
            }
        },

        showSearchedUserItem(user){
            this.searchedSelectedUserInfo = user;
            this.searchedSelectedUserInfoModal = true;
        },

        initeSearchResultData(val){
            if(val == false){
                this.searchedSelectedUserInfo = {};
                this.searchedSelectedUserInfoModal = false;
            }
        },

        showSearchedContentItem(content){
            this.searchedSelectedContentInfo = content;
            this.queryTitle = content.contentName
            this.$store.commit('setModalView',true);
            this.$router.push({path:this.currentPath.path,query:{applicationType:content.contentName}})
        },

        closeProflieDrawer(){
            if(JSON.stringify(this.currentPath.query) != '{}'){
                this.$router.push(this.$route.path)
            }
            this.isOpenMenuProfile = false;
        },
        closeChatDrawer(){
            if(JSON.stringify(this.currentPath.query) != '{}'){
                this.$router.push(this.$route.path)
            }
            this.isOpenChat = false;
        },
        closeMapDrawer(){
            if(JSON.stringify(this.currentPath.query) != '{}'){
                this.$router.push(this.$route.path)
            }
            this.isOpenMap = false;
        }


    }
}
</script>

<style>

    .logout-modal {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logout-modal .ivu-modal-content{
        height: unset!important;
        width: unset!important;
    }
    .logout-modal .ivu-modal {
        top: 0;
    }

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
        position: unset!important;
            padding: 10px!important;
    }
    #bottom-right-wrapper{
        right: 18vw!important;
        bottom:8vh!important;
    }
</style>