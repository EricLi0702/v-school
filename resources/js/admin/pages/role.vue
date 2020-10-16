<template>
    <div class="w-100 es-view">
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
            <div class="_overflow_table_div">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>Role Name</th>
                        <th>Permission</th>
                        <th>Crated at</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(role,i) in roleLists" :key="i" v-if="roleLists.length">
                        <td>{{role.id}}</td>
                        <td>{{role.roleName}}</td>
                        <td>{{role.permission}}</td>
                        <td>{{role.created_at}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(role,i)">Edit</Button>
                            <Button type="error" size="small" @click="showDeletingModal(role,i)" :loading="role.isDeleting">Delete</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add Role"
            >
                <Input v-model="addData.roleName" class="mb-2" placeholder="Enter something..."/>
                
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add Role'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit Role"
            >
                <Input v-model="editData.roleName" class="mb-2" placeholder="Enter something..." />
                

                <div slot="footer">
                    <Button type="default" @click="closeEditModal">Close</Button>
                    <Button type="primary" @click="editRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing': 'Edit Role'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <deleteModal></deleteModal>
            
        </div>
    </div>
</template>
<script>
import menuItem from '../../components/pages/basic/menuItem';
import deleteModal from '../components/deleteModal';
import {mapGetters,mapActions} from 'vuex';
export default {
    components:{
        menuItem,
        deleteModal
    },
    data () {
        return {
            addData:{
                roleName:'',
            },
            addModal:false,
            isAdding:false,
            roleLists:[],
            editModal:false,
            editData:{
                roleName:'',
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            token:'',
            isIconImageNew:false,
            isEditingItem:false,
            websiteSettings:[]
        }
    },
    async created(){
        this.token = window.Laravel.csrfToken
        const res = await this.callApi('get','api/role');
        if(res.status == 200){
            // //console.log(res)
            this.roleLists = res.data;
            //console.log(res);
        }
    },
    methods:{
       addModalemit(value){
           this.addModal = value;
       },
       async addRole(){
           this.isAdding = true;
           if(this.addData.roleName.trim()==''){
               this.isAdding = false;
               return this.error('Role Name is required');
           }
           
            const res = await this.callApi('post', 'api/role',this.addData)
           if(res.status === 201){
               this.roleLists.unshift(res.data);
               this.success('Category has been added successfully!');
               this.addModal = false;
               this.addData.roleName = '';
           }else{
               if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },

       async editRole(){
           this.isAdding = true;
           if(this.editData.roleName.trim()==''){
               this.isAdding = false;
               return this.error('Category Name is required');
               
           }
           
            const res = await this.callApi('put', 'api/role',this.editData)
           if(res.status === 200){
               this.roleLists[this.index].roleName = this.editData.roleName;
               this.success('Role has been added successfully!');
               this.editModal = false;
           }else{
               if(res.status == 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },


        showEditModal(role,index){
            // let obj = {
            //     categoryName:category.categoryName,
            //     iconImage:category.iconImage
            // }
            this.editData = role;
            this.editModal = true;
            this.index = index;
            this.isEditingItem = true;
        },

        showDeletingModal(category,i){
            
            const deleteModalObj = {
                showDeleteModal:true,
                deleteUrl:'api/role',
                data:category,
                deletingIndex:i,
                isDeleted:false,
            }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
        },
        
        closeEditModal(){
            this.isEditingItem = false;
            this.editModal = false
        }

    },
    computed : {
        ...mapGetters(['getDeleteModalObj'])
    },

    watch : {
        getDeleteModalObj(Obj){
            if(Obj.isDeleted){
                this.roleLists.splice(Obj.deletingIndex,1)
            }
        }
    }
    
}
</script>

<style>
    
</style>