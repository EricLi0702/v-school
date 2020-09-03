<template>
    <div class="w-100">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        <div class="container content-container">
            <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
            <!-- <List item-layout="vertical">
                <ListItem v-for="item in data" :key="item.title" v-if="categoryLists.length">
                    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _p20">
                    <ListItemMeta :avatar="item.avatar" :title="item.title" :description="item.description" />
                    {{ item.content }}
                    <img src="https://dev-file.iviewui.com/5wxHCQMUyrauMCGSVEYVxHR5JmvS7DpH/large" style="width: 280px">
                    </div>
                </ListItem>
            </List>
            <p class="h3 text-center">Last element</p> -->
            <p class="_title0">
                Role Management
                 <Select v-model="data.roleId" placeholder="Select admin type" style="width:300px" @on-change="changeAdmin">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                </Select>
            </p>
            <div class="_overflow_table_div">
                <table class="_table">
                    <tr>
                        <th>Resource</th>
                        <th>Read</th>
                        <th>Write</th>
                        <th>Update</th>
                        <th>Delete</th>
                        
                    </tr>
                    <tr v-for="(resource,i) in resources" :key="i">
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
                    <div class="mt-2">
                        <Button type="primary" :loading="isSending" :disabled="isSending" @click="assignRoles">Assign</Button>
                    </div>
                </table>
            </div>
            
            
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
            switch1: true,
            token:'',
            isSending:false,
            roles:[],
            resources:[
                {resourceName:'Tags',read:false,write:false,update:false,delete:false,name:'tags'},
                {resourceName:'Category',read:false,write:false,update:false,delete:false,name:'category'},
                {resourceName:'Admin users',read:false,write:false,update:false,delete:false,name:'adminuser'},
                {resourceName:'Role',read:false,write:false,update:false,delete:false,name:'role'},
                {resourceName:'Assign Role',read:false,write:false,update:false,delete:false,name:'assignRole'},
                {resourceName:'Home',read:false,write:false,update:false,delete:false,name:'/'},
            ],

            defaultResourcesPermission:[
                {resourceName:'Tags',read:false,write:false,update:false,delete:false,name:'tags'},
                {resourceName:'Category',read:false,write:false,update:false,delete:false,name:'category'},
                {resourceName:'Admin users',read:false,write:false,update:false,delete:false,name:'adminuser'},
                {resourceName:'Role',read:false,write:false,update:false,delete:false,name:'role'},
                {resourceName:'Assign Role',read:false,write:false,update:false,delete:false,name:'assignRole'},
                {resourceName:'Home',read:false,write:false,update:false,delete:false,name:'/'},
            ],
            addModal:false,
        }
    },
    async created(){
        console.log(this.$route);
        this.token = window.Laravel.csrfToken
        const res = await this.callApi('get','api/role');
        if(res.status == 200){
            // console.log(res)
            this.roles = res.data;
            if(res.data.length){
                this.data.roleId = res.data[0].id;
                if(res.data[0].permission){
                    this.resources = JSON.parse(res.data[0].permission)
                }
            }
            console.log(res);
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
            // console.log(this.roles);
            let index = this.roles.findIndex(role=>role.id == this.data.roleId);
            console.log('index',index);
            let permission = this.roles[index].permission;
            console.log('permission',permission)
            if(permission == null){
                console.log('permission is null')
                this.resources = this.defaultResourcesPermission
                console.log(this.defaultResourcesPermission)
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