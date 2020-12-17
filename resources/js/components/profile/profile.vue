<template>
    <div>
        <div v-if="!$isMobile()" class="profile-container container-fluid row p-0 m-0 h-100">
            <div class="cu-col-30 profile-list p-0 h-100 m-0">
                <Menu class="w-100" active-name="1">
                    <MenuItem name="1" class="d-flex" :to="`${currentPath.path}?profile=我的信息`">
                        <Icon type="md-person" size="25" color="#007BFF" />
                        <p class="pl-2">我的信息</p> 
                    </MenuItem>
                    <MenuItem name="2" class="d-flex" :to="`${currentPath.path}?profile=当前状态`">
                        <Icon type="md-laptop" size="25" color="#00C697" />
                        <p class="pl-2"> 当前状态 </p>
                    </MenuItem>
                    <!-- <div class="space-of-profile"></div>
                    <MenuItem name="3" class="d-flex" :to="`${currentPath.path}?profile=错题本`">
                        <Icon type="ios-bookmarks" size="25" color="#F47E68" />
                        <p class="pl-2"> 错题本 </p>
                    </MenuItem>
                    <MenuItem name="4" class="d-flex" :to="`${currentPath.path}?profile=录播`">
                        <Icon type="md-videocam" size="25" color="#00C697" />
                        <p class="pl-2"> 录播 </p>
                    </MenuItem>
                    <MenuItem name="5" class="d-flex" :to="`${currentPath.path}?profile=回收站`">
                        <Icon type="md-trash" size="25" color="#007BFF" />
                        <p class="pl-2"> 回收站 </p>
                    </MenuItem>
                    <MenuItem name="6" class="d-flex" :to="`${currentPath.path}?profile=运动`">
                        <Icon type="ios-basketball" size="25" color="#F47E68" />
                        <p class="pl-2"> 运动 </p>
                    </MenuItem>
                    <MenuItem name="7" class="d-flex" :to="`${currentPath.path}?profile=消费`">
                        <Icon type="ios-stats" size="25" color="#00C697" />
                        <p class="pl-2"> 消费 </p>
                    </MenuItem>
                    <MenuItem name="8" class="d-flex" :to="`${currentPath.path}?profile=图书`">
                        <Icon type="ios-paper" size="25" color="#007BFF" />
                        <p class="pl-2"> 图书 </p>
                    </MenuItem>
                    <MenuItem name="9" class="d-flex" :to="`${currentPath.path}?profile=订单`">
                        <Icon type="md-settings" size="25" color="#FE9F46" />
                        <p class="pl-2"> 订单 </p>
                    </MenuItem>
                    <div class="space-of-profile"></div>
                    <MenuItem name="10" class="d-flex" :to="`${currentPath.path}?profile=教师发展档案`">
                        <Icon type="ios-person" size="25" color="#F9BD68" />
                        <p class="pl-2"> 教师发展档案 </p>
                    </MenuItem>
                    <MenuItem name="11" class="d-flex" :to="`${currentPath.path}?profile=学生成长`">
                        <Icon type="md-calendar" size="25" color="#00C697" />
                        <p class="pl-2"> 学生成长 </p>
                    </MenuItem> -->
                    <div class="space-of-profile"></div>
                    <MenuItem name="12" class="d-flex" :to="`${currentPath.path}?profile=帮助中心`">
                        <Icon type="md-help-circle" size="25" color="#00C697" />
                        <p class="pl-2"> 帮助中心 </p>
                    </MenuItem>
                </Menu>
            </div>
            <div class="cu-col-70 profile-body-container p-0 h-100 bg-white m-0">
                <profileView></profileView>
            </div>
        </div>
        <div v-else class="profile-container container-fluid row p-0 m-0 h-100">
            <div class="col-12 profile-body-container p-0 h-100 bg-white m-0">
                <div v-if="currentPath.query.profile == undefined">
                    <div class="w-100 p-4 app-profile-top-area d-flex justify-content-center align-content-center">
                        <avatar :size="70" :username="$store.state.user.name" :src="$store.state.user.userAvatar" class="mx-auto"></avatar>
                    </div>
                    <Menu class="w-100" active-name="1">
                        <MenuItem name="1" class="d-flex" :to="`${currentPath.path}?profile=我的信息`">
                            <Icon type="md-person" size="25" color="#007BFF" />
                            <p class="pl-2">我的信息</p> 
                        </MenuItem>
                        <MenuItem name="2" class="d-flex" :to="`${currentPath.path}?profile=当前状态`">
                            <Icon type="md-laptop" size="25" color="#00C697" />
                            <p class="pl-2"> 当前状态 </p>
                        </MenuItem>
                        <!-- <div class="space-of-profile"></div>
                        <MenuItem name="3" class="d-flex" :to="`${currentPath.path}?profile=错题本`">
                            <Icon type="ios-bookmarks" size="25" color="#F47E68" />
                            <p class="pl-2"> 错题本 </p>
                        </MenuItem>
                        <MenuItem name="4" class="d-flex" :to="`${currentPath.path}?profile=录播`">
                            <Icon type="md-videocam" size="25" color="#00C697" />
                            <p class="pl-2"> 录播 </p>
                        </MenuItem>
                        <MenuItem name="5" class="d-flex" :to="`${currentPath.path}?profile=回收站`">
                            <Icon type="md-trash" size="25" color="#007BFF" />
                            <p class="pl-2"> 回收站 </p>
                        </MenuItem>
                        <MenuItem name="6" class="d-flex" :to="`${currentPath.path}?profile=运动`">
                            <Icon type="ios-basketball" size="25" color="#F47E68" />
                            <p class="pl-2"> 运动 </p>
                        </MenuItem>
                        <MenuItem name="7" class="d-flex" :to="`${currentPath.path}?profile=消费`">
                            <Icon type="ios-stats" size="25" color="#00C697" />
                            <p class="pl-2"> 消费 </p>
                        </MenuItem>
                        <MenuItem name="8" class="d-flex" :to="`${currentPath.path}?profile=图书`">
                            <Icon type="ios-paper" size="25" color="#007BFF" />
                            <p class="pl-2"> 图书 </p>
                        </MenuItem>
                        <MenuItem name="9" class="d-flex" :to="`${currentPath.path}?profile=订单`">
                            <Icon type="md-settings" size="25" color="#FE9F46" />
                            <p class="pl-2"> 订单 </p>
                        </MenuItem>
                        <div class="space-of-profile"></div>
                        <MenuItem name="10" class="d-flex" :to="`${currentPath.path}?profile=教师发展档案`">
                            <Icon type="ios-person" size="25" color="#F9BD68" />
                            <p class="pl-2"> 教师发展档案 </p>
                        </MenuItem>
                        <MenuItem name="11" class="d-flex" :to="`${currentPath.path}?profile=学生成长`">
                            <Icon type="md-calendar" size="25" color="#00C697" />
                            <p class="pl-2"> 学生成长 </p>
                        </MenuItem> -->
                        <div class="space-of-profile"></div>
                        <MenuItem name="12" class="d-flex" :to="`${currentPath.path}?profile=帮助中心`">
                            <Icon type="md-help-circle" size="25" color="#00C697" />
                            <p class="pl-2"> 帮助中心 </p>
                        </MenuItem>
                        <div class="d-flex py-3 px-4" @click="openLogoutModal()">
                            <Icon type="ios-power" size="25" color="#E40D0D" />
                            <p class="pl-3"> 退出 </p>
                        </div>
                        <!-- <Modal v-model="LogoutModal" class="logout-modal">
                            <p slot="header" style="color:#f60;text-align:center">
                                <Icon type="ios-information-circle"></Icon>
                                <span>退出</span>
                            </p>
                            <div style="text-align:center" class="p-4">
                                <p>您确定要注销吗？</p>
                            </div>
                            <div slot="footer" class="d-flex justify-content-between">
                                <Button type="error" @click="logout">是</Button>
                                <Button type="primary" @click="LogoutModal = false">不</Button>
                            </div>
                        </Modal> -->
                        <Modal 
                            v-model="LogoutModal" 
                            footer-hide
                            class-name= "logout-modal-app"
                            @on-visible-change="initeLogoutData"
                        >
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <div>
                                    <div class="logout-modal-title d-flex justify-content-center text-warning p-4">
                                        <Icon size="25" type="ios-information-circle"></Icon>
                                        <h5 class="ml-2 font-weight-bold">退出</h5>
                                    </div>
                                    <div class="px-3 pb-3 pt-0 text-center text-white">
                                        <p>您确定要注销吗？<br/> 如果您这样做，我们将不会通知您新的消息。</p>
                                    </div>
                                    <div class="">
                                        <!-- <Button long type="error" class="mb-3" @click="logout">是</Button>
                                        <Button long type="primary" class="mb-3" @click="LogoutModal = false">不</Button> -->
                                        <button class="ripple color-red-btn mb-3" @click="logout" data-ripple-color="#ffffff">是</button>
                                        <button class="ripple color-blue-btn mb-3"  @click="LogoutModal = false" data-ripple-color="#ffffff">不</button>
                                        <!-- <button class="ripple color-red-btn mb-3" data-ripple-color="#ffffff">是</button>
                                        <button class="ripple color-blue-btn mb-3"   data-ripple-color="#ffffff">不</button> -->
                                    </div>
                                </div>
                            </div>
                        </Modal>
                    </Menu>
                </div>
                <div v-else>
                    <profileView></profileView>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import profileView from './profileView'
import Avatar from 'vue-avatar'
export default {
    components:{
        profileView,
        Avatar,
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    data(){
        return{
            profileMenuName: '',
            LogoutModal: false,
        }
    },
    watch:{
        currentPath:{
            handler(val){
                this.profileMenuName = val.query.profile;
                this.$emit("updateProfileMenu", this.profileMenuName);
            },
            deep:true
        }
    },
    methods:{
        openLogoutModal () {
            this.LogoutModal = true;
        },
        logout(){
            window.location = '/logout'
        },
        initeLogoutData(val){
            if(val == false){

            }
        }
    }
}
</script>