<template>
    <div class="w-100 es-view mt-2">
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
                        <th>号码</th>
                        <!-- <th>角色名称</th> -->
                        <th>允许</th>
                        <th>创建于</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(role,i) in roleLists" :key="i" v-if="roleLists.length">
                        <td>{{role.id}}</td>
                        <td>{{role.roleName}}</td>
                        <!-- <td>{{role.permission}}</td> -->
                        <td>{{TimeView(role.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(role,i)">编辑</Button>
                            <Button type="error" size="small" @click="showDeletingModal(role,i)" :loading="role.isDeleting">删除</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="新增角色"
            >
                <Input v-model="addData.roleName" class="mb-2" placeholder="输入一些东西..."/>
                
                <div slot="footer">
                    <Button type="default" @click="addModal=false">取消</Button>
                    <Button type="primary" @click="addRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="新增角色"
            >
                <Input v-model="editData.roleName" class="mb-2" placeholder="输入一些东西..." />
                

                <div slot="footer">
                    <Button type="default" @click="closeEditModal">取消</Button>
                    <Button type="primary" @click="editRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
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
               return this.error('角色名称为必填项');
           }
           
            const res = await this.callApi('post', 'api/role',this.addData)
           if(res.status === 201){
               this.roleLists.unshift(res.data);
               this.success('类别已成功添加！');
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
               return this.error('类别名称为必填项');
               
           }
           
            const res = await this.callApi('put', 'api/role',this.editData)
           if(res.status === 200){
               this.roleLists[this.index].roleName = this.editData.roleName;
               this.success('角色已成功添加！');
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