<template>
    <div class="w-100 es-view">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        <div class="container content-container">
            <div class="_overflow_table_div">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>Icon image</th>
                        <th>Grade Name</th>
                        <th>School Name</th>
                        <th>Crated at</th>
                        <th>Action</th>
                    </tr>
                    <tr v-for="(grade,i) in gradeList" :key="i" v-if="gradeList.length">
                        <td>{{grade.id}}</td>
                        <td class="table-image">
                            <img :src="grade.imgUrl" alt="" />
                        </td>
                        <td class="_table_name">{{grade.gradeName}}</td>
                        <td>
                            schoolName    
                        </td>
                        <td>{{grade.created_at}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(grade,i)">Edit</Button>
                            <Button type="error" size="small" @click="showDeletingModal(grade,i)" :loading="grade.isDeleting">Delete</Button>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- add model -->
            <Modal
                v-model="addModal"
                title="Add grade"
            >
                <Input v-model="addData.gradeName" class="mb-2" placeholder="Enter something..."/>
                <Select v-model="addData.gradeType" style="width:200px">
                    <Option value="高">高</Option>
                    <Option value="初">初</Option>
                </Select>
                <Select v-model="addData.schoolId" placeholder="Select school" style="width:300px">
                    <Option v-for="(school,i) in schoolList" :key="i" :value="school.id" >{{school.schoolName}}</Option>
                </Select>
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
                
                <div class="demo-upload-list" v-if="addData.imgUrl">
                    <img :src="addData.imgUrl" />
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                    </div>
                </div>
                
                <div slot="footer">
                    <Button type="default" @click="addModal=false">Close</Button>
                    <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add grade'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit Category"
            >
                <!-- <Input v-model="editData.categoryName" class="mb-2" placeholder="Enter something..." /> -->
                <Input v-model="editData.gradeName" class="mb-2" placeholder="Enter something..."/>
                <Select v-model="editData.gradeType" style="width:200px">
                    <Option value="高">高</Option>
                    <Option value="初">初</Option>
                </Select>
                <Select v-model="editData.schoolId" placeholder="Select school" style="width:300px">
                    <Option v-for="(school,i) in schoolList" :key="i" :value="school.id" >{{school.schoolName}}</Option>
                </Select>
                
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

                <div class="demo-upload-list" v-if="editData.imgUrl">
                    <img :src="editData.imgUrl" />
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
                imgUrl:'',
                gradeName:'',
                gradeType:'',
                schoolId:null
            },
            addModal:false,
            isAdding:false,
            schoolList:[],
            gradeList:[],
            editModal:false,
            editData:{
                imgUrl:'',
                gradeName:'',
                gradeType:'',
                schoolId:null
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
        const [schoolList,gradeList] = await Promise.all([
            this.callApi('get','api/school'),
            this.callApi('get','api/grade')
        ])
        if(schoolList.status == 200){
            console.log(schoolList)
            this.schoolList = schoolList.data
        }
        if(gradeList.status == 200){
            console.log(gradeList)
            this.gradeList = gradeList.data
        }
    },
    methods:{
       addModalemit(value){
           this.addModal = value;
       },
       async addCategory(){
            this.isAdding = true;
            const res = await this.callApi('post', 'api/grade',this.addData)
            if(res.status === 201){
                this.gradeList.unshift(res.data);
                this.success('Grade has been added successfully!');
                this.addModal = false;
                this.addData.gradeName = '';
                this.addData.imgUrl = '';
                this.$refs.uploads.clearFiles();
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

        async editCategory(){
            this.isAdding = true;
            console.log('schoolId',this.editData.schoolId)
            const res = await this.callApi('put', 'api/grade',this.editData)
            if(res.status === 200){
                this.gradeList[this.index].gradeName = this.editData.gradeName;
                this.success('Grade has been added successfully!');
                this.editModal = false;
                this.isIconImageNew = false;
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

        showDeletingModal(category,i){
            
            const deleteModalObj = {
                showDeleteModal:true,
                deleteUrl:'api/grade',
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
                return this.editData.imgUrl = res;
            }
            this.addData.imgUrl = res;
        },
        handleError (res, file) {
            //console.log('res',res);
            //console.log('file',file);
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
                image = this.editData.imgUrl;
                this.editData.imgUrl = '';
                this.addData.imgUrl = '';
                this.$refs.editDataImage.clearFiles();
            }else {
                image = this.addData.imgUrl;
                this.addData.imgUrl = '';
                this.$refs.uploads.clearFiles();
            }
            const res = await this.callApi('delete', 'api/category/upload',{imageName:image})
            if(res.status!=200){
                this.addData.imgUrl = image
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