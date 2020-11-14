<template>
    <div class="w-100 es-view mt-2">
        <div class="container content-container">
            <p class="_title0">
                角色管理
                <Select v-model="data.roleId" placeholder="Select admin type" style="width:300px" @on-change="changeAdmin">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                </Select>
            </p>
        
            <div class="p-scroll">
                <div class="_overflow_table_div" v-for="(schools,i) in resources" :key="i">
                    {{schools.schoolName}}
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
            <Button type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles">分配</Button>
        </div>
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem';
import assignRoleJson from '../../json/assignRole.json';
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
            switch1: true,
            token:'',
            isSending:false,
            roles:[],
            resources:[],
            addModal:false,
            assignRoleJson
        }
    },
    async created(){
        const res = await this.callApi('get','api/role');
        if(res.status == 200){
            this.roles = res.data;
            if(res.data.length){
                this.data.roleId = res.data[0].id;
                if(res.data[0].permission){
                    this.resources = JSON.parse(res.data[0].permission)
                }
            }
            
        }
        else{
                // this.resources = this.assignRoleJson
                this.defaultRole()
        }
    },
    methods:{
       addModalemit(value){
            this.addModal = value;
        },
        change (status) {
            this.$Message.info('开关状态：' + status);
        },
        async assignRoles(){
            console.log(this.resources);
            // return
            this.isSending = true
            let data = JSON.stringify(this.resources);
            const res = await this.callApi('post', 'api/assignRoles',{'permission':data,id:this.data.roleId});
            if(res.status == 200){
                this.success('角色已成功分配！');
                let index = this.roles.findIndex(role=>role.id == this.data.roleId);
                this.roles[index].permission = data;
            }else{
                this.swr();
            }
            this.isSending = false;
        },
        async changeAdmin(){
            // debugger
            let index = this.roles.findIndex(role=>role.id == this.data.roleId);
            let permission = this.roles[index].permission;
            
            if(permission == null){
                // this.resources = this.assignRoleJson
                this.defaultRole()
            }else{
                this.resources = JSON.parse(permission)
            }
        },
        async defaultRole(){
            const lesson = await this.callApi('get','/api/schoolLessonList')
            this.resources = []
            if(lesson.status == 200){
                let admin = {schoolName:"Admin",data:[{resourceName:"使用者",read:true,write:false,update:false,delete:false,name:"adminuser"},{resourceName:"角色",read:true,write:false,update:false,delete:false,name:"role"},{resourceName:"分配角色",read:true,write:false,update:false,delete:false,name:"assignRole"},{resourceName:"学校",read:true,write:false,update:false,delete:false,name:"School"},{resourceName:"年级",read:true,write:false,update:false,delete:false,name:"Grade"},{resourceName:"课",read:true,write:false,update:false,delete:false,name:"Lesson"},{resourceName:"第一页",read:true,write:false,update:false,delete:false,name:"/"}]}
                this.resources.push(admin)
                for(let j=0;j<lesson.data.length;j++){
                    let element = {schoolName:'',data:[]}
                    element.schoolName = lesson.data[j].schoolName
                    let data = {}
                    data.resourceName = "学校空间";
                    data.read = true
                    data.write = false
                    data.update = false
                    data.delete = false
                    data.imgUrl = lesson.data[j].imgUrl
                    data.name = 'schoolSpace/'+lesson.data[j].schoolName
                    element.data.push(data)
                    for(let i=0;i<lesson.data[j].lessons.length;i++){
                        let data = {}
                        data.resourceName = lesson.data[j].lessons[i].lessonName
                        data.read = false
                        data.write = false
                        data.update = false
                        data.delete = false
                        data.imgUrl = lesson.data[j].lessons[i].imgUrl
                        data.name = 'class/'+lesson.data[j].lessons[i].lessonName
                        element.data.push(data)
                    }
                    this.resources.push(element)
                }
                
            }
            console.log('+++++',this.resources)
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