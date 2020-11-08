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
                        </tr>
                        <tr v-for="(resource,k) in schools.data" :key="k">
                            <td>{{resource.resourceName}}</td>
                            <td> 
                                <i-switch true-color="#13ce66" v-model="resource.read" />
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
        console.log('!!!!!!!!!!!')
        console.log(this.assignRoleJson)
        this.resources = this.assignRoleJson
        const res = await this.callApi('get','api/role');
        if(res.status == 200){
            this.roles = res.data;
            if(res.data.length){
                this.data.roleId = res.data[0].id;
                if(res.data[0].permission){
                    console.log('@@@@@@@@@')
                    console.log(res.data)
                    this.resources = JSON.parse(res.data[0].permission)
                }
            }
            
        }
        else{
                this.resources = this.assignRoleJson
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
            // //console.log(this.resources);
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
        changeAdmin(){
            // debugger
            let index = this.roles.findIndex(role=>role.id == this.data.roleId);
            let permission = this.roles[index].permission;
            if(permission == null){
                this.resources = this.assignRoleJson
            }else{
                this.resources = JSON.parse(permission)
            }
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