<template>
    <div class="w-100 es-view">
        <div class="bg-navbar-area" v-if="$isMobile()">
        </div>
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <Button type="success" class="addbtn m-2" @click="showModal"><Icon type="md-add" /> 添加</Button>
        </div>
        
        
        <div class="container content-container">
            <div class="_overflow_table_div p-scroll">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>图标图像</th>
                        <th>学校名称</th>
                        <th>等级名称</th>
                        <th>班级名称</th>
                        <th>被创造在</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(lesson,i) in lessonList" :key="i" v-if="lessonList.length">
                        <td>{{i+1}}</td>
                        <td class="table-image">
                            <img :src="lesson.imgUrl" alt="" />
                        </td>
                        <td><span v-if="lesson.schools">{{lesson.schools.schoolName}}</span></td>
                        <td><span v-if="lesson.grades">{{lesson.grades.gradeName}}</span></td>
                        <td>{{lesson.lessonName}}</td>
                        <td>{{TimeView(lesson.created_at)}}</td>
                        <td class="d-flex">
                            <Button type="info" size="small" @click="showEditModal(lesson,i)">编辑</Button>
                            <Button type="error" size="small" @click="showDeletingModal(lesson,i)" :loading="lesson.isDeleting">删除</Button>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- add model -->
            <Modal
                v-model="addModal"
                title=" 添加"
            >
                <Input v-model="modalData.lessonName" placeholder="输入一些东西..." style="width: 300px" />
                <Select v-model="modalData.schoolId" placeholder="选择学校" style="width:300px"@on-change="changeSchool(modalData.schoolId)">
                    <Option v-for="(school,i) in schoolList" :key="i" :value="school.id" >{{school.schoolName}}</Option>
                </Select>
                <Select v-model="modalData.gradeId" placeholder="选择成绩" style="width:300px">
                    <Option v-for="(grade,i) in gradeList" :key="i" :value="grade.id" >{{grade.gradeName}}</Option>
                </Select>
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
                
                <div class="demo-upload-list" v-if="modalData.imgUrl">
                    <img :src="modalData.imgUrl" />
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
                title="编辑"
            >
                <Input v-model="editData.lessonName" placeholder="输入一些东西..." style="width: 300px" />
                <Select v-model="editData.schoolId" placeholder="选择学校" style="width:300px"  @on-change="changeSchool(editData.schoolId)">
                    <Option v-for="(school,i) in schoolList" :key="i" :value="school.id" >{{school.schoolName}}</Option>
                </Select>
                <Select v-model="editData.gradeId" placeholder="所选成绩" style="width:300px">
                    <Option v-for="(grade,i) in gradeList" :key="i" :value="grade.id" >{{grade.gradeName}}</Option>
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
                    <Button type="default" @click="editModal=false">取消</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? '提交...': '提交'}}</Button>
                </div>
            </Modal>
            <!-- delete model -->
            <Modal class="delete-modal" v-model="showDeleteModal" width="360">
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
                lessonName:'',
                schoolId:null,
                gradeId:null,
                imgUrl:''
            },
            addModal:false,
            isAdding:false,
            schoolList:[],
            gradeList:[],
            lessonList:[],
            editModal:false,
            editData:{
                lessonName:'',
                schoolId:null,
                gradeId:null,
                imgUrl:''
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1,
            token:window.Laravel.csrfToken,
            isIconImageNew:false,
            isEditingItem:false,
        }
    },
    async created(){
        const [schoolTree,lessonList] = await Promise.all([
            this.callApi('get','/api/schoolTree'),
            this.callApi('get','/api/lesson'),
        ])
        if(schoolTree.status == 200){
            this.schoolList = schoolTree.data
            this.gradeList = this.schoolList[0].grades
        }
        if(lessonList.status == 200){
            this.lessonList = lessonList.data
        }
    },
    methods:{
       showModal(){
           this.addModal = true;
       },
       async addTag(){
            this.isAdding = true;
                const res = await this.callApi('post', '/api/lesson',this.modalData)
            if(res.status === 201){
                this.lessonList.unshift(res.data);
                this.success('学校已成功添加！');
                this.addModal = false;
                this.modalData.lessonName = '';
                this.modalData.imgUrl = '';
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

       async editTag(){
           this.isAdding = true;
            const res = await this.callApi('put', '/api/lesson',this.editData)
           if(res.status === 200){
               this.lessonList[this.index].lessonName = this.editData.lessonName;
               this.lessonList[this.index].imgUrl = this.editData.imgUrl;
               this.success('学校已成功添加！');
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


        showEditModal(tag,index){
            let _index = this.schoolList.findIndex(school=>school.id == tag.schoolId)
            this.gradeList = this.schoolList[_index].grades
            let obj = {
                id:tag.id,
                lessonName:tag.lessonName,
                schoolId:tag.schoolId,
                gradeId:tag.gradeId,
                imgUrl:tag.imgUrl
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','/api/lesson',this.deleteItem);
            if(res.status == 200){
                this.lessonList.splice(this.deletingIndex,1);
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

        changeSchool(schoolId){
            let index = this.schoolList.findIndex(school=>school.id == schoolId);
            this.gradeList = this.schoolList[index].grades
        },
        handleSuccess (res, file) {
            res = `/uploads/${res}`
            if(this.editModal){
                return this.editData.imgUrl = res;
            }
            this.modalData.imgUrl = res;
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
                this.modalData.imgUrl = '';
                this.$refs.editDataImage.clearFiles();
            }else {
                image = this.modalData.imgUrl;
                this.modalData.imgUrl = '';
                this.$refs.uploads.clearFiles();
            }
            const res = await this.callApi('delete', '/api/category/upload',{imageName:image})
            if(res.status!=200){
                this.modalData.imgUrl = image
                this.swr()
            }
        },
    }
}
</script>