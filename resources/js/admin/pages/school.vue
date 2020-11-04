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
                        <th>图标图像</th>
                        <th>学校名称</th>
                        <th>创建于</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(school,i) in schoolList" :key="i" v-if="schoolList.length">
                        <td>{{i+1}}</td>
                        <td class="table-image">
                            <img :src="school.imgUrl" alt="" />
                        </td>
                        <td class="_table_name">{{school.schoolName}}</td>
                        <td>{{TimeView(school.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(school,i)">编辑</Button>
                            <Button type="error" size="small" @click="showDeletingModal(school,i)" :loading="school.isDeleting">Delete</Button>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- add model -->
            <Modal
                v-model="addModal"
                title="新增学校"
            >
                <Input v-model="addData.schoolName" placeholder="输入一些东西..." style="width: 300px" />
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
                    action="api/category/upload">
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
                    <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="编辑学校"
            >
                <Input v-model="editData.schoolName" placeholder="输入一些东西..." style="width: 300px" />
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
                    action="api/category/upload">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>单击或拖动文件以上传</p>
                    </div>
                </Upload>
                
                <div class="demo-upload-list" v-if="editData.imgUrl">
                    <img :src="editData.imgUrl" />
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteImage(false)"></Icon>
                    </div>
                </div>
                <div slot="footer">
                    <Button type="default" @click="editModal=false">取消</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
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
            addData:{
                schoolName:'',
                imgUrl:''
            },
            addModal:false,
            isAdding:false,
            schoolList:[],
            editModal:false,
            editData:{
                schoolName:'',
                imgUrl:'',
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            isIconImageNew:false,
            isEditingItem:false,
            token : window.Laravel.csrfToken,
        }
    },
    async created(){
        const res = await this.callApi('get','api/school');
        if(res.status == 200){
            this.schoolList = res.data;
        }
    },
    methods:{
       addModalemit(value){
           
           this.addModal = value;
       },
       async addTag(){
            this.isAdding = true;
            const res = await this.callApi('post', '/api/school',this.addData)
            if(res.status === 201){
                this.schoolList.unshift(res.data);
                this.success('学校已成功添加！');
                this.addModal = false;
                this.addData.schoolName = '';
                this.addData.imgUrl = ''
            }else{
                if(res.status === 422){
                    if(res.data.errors.schoolName){
                        this.info(res.data.errors.schoolName[0]);
                    }
                }else{
                    this.swr()
                }
                
            }
            this.isAdding = false;
       },

       async editTag(){
           this.isAdding = true;
            const res = await this.callApi('put', 'api/school',this.editData)
           if(res.status === 200){
               this.schoolList[this.index].schoolName = this.editData.schoolName;
               this.schoolList[this.index].imgUrl = this.editData.imgUrl;
               this.success('学校已成功添加！');
               this.editModal = false;
               
           }else{
               if(res.status == 422){
                   if(res.data.errors.schoolName){
                       this.info(res.data.errors.schoolName[0]);
                   }
               }else{
                   this.swr()
               }
               
           }
           this.isAdding = false;
       },

        showEditModal(school,index){
            let obj = {
                id:school.id,
                schoolName:school.schoolName,
                imgUrl:school.imgUrl
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','api/school',this.deleteItem);
            if(res.status == 200){
                this.schoolList.splice(this.deletingIndex,1);
                this.success('标记已成功删除！');
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
            res = `/uploads/${res}`
            if(this.editModal){
                return this.editData.imgUrl = res;
            }
            this.addData.imgUrl = res;
        },
        handleError (res, file) {
            //console.log('res',res);
            //console.log('file',file);
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
            const res = await this.callApi('delete', 'api/category/upload',{imageName:image})
            if(res.status!=200){
                this.addData.imgUrl = image
                this.swr()
            }
        },
    }
}
</script>