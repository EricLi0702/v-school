<template>
    <div>
        <!-- <div class="vx-item is-click">
            <div class="vx-item-left">
                <span>上课周数</span>
            </div>
            <div class="vx-item-right">
                <span>
                    必填
                </span>
                <span>
                    已选 {{}}
                </span>
                <Icon type="ios-arrow-forward"></Icon>
            </div>
        </div> -->
        <div class="vx-item is-click">
            <div class="vx-item-left">
                日期
            </div>
            <div class="vx-item-right">
                <DatePicker type="date" :options="options"  v-model="addData.publishDate" placeholder="选填" ></DatePicker>
            </div>
        </div>
        <div class="category-title align-center"> 任课总表 </div>
        <div v-if="addData.courseTable != null">
            <div class="vx-item">
                <div class="vx-item-left">
                    班级
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.classname}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    场所
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.location}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    周一第1节
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.first}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    周一第2节
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.second}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    周一第3节
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.third}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    周一第4节
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.fourth}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    周一第5节
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.fifth}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    周一第6节
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.sixth}}
                </div>
            </div>
            <div class="vx-item">
                <div class="vx-item-left">
                    周一第7节
                </div>
                <div class="vx-item-right">
                    {{addData.courseTable.seventh}}
                </div>
            </div>
        </div>
        <Modal
            v-model="uploadModal"
            class="uploadModal"
            title="导入习题"
            footer-hide
            :styles="{top:'140px',left:'64px'}">
                <Upload
                    ref="otherUploads"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    type="drag"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['xls','xlsx']"
                    :max-size="524288"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="/api/fileUpload/curriculumImport">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>将文件拖到此处，或 <span class="text-color">点击上传</span></p>
                    </div>
                </Upload>
                <div class="es-item-tooltip">
                    <div>导入说明</div> 
                    <div>1、必须按正确的格式将数据填入模板 <span @click="sampleDownload" class="text-color">（ 下载模板 ）</span></div>
                    <div>2、文件格式必须为xls、xlsx</div>
                </div>
        </Modal>
        <div class="es-model-operate">
            <Button type="primary" @click="upload">导入课表</Button>
            <Button type="primary" @click="submit" :loading="isLoading" :disabled="isLoading">提交</Button>
        </div>
    </div>
</template>

<script>
import contentComponent from '../contentComponent'
export default {
    components:{
        contentComponent,
    },
    data(){
        return{
            uploadModal:false,
            isLoading:false,
            token:window.Laravel.csrfToken,
            baseUrl:window.Laravel.base_url,
            options: {
                disabledDate (date) {
                    return date && date.valueOf() < Date.now() - 86400000;
                }
            },
            addData:{
                courseTable:null,
                publishDate:'',
                viewList:[]
            },
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    created(){

    },
    methods:{
        emitData(val){
            this.addData = val
        },
        async upload(){
            this.uploadModal = true
        },
        handleSuccess (res, file) {
            if(res.status == 400){
                this.error(res.phoneNumber+res.msg)
                return
            }
            this.success('操作成功')
            // console.log(res.data)
            this.addData.courseTable = res.data[0][0]
            console.log('++++++++',this.addData.courseTable)
            this.uploadModal = false
        },
        handleError (res, file) {
            console.log('error',res)
            // this.$Notice.warning({
            //     title:'文件格式不正确',
            //     desc:`${file.errors.file.length ? file.errors.file[0] : '出了些问题！'}`
            // })
            this.error('导入出错')
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式  错误，请选择其他文件类型。'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  太大，不超过512M。'
            });
        },
        sampleDownload(){
            let classId = this.currentPath.params.className
            location.href = this.baseUrl+"/export/curriculumTemplate?classId="+JSON.stringify(classId)
        },
        async submit(){
            if(this.addData.publishDate == ''){
                return this.error('日期为必填项')
            }
            if(this.addData.courseTable == null){
                return this.error('课程表为必填项')
            }
            this.addData.viewList.push(this.currentPath.params.className)
            // this.addData.viewList.push(this.currentPath.params.schoolName)
            let userId = this.$store.state.user.id
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:25,foamingPosition:foamingPosition})
            this.isLoading = false
             if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                // this.uploadModal = false
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
        }
    }
}
</script>

<style>

</style>