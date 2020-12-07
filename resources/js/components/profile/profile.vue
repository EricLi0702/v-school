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
                    <div class="space-of-profile"></div>
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
                    </MenuItem>
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
                        <div class="space-of-profile"></div>
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
                        </MenuItem>
                        <div class="space-of-profile"></div>
                        <MenuItem name="12" class="d-flex" :to="`${currentPath.path}?profile=帮助中心`">
                            <Icon type="md-help-circle" size="25" color="#00C697" />
                            <p class="pl-2"> 帮助中心 </p>
                        </MenuItem>
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
    }
}
</script>