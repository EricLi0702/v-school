<template>
    <div class="w-100 es-view">
        <div class="_1adminOverveiw_table_recent _box_shadow _border_radious mb-2 ml-10 w-930">
            <menuItem
                @addModalemit = "addModalemit"
            />
        </div>
        
        
        <div class="container content-container">
            <div class="_overflow_table_div p-scroll">
                <table class="table">
                    <tr>
                        <th>ID</th>
                        <th>学校名称</th>
                        <th>等级名称</th>
                        <th>班级名称</th>
                        <th>被创造在</th>
                        <th>行动</th>
                    </tr>
                    <tr v-for="(lesson,i) in lessonList" :key="i" v-if="lessonList.length">
                        <td>{{lesson.id}}</td>
                        <td>学校名称</td>
                        <td>等级名称</td>
                        <td>{{lesson.lessonName}}</td>
                        <td>{{lesson.created_at}}</td>
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
                title="Add Lesson"
            >
                <Input v-model="modalData.lessonName" placeholder="输入一些东西..." style="width: 300px" />
                <Select v-model="modalData.schoolId" placeholder="选择学校" style="width:300px">
                    <Option v-for="(school,i) in schoolList" :key="i" :value="school.id" >{{school.schoolName}}</Option>
                </Select>
                <Select v-model="modalData.gradeId" placeholder="选择成绩" style="width:300px">
                    <Option v-for="(grade,i) in gradeList" :key="i" :value="grade.id" >{{grade.gradeName}}</Option>
                </Select>
                <div slot="footer">
                    <Button type="default" @click="addModal=false">关</Button>
                    <Button type="primary" @click="addTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Adding': 'Add lesson'}}</Button>
                </div>
            </Modal>

            <!-- edit model -->
            <Modal
                v-model="editModal"
                title="Edit Lesson"
            >
                <Input v-model="editData.lessonName" placeholder="输入一些东西..." style="width: 300px" />
                <Select v-model="editData.schoolId" placeholder="选择学校" style="width:300px">
                    <Option v-for="(school,i) in schoolList" :key="i" :value="school.id" >{{school.schoolName}}</Option>
                </Select>
                <Select v-model="editData.gradeId" placeholder="所选成绩" style="width:300px">
                    <Option v-for="(grade,i) in gradeList" :key="i" :value="grade.id" >{{grade.gradeName}}</Option>
                </Select>
                <div slot="footer">
                    <Button type="default" @click="editModal=false">关</Button>
                    <Button type="primary" @click="editTag" :disabled="isAdding" :loading="isAdding">{{isAdding ? 'Editing': 'Edit lesson'}}</Button>
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
            modalData:{
                lessonName:'',
                schoolId:null,
                gradeId:null,
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
            },
            index:-1,
            showDeleteModal:false,
            isDeleting:false,
            deleteItem:{},
            deletingIndex:-1
        }
    },
    async created(){
        const [schoolList,lessonList,gradeList] = await Promise.all([
            this.callApi('get','/api/school'),
            this.callApi('get','/api/lesson'),
            this.callApi('get','/api/grade')
        ])
        if(schoolList.status == 200){
            this.schoolList = schoolList.data
        }
        if(lessonList.status == 200){
            this.lessonList = lessonList.data
        }
        if(gradeList.status == 200){
            this.gradeList = gradeList.data
        }
    },
    methods:{
       addModalemit(value){
           this.addModal = value;
       },
       async addTag(){
            this.isAdding = true;
                const res = await this.callApi('post', '/api/lesson',this.modalData)
            if(res.status === 201){
                this.lessonList.unshift(res.data);
                this.success('学校已成功添加！');
                this.addModal = false;
                this.modalData.lessonName = '';
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
            const res = await this.callApi('put', 'api/lesson',this.editData)
           if(res.status === 200){
               this.lessonList[this.index].lessonName = this.editData.lessonName;
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
            let obj = {
                id:tag.id,
                lessonName:tag.lessonName,
                schoolId:tag.schoolId,
                gradeId:tag.gradeId
            }
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },

        async deleteTag(tag,i){
            this.isDeleting = true;
            
            // tag.isDelete = true;
            this.$set(tag,'isDeleting',true);
            const res = await this.callApi('delete','api/lesson',this.deleteItem);
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

        changeSchool(){
            debugger
            let index = this.gradeList.findIndex(grade=>schoolId == this.editData.schoolId);
            let permission = this.roles[index].permission;
            if(permission == null){
                this.resources = this.assignRoleJson
            }else{
                this.resources = JSON.parse(permission)
            }
        }
    }
}
</script>