<template>
    <div class="w-100">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        <perfect-scrollbar>
            <div class="container content-container">
                <p class="_title0">
                    Role Management
                    <Select v-model="data.roleId" placeholder="Select admin type" style="width:300px" @on-change="changeAdmin">
                        <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                    </Select>
                </p>
            
                <div class="p-scroll">
                    <div class="_overflow_table_div" v-for="(schools,i) in resources" :key="i">
                        {{schools.schoolName}}
                        <table class="table">
                            <tr>
                                <th>Resource</th>
                                <th>Read</th>
                                <th>Write</th>
                                <th>Update</th>
                                <th>Delete</th>
                                
                            </tr>
                            <tr v-for="(resource,k) in schools.menuList" :key="k">
                                <td>{{resource.resourceName}}</td>
                                <td> 
                                    <i-switch true-color="#13ce66" v-model="resource.read" />
                                </td>
                                <td>
                                    <i-switch true-color="#13ce66" v-model="resource.write"/>
                                </td>
                                <td>
                                    <i-switch true-color="#13ce66" v-model="resource.update"/>
                                </td>
                                <td>
                                    <i-switch true-color="#13ce66" v-model="resource.delete"/>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                    <div class="mt-2">
                        <Button type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles">Assign</Button>
                    </div>
                </div>
            
            </div>
        </perfect-scrollbar>
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
        this.resources = this.assignRoleJson
        const res = await this.callApi('get','api/role');
        if(res.status == 200){
            // console.log(res)
            this.roles = res.data;
            if(res.data.length){
                console.log('###########')
                console.log(res.data[0])
                this.data.roleId = res.data[0].id;
                if(res.data[0].permission){
                    this.resources = JSON.parse(res.data[0].permission)
                }
            }
            
            console.log(res);
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
            // console.log(this.resources);
            this.isSending = true
            let data = JSON.stringify(this.resources);
            const res = await this.callApi('post', 'api/assignRoles',{'permission':data,id:this.data.roleId});
            if(res.status == 200){
                this.success('Role has been assigned successfully!');
                let index = this.roles.findIndex(role=>role.id == this.data.roleId);
                this.roles[index].permission = data;
            }else{
                this.swr();
            }
            this.isSending = false;
        },
        changeAdmin(){
            debugger
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