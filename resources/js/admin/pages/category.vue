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
                        <th>Tag Name</th>
                        <th>Crated at</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(tag,i) in tags" :key="i" v-if="tags.length">
                        <td>{{tag.id}}</td>
                        <td class="_table_name">{{tag.tagName}}</td>
                        <td>{{tag.created_at}}</td>
                        <td>
                            <Button type="info" size="small" @click="showEditModal(tag,i)">Edit</Button>
                            <Button type="error" size="small" @click="showDeletingModal(tag,i)" :loading="tag.isDeleting">Delete</Button>
                        </td>
                    </tr>
                </table>
            </div>
            

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add category"
            >
                <Input v-model="modalData.tagName" class="mb-2" placeholder="Enter something..."/>
                
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
                
                <div class="demo-upload-list" v-if="modalData.iconImage">
                    <img :src="`/uploads/${modalData.iconImage}`" />
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                    </div>
                </div>
                
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add tag'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit tag"
            >
                <Input v-model="editData.tagName" placeholder="Enter something..." style="width: 300px" />
                <div slot="footer">
                    <Button type="default" @click="editModal=false">Close</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing': 'Edit tag'}}</Button>
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
            modalData:{
                iconImage:'',
                categoryName:''
            },
            addModal:false,
            isAdding:false,
            tags:[],
            editModal:false,
            editData:{
                tagName:''
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            token:''
        }
    },
    async created(){
        this.token = window.Laravel.csrfToken
        const res = await this.callApi('get','api/tag');
        if(res.status == 200){
            // console.log(res)
            this.tags = res.data;
            console.log(res);
        }
    },
    methods:{
       addModalemit(value){
           console.log('##########',value);
           this.addModal = value;
       },
       async addTag(){
           this.isAdding = true;
           if(this.modalData.tagName.trim()==''){
               return this.error('tagName is required');
           }
            const res = await this.callApi('post', 'api/tag',this.modalData)
           if(res.status === 201){
               this.tags.unshift(res.data);
               this.success('Tag has been added successfully!');
               this.addModal = false;
               this.modalData.tagName = '';
           }else{
               if(res.status === 422){
                   if(res.data.errors.tagName){
                       this.info(res.data.errors.tagName[0]);
                   }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },

       async editTag(){
           this.isAdding = true;
           if(this.editData.tagName.trim()==''){
               return this.error('tagName is required');
           }
            const res = await this.callApi('put', 'api/tag',this.editData)
           if(res.status === 200){
               this.tags[this.index].tagName = this.editData.tagName;
               this.success('Tag has been added successfully!');
               this.editModal = false;
               
           }else{
               if(res.status == 422){
                   if(res.data.errors.tagName){
                       this.info(res.data.errors.tagName[0]);
                   }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },


        showEditModal(tag,index){
            let obj = {
                id:tag.id,
                tagName:tag.tagName
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','api/tag',this.deleteItem);
            if(res.status == 200){
                this.tags.splice(this.deletingIndex,1);
                this.success('Tag has been deleted successfully!');
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
        handleSuccess (res, file) {
            this.modalData.iconImage = res;
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

        async deleteImage(){
            console.log('@@@@@@',this.modalData.iconImage);
            let image = this.modalData.iconImage;
            this.modalData.iconImage = '';
            this.$refs.uploads.clearFiles();
            const res = await this.callApi('delete', 'api/category/upload',{imageName:image})
            if(res.status!=200){
                this.modalData.iconImage = image
                this.swr()
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