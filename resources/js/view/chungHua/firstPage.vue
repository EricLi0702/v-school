<template>
    <div>
        <div class="w-100 row m-0 p-4 grid" v-if="!$isMobile()">
            <figure v-for="school in getUserPermission" :key="school.id" class="col-12 col-md-4 eschool-item-select effect-apollo" @click="selSchool(school)">
                <img class="card-image p-3 w-100" :src="school.imgUrl">
                <figcaption>
                    <h2 class="m-3">{{school.schoolName}}</h2>
                </figcaption>
            </figure>
        </div>
        <div v-else class="h-100 w-100">
            <div class="fp-top-bg-container">
                <img src="/img/login.jpg" alt="" class="w-100">
            </div>
            <div class="app-main-school-menu w-100" v-if="$store.state.user">
                <Menu class="w-100">
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
                    <Submenu :name="i" v-for="(permissionList,i) in getUserPermission" :key="i">
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
            
        </div>
    </div>
</template>
<script>
import {mapGetters,mapActions} from 'vuex'

export default {
    data(){
        return {
            schoolList:[],
            permission:null,
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
                    label:'创建年级',
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
                    label:'创建年级',
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
        }
    },
    computed:{
        ...mapGetters([
            'getUserPermission'
        ])
    },

    async mounted(){
       
    },

    async created(){

    },
    methods:{
        selSchool(school){
            this.$router.push({path:`/schoolSpace/${school.schoolId}`})
        }
    }
}
</script>
