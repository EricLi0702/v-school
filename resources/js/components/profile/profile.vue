<template>
    <div class="profile-container container-fluid row p-0 m-0 h-100">
        <div class="cu-col-30 profile-list p-0 h-100 m-0">
            <Menu class="w-100" active-name="1">
                <router-link :to="`${currentPath.path}?profile=我的信息`">
                    <MenuItem name="1" class="d-flex">
                        <Icon type="md-person" size="25" color="#007BFF" />
                        <p class="pl-2">我的信息</p> 
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=当前状态`">
                    <MenuItem name="2" class="d-flex">
                        <Icon type="md-laptop" size="25" color="#00C697" />
                        <p class="pl-2"> 当前状态 </p>
                    </MenuItem>
                </router-link>
                <div class="space-of-profile"></div>
                <router-link :to="`${currentPath.path}?profile=错题本`">
                    <MenuItem name="3" class="d-flex">
                        <Icon type="ios-bookmarks" size="25" color="#F47E68" />
                        <p class="pl-2"> 错题本 </p>
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="4" class="d-flex">
                        <Icon type="md-videocam" size="25" color="#00C697" />
                        <p class="pl-2"> 录播 </p>
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="5" class="d-flex">
                        <Icon type="md-trash" size="25" color="#007BFF" />
                        <p class="pl-2"> 回收站 </p>
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="6" class="d-flex">
                        <Icon type="ios-basketball" size="25" color="#F47E68" />
                        <p class="pl-2"> 运动 </p>
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="7" class="d-flex">
                        <Icon type="ios-stats" size="25" color="#00C697" />
                        <p class="pl-2"> 消费 </p>
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="8" class="d-flex">
                        <Icon type="ios-paper" size="25" color="#007BFF" />
                        <p class="pl-2"> 图书 </p>
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="9" class="d-flex">
                        <Icon type="md-settings" size="25" color="#FE9F46" />
                        <p class="pl-2"> 订单 </p>
                    </MenuItem>
                </router-link>
                <div class="space-of-profile"></div>

                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="10" class="d-flex">
                        <Icon type="ios-person" size="25" color="#F9BD68" />
                        <p class="pl-2"> 教师发展档案 </p>
                    </MenuItem>
                </router-link>
                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="11" class="d-flex">
                        <Icon type="md-calendar" size="25" color="#00C697" />
                        <p class="pl-2"> 学生成长 </p>
                    </MenuItem>
                </router-link>
                <div class="space-of-profile"></div>

                <router-link :to="`${currentPath.path}?profile=录播`">
                    <MenuItem name="12" class="d-flex">
                        <Icon type="md-help-circle" size="25" color="#00C697" />
                        <p class="pl-2"> 帮助中心 </p>
                    </MenuItem>
                </router-link>
            </Menu>
        </div>
        <div class="cu-col-70 profile-body-container p-0 h-100 bg-white m-0">
            
            <profileView></profileView>
        </div>
    </div>
</template>

<script>
import profileView from './profileView'
export default {
    components:{
        profileView,
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    }
}
</script>