<template>
    <div class="w-100 es-view">
        <div class="bg-navbar-area" v-if="$isMobile()">
        </div>
        <div class="container content-container">
            <div class="_title0 pt-3 row">
                <p class="col-12 mb-2">角色管理</p> 
                <Select v-model="data.roleId" placeholder="" class="col-12 col-md-6 mb-2" @on-change="changeRole">
                    <Option v-for="role in roles" :key="role.id" :value="role.id" >{{role.roleName}}</Option>
                </Select>
                <Select v-model="userData.userId" placeholder="" class="col-12 col-md-6 mb-2" @on-change="changeUser">
                    <Option v-for="user in userList" :key="user.id" :value="user.id" >{{user.name}}</Option>
                </Select>
            </div>
        
            <div class="p-scroll-72">
                <div class="_overflow_table_div" v-for="(schools,i) in resources" :key="i">
                    <div class="es-item">
                        <div class="es-item-left">
                            {{schools.schoolName.resourceName}}
                        </div>
                        <div class="es-item-right">
                            <i-switch true-color="#13ce66" v-model="schools.schoolName.read" />
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>资源资源</th>
                            <th>读</th>
                            <th>创建</th>
                            <th>更新</th>
                            <th>删除</th>
                        </tr>
                        <tr v-for="(resource,k) in schools.data" :key="k">
                            <td>{{resource.resourceName}}</td>
                            <td> 
                                <i-switch true-color="#13ce66" v-model="resource.read" />
                            </td>
                            <td>
                                <i-switch true-color="#13ce66" v-model="resource.write" />
                            </td>
                            <td>
                                <i-switch true-color="#13ce66" v-model="resource.update" />
                            </td>
                            <td>
                                <i-switch true-color="#13ce66" v-model="resource.delete" />
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        
        </div>
        <div class="float-right">
            <Button class="mr-4 mt-2" type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles" v-if="isUpdatePermitted">保存</Button>
        </div>
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem';
export default {
    components:{
        menuItem,
    },
    data () {
        return {
            data:{
                roleName:'',
                roleId:null
            },
            userData:{
                userName:'',
                userId:null
            },
            switch1: true,
            token:'',
            isSending:false,
            roles:[],
            resources:[],
            addModal:false,
            assignRoleJson:[],
            userList:[],
        }
    },
    async created(){
        this.defaultRole()
        const res = await this.callApi('get','/api/role');
        if(res.status == 200){
            this.roles = res.data;
            if(res.data.length){
                this.data.roleId = res.data[0].id;
            }else{
            }    
        }
        this.changeRole()
    },
    methods:{
       addModalemit(value){
            this.addModal = value;
        },
        change (status) {
            this.$Message.info('开关状态：' + status);
        },
        async assignRoles(){
            // return
            this.isSending = true
            let data = JSON.stringify(this.resources);
            const res = await this.callApi('post', '/api/assignRoles',{'permission':data,roleId:this.data.roleId,userId:this.userData.userId});
            if(res.status == 200 || res.status == 201){
                this.success('角色已成功分配！');
                let index = this.roles.findIndex(role=>role.id == this.data.roleId);
                this.roles[index].permission = data;
            }else{
                this.swr();
            }
            this.isSending = false;
        },
        async changeRole(){

            await axios.get('/api/userByRole',{params:{id:this.data.roleId}})
                    .then(res=>{
                        if(res.data.length>0){
                            this.userData.userId = res.data[0].id
                            if(res.data[0].permission == null){
                                this.defaultRole()
                            }
                            else{
                                this.resources = JSON.parse(res.data[0].permission.permission)
                            }
                            this.userList = res.data
                        }else{
                            this.userList = []
                            this.userData.userId = null
                        }
                    })
                    .catch(err=>{
                        console.log(err)
                    })
            this.reallocation()
        },
        async changeUser(){
            let index = this.userList.findIndex(user=>user.id == this.userData.userId);
            let permission = this.userList[index].permission;
            if(permission == null){
                this.defaultRole()
            }else{
                this.resources = JSON.parse(permission.permission)
                this.reallocation()
            }
        },
        async defaultRole(){
            this.resources = []
            this.assignRoleJson = []
            let admin = {schoolName:{resourceName:"系统设置",read:false},data:[{resourceName:"名单",read:false,write:false,update:false,delete:false,name:"adminuser"},{resourceName:"角色",read:false,write:false,update:false,delete:false,name:"role"},{resourceName:"分配角色",read:false,write:false,update:false,delete:false,name:"assignRole"},{resourceName:"学校",read:false,write:false,update:false,delete:false,name:"School"},{resourceName:"年级",read:false,write:false,update:false,delete:false,name:"Grade"},{resourceName:"班级",read:false,write:false,update:false,delete:false,name:"Lesson"},{resourceName:"stream",read:false,write:false,update:false,delete:false,name:"stream"},{resourceName:"imei管理",read:false,write:false,update:false,delete:false,name:"imeiManage"}]}
            this.assignRoleJson.push(admin)
            const lesson = await this.callApi('get','/api/schoolLessonList')
            if(lesson.status == 200){
                for(let j=0;j<lesson.data.length;j++){
                    let element = {schoolName:{resourceName:"",read:false},data:[]}
                    element.schoolName.resourceName = lesson.data[j].schoolName
                    let data = {}
                    data.resourceName = "学校空间";
                    data.read = false
                    data.write = false
                    data.update = false
                    data.delete = false
                    data.imgUrl = lesson.data[j].imgUrl
                    data.name = 'schoolSpace/'+lesson.data[j].id
                    element.data.push(data)
                    for(let i=0;i<lesson.data[j].lessons.length;i++){
                        let data = {}
                        data.resourceName = lesson.data[j].lessons[i].lessonName
                        data.read = false
                        data.write = false
                        data.update = false
                        data.delete = false
                        data.imgUrl = lesson.data[j].lessons[i].imgUrl
                        data.name = 'class/'+lesson.data[j].id+'/'+lesson.data[j].lessons[i].id
                        element.data.push(data)
                    }
                    this.assignRoleJson.push(element)
                }
            }
            this.resources = this.assignRoleJson
            
        },
        reallocation(){
            let defaultRoleJson = JSON.parse(JSON.stringify(this.assignRoleJson))
            for(let i=0;i<this.resources.length;i++){
                for(let j=0;j<defaultRoleJson.length;j++){
                    if(defaultRoleJson[j].schoolName.resourceName == this.resources[i].schoolName.resourceName){
                        defaultRoleJson[j].schoolName = this.resources[i].schoolName
                        let din = 0
                        for(let k=0;k<this.resources[i].data.length;k++){
                            if(defaultRoleJson[j].data[din].resourceName == this.resources[i].data[k].resourceName){
                                defaultRoleJson[j].data[din] = this.resources[i].data[k]
                                din ++
                            }
                        }
                    }
                }
            }
            this.resources = defaultRoleJson
        }
    },
    computed : {
        
    },

    watch : {
        
    }
    
}
</script>

<style>
    
</style>