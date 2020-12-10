<template>
    <div>
        <div v-if="item.contentType == 4 || item.contentType == 5">
            <vue-editor v-model="item.addData.content" placeholder="公告内容"></vue-editor>
        </div>
        <div v-else-if="item.contentType == 24">
            <textarea name="" id="" v-model="item.addData.description.title" class="text-content pl-4 pr-3 pt-2 border-right-0 border-top-0 border-left-0 border-bottom" cols="30" rows="10" placeholder="输入内容"></textarea>
            <div class="image-item row m-0 p-0 px-4" v-if="item.addData.description.imgUrl">
                <div class="image-block">
                    <div class="image-upload-list" v-for="(imgUrl,i) in item.addData.description.imgUrl" :key="i">
                        <img :src="imgUrl" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-item row col-12 px-4" v-if="item.addData.description.otherUrl.length">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(otherUrl,j) in item.addData.description.otherUrl" :key="j">
                    <div class="image-upload-list float-left file-gravatar-icon">
                        <img src="/img/icon/icon_rar@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('other',otherUrl)"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2 gray-font bg-light-gray w-100">
                        <div class="text-break word-ellipse">{{otherUrl.fileOriName}}</div>
                        <div class="">{{otherUrl.fileSize}}</div>
                    </div>
                </div>
            </div>
            <div class="file-item row col-12 px-4" v-if="item.addData.description.videoUrl.length">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(videoUrl,j) in item.addData.description.videoUrl" :key="j">
                    <div class="image-upload-list float-left file-gravatar-icon">
                        <img src="/img/icon/icon_mp4@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('video',videoUrl)"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2 gray-font bg-light-gray w-100">
                        <div class="text-break word-ellipse">{{videoUrl.fileOriName}}</div>
                        <div class="">{{videoUrl.fileSize}}</div>
                    </div>
                </div>
            </div>
            <div class="ke-custom-toolbar p-0 ">
                <div class="vx-item">
                    <div class="vx-item-left">
                        <Upload
                            ref="imageUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="imageSuccess"
                            :on-error="handleError"
                            :format="['jpg','gif','png']"
                            :max-size="10240"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/image">
                                <img src="/img/icon/photo.png" alt="" class="uploadicon">
                        </Upload>
                        <Upload
                            ref="otherUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="otherSuccess"
                            :on-error="handleError"
                            :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                            :max-size="524288"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/other">
                                <img src="/img/icon/file.png" alt="" class="uploadicon">
                        </Upload>
                        <Upload
                            ref="videoUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="videoSuccess"
                            :on-error="handleError"
                            :format="['mp4']"
                            :max-size="524288"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/video">
                                <img src="/img/icon/video.png" alt="" class="uploadicon">
                        </Upload>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="item.contentType == 1 || item.contentType == 2" class="vx-item" >
            <div class="vx-item-left">
                截止时间
            </div>
            <div class="vx-item-right">
                <DatePicker type="datetime" :options="options"  v-model="item.addData.deadline" placeholder="选填" ></DatePicker>
            </div>
        </div>
        <div class="es-model-operate">
            <Button :loading="isUpdating" :disabled="isUpdating" type="primary" @click="submit">提交</Button>
        </div>
    </div>
</template>
<script> 
import {VueEditor} from "vue2-editor"
import contentComponent from './contentComponent'
export default {
    props:['item'],
    components:{
        VueEditor,
        contentComponent
    },
    data(){
        return{
            token:'',
            isUpdating : false,
            options: {
                disabledDate (date) {
                    return date && date.valueOf() < Date.now() - 86400000;
                }
            },
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    mounted(){
    },
    methods:{
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.item.addData.description.imgUrl.push(res);
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'文件格式不正确',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式 ' + file.name + ' 错误，请选择其他文件类型。'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + ' 太大，不超过512M。'
            });
        },
        otherSuccess (res, file) {
            let url = `/uploads/other/${res.fileName}`;
            this.$set(res,'imgUrl',url)
            this.item.addData.description.otherUrl.push(res);
        },
        videoSuccess(res,file){
            let url = `/uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.item.addData.description.videoUrl.push(res);
        },
        async deleteFile(type,fileName){
            let filePath = '';
            if(type == 'image'){
                filePath = fileName
            }else {
                filePath = fileName.imgUrl
            }
            const res = await this.callApi('delete','/api/fileUpload/file',{fileName:filePath});
            if(res.status == 200){
                if(type == 'image'){
                    this.item.addData.description.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.item.addData.description.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.item.addData.description.videoUrl.pop(fileName)
                }
            }
            if(res.status != 200){
                this.swr();
            }
        },
        async submit(){
            this.isUpdating = true;
            if(this.item.contentType == 24){
                const res = await this.callApi('put','/api/questionnaire/update',{
                    content : this.item.addData.description.title,
                    bulletinId : this.item.id,
                    contentType : this.item.contentType
                })
                if(res.data.msg == 1){
                    this.success('删除成功');
                    this.isUpdating = false;
                    this.$emit("updatePostDetail", res.data.responseData);
                }
                else{
                    this.swr();
                    this.isUpdating = false;
                }
                
            }
            if(this.item.contentType == 4 || this.item.content  == 5){
                const res = await this.callApi('put','/api/questionnaire/update',{
                    content : this.item.addData.content,
                    bulletinId : this.item.id,
                    contentType : this.item.contentType
                })
                if(res.data.msg == 1){
                    this.success('删除成功');
                    this.isUpdating = false;
                    this.$emit("updatePostDetail", res.data.responseData);
                }
                else{
                    this.swr();
                    this.isUpdating = false;
                }
            }
            if(this.item.contentType == 1 || this.item.contentType  == 2 ){
                const res = await this.callApi('put','/api/questionnaire/update',{
                    deadline : this.item.addData.deadline,
                    bulletinId : this.item.id,
                    contentType : this.item.contentType
                })
                if(res.data.msg == 1){
                    this.success('删除成功');
                    this.isUpdating = false;
                    this.$emit("updatePostDetail", res.data.responseData);
                }
                else{
                    this.swr();
                    this.isUpdating = false;
                }
            }
            
        },
    }
}
</script>