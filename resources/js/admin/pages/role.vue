<template>
    <div class="w-100 es-view">
        <div class="bg-navbar-area" v-if="$isMobile()">
        </div>
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <Button type="success" class="addbtn m-2" @click="showModal" v-if="isWritePermitted"><Icon type="md-add" /> 添加</Button>
        </div>
        <div class="container content-container">
            <div class="_overflow_table_div">
                <table class="table">
                    <tr>
                        <th>号码</th>
                        <th>允许</th>
                        <th>创建于</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(role,i) in roleLists" :key="i" v-if="roleLists.length">
                        <td>{{role.id}}</td>
                        <td>{{role.roleName}}</td>
                        <td>{{TimeView(role.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(role,i)" v-if="isUpdatePermitted">编辑</Button>
                            <Button type="error" size="small" @click="showDeletingModal(role,i)" :loading="role.isDeleting" v-if="isDeletePermitted">删除</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="新增角色"
                footer-hide
                :styles="{top:'75px',left:'-90px'}"
            >
                <Input v-model="addData.roleName" class="mb-2" placeholder="输入一些东西..."/>
                <div class="p-modal-scroll" style="height:70vh!important">
                    <Tree :data="addData.permission" show-checkbox  expand-node @on-check-change="selPermission"></Tree>
                    <div class="es-model-operate">
                        <Button type="default" @click="addModal=false">取消</Button>
                        <Button type="primary" @click="addRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                    </div>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="新增角色"
                footer-hide
                :styles="{top:'75px',left:'-90px'}"
            >
                <Input v-model="editData.roleName" class="mb-2" placeholder="输入一些东西..." />
                <div class="p-modal-scroll" style="height:70vh!important">
                    <Tree :data="editData.permission" show-checkbox  expand-node @on-check-change="selPermission"></Tree>

                    <div class="es-model-operate">
                        <Button type="default" @click="closeEditModal">取消</Button>
                        <Button type="primary" @click="editRole" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                    </div>
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
                permission:[
                    {
                        title:"权限管理",
                        expand:true,
                        children:[
                            {
                                title:"学校空间",
                                expand:true,
                                children:[
                                    {
                                        title:"问卷"
                                    },
                                    {
                                        title:"投票"
                                    },
                                    {
                                        title:"短信"
                                    },
                                    {
                                        title:"校园动态"
                                    },
                                    {
                                        title:"公告"
                                    },
                                    {
                                        title:"布告栏"
                                    },
                                    {
                                        title:"考勤"
                                    },
                                    {
                                        title:"作业"
                                    },
                                    {
                                        title:"习题"
                                    },
                                    {
                                        title:"家访"
                                    },
                                ]
                            },
                            {
                                title:"班级空间",
                                expand:true,
                                children:[
                                    {
                                        title:"问卷"
                                    },
                                    {
                                        title:"投票"
                                    },
                                    {
                                        title:"作业"
                                    },
                                    {
                                        title:"习题"
                                    },
                                    {
                                        title:"家访"
                                    },
                                    {
                                        title:"通知"
                                    },
                                    {
                                        title:"评价"
                                    },
                                    {
                                        title:"表彰"
                                    },
                                    {
                                        title:"课表"
                                    },
                                    {
                                        title:"相册"
                                    },
                                    {
                                        title:"文件"
                                    },
                                ]
                            }
                        ]
                    }
                ]
            },
            addModal:false,
            isAdding:false,
            roleLists:[],
            editModal:false,
            editData:{
                roleName:'',
                permission:[]
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            token:'',
            isIconImageNew:false,
            isEditingItem:false,
            websiteSettings:[],
            permission:[
                {
                    title:"权限管理",
                    expand:true,
                    children:[
                        {
                            title:"学校空间",
                            expand:true,
                            children:[
                                {
                                    title:"问卷"
                                },
                                {
                                    title:"投票"
                                },
                                {
                                    title:"短信"
                                },
                                {
                                    title:"校园动态"
                                },
                                {
                                    title:"公告"
                                },
                                {
                                    title:"布告栏"
                                },
                                {
                                    title:"考勤"
                                },
                                {
                                    title:"作业"
                                },
                                {
                                    title:"习题"
                                },
                                {
                                    title:"家访"
                                },
                            ]
                        },
                        {
                            title:"班级空间",
                            expand:true,
                            children:[
                                {
                                    title:"问卷"
                                },
                                {
                                    title:"投票"
                                },
                                {
                                    title:"作业"
                                },
                                {
                                    title:"习题"
                                },
                                {
                                    title:"家访"
                                },
                                {
                                    title:"通知"
                                },
                                {
                                    title:"评价"
                                },
                                {
                                    title:"表彰"
                                },
                                {
                                    title:"课表"
                                },
                                {
                                    title:"相册"
                                },
                                 {
                                    title:"文件"
                                },
                            ]
                        }
                    ]
                }
            ]
        }
    },
    async created(){
        this.token = window.Laravel.csrfToken
        const res = await this.callApi('get','/api/role');
        if(res.status == 200){
            this.roleLists = res.data;
            console.log('******************',this.roleLists)
            for(let i=0;i<this.roleLists.length;i++){
                this.roleLists[i].permission = JSON.parse(this.roleLists[i].permission)
            }
        }
    },
    methods:{
       showModal(){
           this.addModal = true;
       },
       async addRole(){
           this.isAdding = true;
           if(this.addData.roleName.trim()==''){
               this.isAdding = false;
               return this.error('角色名称为必填项');
           }
           console.log('&&&&&&&&&&&&&',this.addData)
            const res = await this.callApi('post', '/api/role',this.addData)
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
           if(this.editData.roleName.trim()==''){
               return this.error('类别名称为必填项');
           }
            this.isAdding = true;
            const res = await this.callApi('put', '/api/role',this.editData)
            this.isAdding = false;
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
                deleteUrl:'/api/role',
                data:category,
                deletingIndex:i,
                isDeleted:false,
            }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
        },
        
        closeEditModal(){
            this.isEditingItem = false;
            this.editModal = false
        },
        selPermission(val){
            console.log('++++++++++',val)
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