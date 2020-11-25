<template>
    <div class="w-100 es-view mt-2">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <Button type="success" class="addbtn m-2" @click="showModal"><Icon type="md-add" /> 添加</Button>
        </div>
        <div class="container content-container">
            <div class="_overflow_table_div">
                <table class="table">
                    <tr>
                        <th>号码</th>
                        <th>图标图像</th>
                        <th>等级名称</th>
                        <th>学校名称</th>
                        <th>创建于</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(grade,i) in gradeList" :key="i" v-if="gradeList.length">
                        <td>{{i+1}}</td>
                        <td class="table-image">
                            <img :src="grade.imgUrl" alt="" />
                        </td>
                        <td class="_table_name">{{grade.gradeName}}</td>
                        <td>
                            <span v-if="grade.schools">{{grade.schools.schoolName}}</span>
                        </td>
                        <td>{{TimeView(grade.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(grade,i)">编辑</Button>
                            <Button type="error" size="small" @click="showDeletingModal(grade,i)" :loading="grade.isDeleting">删除</Button>
                        </td>
                    </tr>
                </table>
            </div>
            <!-- add model -->
            <Modal
                v-model="addModal"
                title="增加等级"
            >
                <Input v-model="addData.gradeName" class="mb-2" placeholder="输入一些东西..."/>
                <Select v-model="addData.schoolId" placeholder="选择学校" style="width:300px">
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
                    :max-size="10240"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="/api/category/upload">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>单击或拖动文件以上传</p>
                    </div>
                </Upload>
                
                <div class="demo-upload-list" v-if="addData.imgUrl">
                    <img :src="addData.imgUrl" />
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteImage"></Icon>
                    </div>
                </div>
                
                <div slot="footer">
                    <Button type="default" @click="addModal=false">取消</Button>
                    <Button type="primary" @click="addCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="编辑类别"
            >
                <!-- <Input v-model="editData.categoryName" class="mb-2" placeholder="Enter something..." /> -->
                <Input v-model="editData.gradeName" class="mb-2" placeholder="输入一些东西..."/>
                <!-- <Select v-model="editData.gradeType" style="width:200px">
                    <Option value="高">高</Option>
                    <Option value="初">初</Option>
                </Select> -->
                <Select v-model="editData.schoolId" placeholder="选择学校" style="width:300px">
                    <Option v-for="(school,i) in schoolList" :key="i" :value="school.id" >{{school.schoolName}}</Option>
                </Select>
                
                <Upload v-show="isIconImageNew"
                    ref="editDataImage"
                    type="drag"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['jpg','jpeg','png']"
                    :max-size="10240"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="/api/category/upload">
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
                    <Button type="default" @click="closeEditModal">取消</Button>
                    <Button type="primary" @click="editCategory" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
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
            this.callApi('get','/api/school'),
            this.callApi('get','/api/grade')
        ])
        if(schoolList.status == 200){
            this.schoolList = schoolList.data
        }
        if(gradeList.status == 200){
            this.gradeList = gradeList.data
        }
    },
    methods:{
       showModal(){
           this.addModal = true;
       },
       async addCategory(){
            this.isAdding = true;
            const res = await this.callApi('post', '/api/grade',this.addData)
            if(res.status === 201){
                this.gradeList.unshift(res.data);
                this.success('成绩已成功添加！');
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
            const res = await this.callApi('put', '/api/grade',this.editData)
            if(res.status === 200){
                this.gradeList[this.index].gradeName = this.editData.gradeName;
                this.success('成绩已成功添加！');
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
            
            // const deleteModalObj = {
            //     showDeleteModal:true,
            //     deleteUrl:'api/grade',
            //     data:category,
            //     deletingIndex:i,
            //     isDeleted:false,
            // }
            // this.$store.commit('setDeleteModalObj',deleteModalObj);
            this.deleteItem = category;
            this.deletingIndex = i;
            this.showDeleteModal = true;
        },
        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','/api/grade',this.deleteItem);
            if(res.status == 200){
                this.gradeList.splice(this.deletingIndex,1);
                this.success('标记已成功删除！');
            }else{
                this.swr();
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },
        handleSuccess (res, file) {
            res = `/uploads/${res}`
            if(this.isEditingItem){
                return this.editData.imgUrl = res;
            }
            this.addData.imgUrl = res;
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : '出问题了！'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式的 ' + file.name + ' 不正确，请选择jpg或png.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + ' 太大，不超过2M。'
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
            const res = await this.callApi('delete', '/api/category/upload',{imageName:image})
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