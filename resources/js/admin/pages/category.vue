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
            <div class="_overflow_table_div">
                <table class="_table">
                    <tr>
                        <th>ID</th>
                        <th>Icon image</th>
                        <th>Category Name</th>
                        <th>Crated at</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(category,i) in categoryLists" :key="i" v-if="categoryLists.length">
                        <td>{{category.id}}</td>
                        <td class="table_image">
                            <img :src="category.iconImage" alt="" />
                        </td>
                        <td class="_table_name">{{category.categoryName}}</td>
                        <td>{{category.created_at}}</td>
                        <td>
                            <Button type="info" size="small" @click="showEditModal(category,i)">Edit</Button>
                            <Button type="error" size="small" @click="showDeletingModal(category,i)" :loading="category.isDeleting">Delete</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add category"
            >
                <Input v-model="addData.categoryName" class="mb-2" placeholder="Enter something..."/>
                
                <!-- <div class="space"></div> -->
                <!-- upload model -->
                <Upload
                    ref="uploads"
                    type="drag"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="api/category/upload">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>Click or drag files here to upload</p>
                    </div>
                </Upload>
                
                <div class="demo-upload-list" v-if="addData.iconImage">
                    <img :src="addData.iconImage" />
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                    </div>
                </div>
                
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add Category'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit Category"
            >
                <Input v-model="editData.categoryName" class="mb-2" placeholder="Enter something..." />
                
                <Upload v-show="isIconImageNew"
                    ref="editDataImage"
                    type="drag"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="api/category/upload">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>Click or drag files here to upload</p>
                    </div>
                </Upload>

                <div class="demo-upload-list" v-if="editData.iconImage">
                    <img :src="editData.iconImage" />
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                    </div>
                </div>

                <div slot="footer">
                    <Button type="default" @click="closeEditModal">Close</Button>
                    <Button type="primary" @click="editCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing': 'Edit Category'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <deleteModal></deleteModal>
            <!-- <Modal v-model="showDeleteModal" width="360">
                <p slot="header" style="color:#f60;text-align:center">
                    <Icon type="ios-information-circle"></Icon>
                    <span>Delete confirmation</span>
                </p>
                <div style="text-align:center">
                    <p>Will you delete it?</p>
                </div>
                <div slot="footer">
                    <Button type="error" size="large" long :loading="isDeleting" @click="deletecategory">Delete</Button>
                </div>
            </Modal> -->
            <!-- <Page :total="100" /> -->
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
            // data: [
            //     {
            //         title: 'This is title 1',
            //         description: 'This is description, this is description, this is description.',
            //         avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
            //         content: 'This is the content, this is the content, this is the content, this is the content.'
            //     },
            //     {
            //         title: 'This is title 2',
            //         description: 'This is description, this is description, this is description.',
            //         avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
            //         content: 'This is the content, this is the content, this is the content, this is the content.'
            //     },
            //     {
            //         title: 'This is title 3',
            //         description: 'This is description, this is description, this is description.',
            //         avatar: 'https://dev-file.iviewui.com/userinfoPDvn9gKWYihR24SpgC319vXY8qniCqj4/avatar',
            //         content: 'This is the content, this is the content, this is the content, this is the content.'
            //     }
            // ],
            addData:{
                iconImage:'',
                categoryName:''
            },
            addModal:false,
            isAdding:false,
            categoryLists:[],
            editModal:false,
            editData:{
                iconImage:'',
                categoryName:''
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
        const res = await this.callApi('get','api/category');
        if(res.status == 200){
            // console.log(res)
            this.categoryLists = res.data;
            console.log(res);
        }
    },
    methods:{
       addModalemit(value){
           console.log('##########',value);
           this.addModal = value;
       },
       async addCategory(){
           this.isAdding = true;
           if(this.addData.categoryName.trim()==''){
               this.isAdding = false;
               return this.error('Category Name is required');
               
           }
           if(this.addData.iconImage.trim()==''){
               this.isAdding = false;
               return this.error('Icon image is required');
               
           }
            const res = await this.callApi('post', 'api/category',this.addData)
           if(res.status === 201){
               this.categoryLists.unshift(res.data);
               this.success('Category has been added successfully!');
               this.addModal = false;
               this.addData.categoryName = '';
               this.addData.iconImage = '';
               this.$refs.uploads.clearFiles();
           }else{
               if(res.status === 422){
                   if(res.data.errors.categoryName){
                       this.info(res.data.errors.categoryName[0]);
                   }
                   if(res.data.errors.iconImage){
                       this.info(res.data.errors.iconImage[0]);
                   }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },

       async editCategory(){
           this.isAdding = true;
           if(this.editData.categoryName.trim()==''){
               this.isAdding = false;
               return this.error('Category Name is required');
               
           }
           if(this.editData.iconImage.trim()==''){
               this.isAdding = false;
               return this.error('Icon image is required');
               
           }
            const res = await this.callApi('put', 'api/category',this.editData)
           if(res.status === 200){
               this.categoryLists[this.index].categoryName = this.editData.categoryName;
               this.success('category has been added successfully!');
               this.editModal = false;
               this.isIconImageNew = false;
           }else{
               if(res.status == 422){
                   if(res.data.errors.categoryName){
                       this.info(res.data.errors.categoryName[0]);
                   }
                   if(res.data.errors.iconImage){
                       this.info(res.data.errors.iconImage[0]);
                   }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },


        showEditModal(category,index){
            // let obj = {
            //     categoryName:category.categoryName,
            //     iconImage:category.iconImage
            // }
            this.editData = category;
            this.editModal = true;
            this.index = index;
            this.isEditingItem = true;
        },

        // async deleteCategory(){
        //     this.isDeleting = true;
        //     const res = await this.callApi('delete','api/category',this.deleteItem);
        //     if(res.status == 200){
        //         this.categoryLists.splice(this.deletingIndex,1);
        //         this.success('category has been deleted successfully!');
        //     }else{
        //         this.swr();
        //     }
        //     this.isDeleting = false;
        //     this.showDeleteModal = false;
        // },

        showDeletingModal(category,i){
            
            const deleteModalObj = {
                showDeleteModal:true,
                deleteUrl:'api/category',
                data:category,
                deletingIndex:i,
                isDeleted:false,
            }
            this.$store.commit('setDeleteModalObj',deleteModalObj);
            // this.deleteItem = category;
            // this.deletingIndex = i;
            // this.showDeleteModal = true;
        },
        handleSuccess (res, file) {
            res = `/uploads/${res}`
            if(this.isEditingItem){
                return this.editData.iconImage = res;
            }
            this.addData.iconImage = res;
        },
        handleError (res, file) {
            console.log('res',res);
            console.log('file',file);
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },

        async deleteImage(isAdd = true){
            let image = ''
            if(!isAdd){//for edit iconimage delete
                this.isIconImageNew = true
                image = this.editData.iconImage;
                this.editData.iconImage = '';
                this.addData.iconImage = '';
                this.$refs.editDataImage.clearFiles();
            }else {
                console.log('@@@@@@',this.addData.iconImage);
                image = this.addData.iconImage;
                this.addData.iconImage = '';
                this.$refs.uploads.clearFiles();
            }
            
            console.log(image);
            const res = await this.callApi('delete', 'api/category/upload',{imageName:image})
            if(res.status!=200){
                this.addData.iconImage = image
                this.swr()
            }
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
                this.categoryLists.splice(Obj.deletingIndex,1)
            }
        }
    }
    
}
</script>

<style>
    .demo-upload-list{
        display: inline-block;
        width: 60px;
        height: 60px;
        text-align: center;
        line-height: 60px;
        border: 1px solid transparent;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
        position: relative;
        box-shadow: 0 1px 1px rgba(0,0,0,.2);
        margin-right: 4px;
    }
    .demo-upload-list img{
        width: 100%;
        height: 100%;
    }
    .demo-upload-list-cover{
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,.6);
    }
    .demo-upload-list:hover .demo-upload-list-cover{
        display: block;
    }
    .demo-upload-list-cover i{
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>