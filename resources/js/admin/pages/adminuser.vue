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
                <ListItem v-for="item in data" :key="item.title" v-if="tags.length">
                    <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _p20">
                    <ListItemMeta :avatar="item.avatar" :title="item.title" :description="item.description" />
                    {{ item.content }}
                    <img src="https://dev-file.iviewui.com/5wxHCQMUyrauMCGSVEYVxHR5JmvS7DpH/large" style="width: 280px">
                    </div>
                </ListItem>
            </List>
            <p class="h3 text-center">Last element</p> -->
            <div class="_overflow_table_div">
                <table class="_table">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone Number</th>
                        <th>Role</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(user,i) in users" :key="i" v-if="users.length">
                        <td>{{user.id}}</td>
                        <td class="_table_name">{{user.name}}</td>
                        <td>{{user.phoneNumber}}</td>
                        <td>{{roles[user.roleId-1].roleName}}</td>
                        <td>{{user.created_at}}</td>
                        <td>
                            <Button type="info" size="small" @click="showEditModal(user,i)">Edit</Button>
                            <Button type="error" size="small" @click="showDeletingModal(user,i)" :loading="user.isDeleting">Delete</Button>
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
                <Input type="text" v-model="modalData.name" placeholder="Full Name" class="mb-2" />
                <Input type="text" v-model="modalData.phoneNumber" placeholder="Phone Number" class="mb-2" />
                <Input type="password" v-model="modalData.password" placeholder="Password" class="mb-2" />
                <Select v-model="modalData.roleId">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                    
                </Select>
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addAdmin" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add Admin'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit profile"
                draggable 
                scrollable
            >
                <Input type="text" v-model="editData.name" placeholder="Full Name" class="mb-2" />
                <Input type="text" v-model="editData.phoneNumber" placeholder="Phone Number" class="mb-2" />
                <Input type="password" v-model="editData.password" placeholder="Password" class="mb-2" />
                <Select v-model="editData.roleId">
                    <Option v-for="(role,i) in roles" :key="i" :value="role.id" >{{role.roleName}}</Option>
                </Select>
                <div slot="footer">
                    <Button type="default" @click="editModal=false">Close</Button>
                    <Button type="primary" @click="editUser" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing': 'Edit Admin'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <Modal v-model="showDeleteModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>Delete confirmation</span>
                </p>
                <div style="text-align:center">
                    <p>Will you delete it?</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="isDeleting" @click="deleteTag">Delete</Button>
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
            this.callApi('get','api/users'),
            this.callApi('get','api/role')
        ])
        // const res = await this.callApi('get','api/users');
        // const resRole = await this.callApi('get','api/role');
        if(res.status == 200){
            // console.log(res)
            this.users = res.data;
            console.log(res);
        }else{
            this.swr();
        }
        if(resRole.status == 200){
            // console.log(res)
            this.roles = resRole.data;
            console.log(res);
        }else{
            this.swr();
        }
    },
    methods:{
        addModalemit(value){
            console.log('##########',value);
            this.addModal = value;
        },
        async addAdmin(){
            this.isAdding = true;
            // if(this.modalData.name.trim() == ''){
            //     this.isAdding = false;
            //     this.error('Name is reuired');
            // }
            // if(this.modalData.phoneNumber.trim() == ''){
            //     this.isAdding = false;
            //     this.error('Phone Number is reuired');
            // }
            // if(this.modalData.password.trim() == ''){
            //     this.isAdding = false;
            //     this.error('Password is reuired');
            // }
            // if(this.modalData.userType.trim() == ''){
            //     this.isAdding = false;
            //     this.error('User type is reuired');
            // }
            const res = await this.callApi('post', 'api/users',this.modalData)
            if(res.status === 201){
                this.users.unshift(res.data.user);
                this.success('Admin user has been added successfully!');
                this.addModal = false;
                this.modalData.name = '';
                this.modalData.phoneNumber = '';
                this.modalData.password = '';
                this.modalData.roleId = null;

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
            // if(this.editData.name.trim() == ''){
            //     this.isAdding = false;
            //     this.error('Name is reuired');
            // }
            // if(this.editData.phoneNumber.trim() == ''){
            //     this.isAdding = false;
            //     this.error('Phone Number is reuired');
            // }
            // if(this.editData.password.trim() == ''){
            //     this.isAdding = false;
            //     this.error('Password is reuired');
            // }
            // if(this.editData.userType.trim() == ''){
            //     this.isAdding = false;
            //     this.error('User type is reuired');
            // }
            const res = await this.callApi('put', 'api/users',this.editData)
           if(res.status === 200){
               this.users[this.index].name = this.editData.name;
               this.users[this.index].phoneNumber = this.editData.phoneNumber;
               this.users[this.index].roleId = this.editData.roleId;
               this.success('Admin user has been added successfully!');
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
            const res = await this.callApi('delete','api/users',this.deleteItem);
            if(res.status == 200){
                this.users.splice(this.deletingIndex,1);
                this.success('User has been deleted successfully!');
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
        }
    }
}
</script>