<template>
    <div class="w-100 es-view mt-2">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        <div class="container content-container">
            <div class="_overflow_table_div">
                <table class="table">
                    <tr>
                        <th>号码</th>
                        <th>名称</th>
                        <th>电话号码</th>
                        <th>角色</th>
                        <th>创建于</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(user,i) in users" :key="i" v-if="users.length">
                        <td>{{user.id}}</td>
                        <td class="_table_name">{{user.name}}</td>
                        <td>{{user.phoneNumber}}</td>
                        <td>{{roles[user.roleId-1].roleName}}</td>
                        <td>{{TimeView(user.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(user,i)">编辑</Button>
                            <Button type="primary" size="small" @click="allow(user)">{{user.isActived == 0?'激活':'禁用'}}</Button>
                            <Button type="error" size="small" @click="showDeletingModal(user,i)" :loading="user.isDeleting">删除</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add admin"
                draggable 
                scrollable
            >
                <Input type="text" v-model="modalData.name" placeholder="全名" class="mb-2" />
                <Input type="text" v-model="modalData.phoneNumber" placeholder="电话号码" class="mb-2" />
                <Input type="password" v-model="modalData.password" placeholder="密码" class="mb-2" />
                <Select v-model="modalData.roleId">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                    
                </Select>
                <div slot="footer">
                    <Button type="default" @click="addModal=false">取消</Button>
                    <Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="编辑个人资料"
                draggable 
                scrollable
            >
                <Input type="text" v-model="editData.name" placeholder="全名" class="mb-2" />
                <Input type="text" v-model="editData.phoneNumber" placeholder="电话号码" class="mb-2" />
                <Input type="password" v-model="editData.password" placeholder="密码" class="mb-2" />
                <Select v-model="editData.roleId">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                </Select>
                <div slot="footer">
                    <Button type="default" @click="editModal=false">取消</Button>
                    <Button type="primary" @click="editUser" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <Modal v-model="showDeleteModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>删除确认</span>
                </p>
                <div style="text-align:center">
                    <p>你会删除吗？</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="isDeleting" @click="deleteTag">删除</Button>
                </div>
            </Modal>
            <!-- <Page :total="100" /> -->
        </div>
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem'
export default {
    components:{
        menuItem
    },
    data () {
        return {
            modalData:{
                name:'',
                phoneNumber:'',
                password:'',
                roleId:null,
            },
            addModal:false,
            isAdding:false,
            users:[],
            editModal:false,
            editData:{
                name:'',
                phoneNumber:'',
                password:'',
                roleId:null,
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            roles:[]
        }
    },
    async created(){
        const [res,resRole] = await Promise.all([
            this.callApi('get','/api/users'),
            this.callApi('get','/api/role')
        ])
        if(res.status == 200){
            this.users = res.data;
        }else{
            this.swr();
        }
        if(resRole.status == 200){
            this.roles = resRole.data;
        }else{
            this.swr();
        }
    },
    methods:{
        addModalemit(value){
            this.addModal = value;
        },
        async addAdmin(){
            this.isAdding = true;
            const res = await this.callApi('post', '/api/addUsers',this.modalData)
            if(res.status == 201){
                this.users.unshift(res.data);
                this.success('管理员用户已成功添加！');
                this.addModal = false;
                this.modalData.name = '';
                this.modalData.phoneNumber = '';
                this.modalData.password = '';
                this.modalData.roleId = null;
                this.isAdding = false

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

       async editUser(){
           this.isAdding = true;
            
            const res = await this.callApi('put', '/api/users',this.editData)
           if(res.status === 200){
               this.users[this.index].name = this.editData.name;
               this.users[this.index].phoneNumber = this.editData.phoneNumber;
               this.users[this.index].roleId = this.editData.roleId;
               this.success('管理员用户已成功添加！');
               this.editModal = false;
               
           }else{
               if(res.status == 422){
                   for(let i in res.data.errors){
                        this.error(res.data.errors[i][0]);
                    }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },


        showEditModal(user,index){
            let obj = {
                id:user.id,
                name:user.name,
                phoneNumber:user.phoneNumber,
                roleId:user.roleId,
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','/api/users',this.deleteItem);
            if(res.status == 200){
                this.users.splice(this.deletingIndex,1);
                this.success('用户已成功删除！');
            }else{
                this.swr();
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },

        showDeletingModal(tag,i){
            // if(!confirm('Are you sure you want to delete this tag?')) return
            this.deleteItem = tag;
            this.deletingIndex = i;
            this.showDeleteModal = true;
        },
        async allow(user){
            if(user.isActived == 0){
                user.isActived = 1
            }else if(user.isActived == 1){
                user.isActived = 0
            }
            const res = await this.callApi('put','/api/profile',{isActived:user.isActived,userId:user.id})
            if(res.status == 200){
                this.success('操作成功')
            }
        }
    }
}
</script>