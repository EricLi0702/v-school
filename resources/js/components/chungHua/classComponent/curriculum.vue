<template>
    <div>
        <!-- <contentComponent @contentData="emitData"></contentComponent>
        <div class="es-model-operate">
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div> -->
        <div class="vx-item">
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
            <Button type="primary" @click="submit">导入课表

            </Button>
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
        async submit(){
            this.uploadModal = true
        },
        handleSuccess (res, file) {
            console.log('success',res)
            if(res.status == 400){
                this.error(res.phoneNumber+res.msg)
                return
            }
            this.success('操作成功')
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
            let schoolId = this.currentPath.params.schoolName
            console.log(schoolId)
            location.href = this.baseUrl+"/export/curriculumTemplate?schoolId="+JSON.stringify(schoolId)
        }
    }
}
</script>

<style>

</style>