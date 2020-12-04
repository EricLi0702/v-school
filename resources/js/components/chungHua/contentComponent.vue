<template>
     <div>
        <textarea name="" id="" v-model="questionData.title" class="text-content pl-4 pr-3 pt-2 border-right-0 border-top-0 border-left-0 border-bottom" cols="30" rows="10" placeholder="输入内容"></textarea>
        <div class="image-item row m-0 p-0 px-4" v-if="questionData.imgUrl">
            <div class="image-block">
                <div class="image-upload-list" v-for="(imgUrl,i) in questionData.imgUrl" :key="i">
                    <img :src="imgUrl" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="file-item row col-12 px-4" v-if="questionData.otherUrl.length">
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(otherUrl,j) in questionData.otherUrl" :key="j">
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
        <div class="file-item row col-12 px-4" v-if="questionData.videoUrl.length">
            <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(videoUrl,j) in questionData.videoUrl" :key="j">
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
        <div class="category-title"></div> 
    </div>
</template>

<script>
export default {
    props:[
        'index','contentType','templateData'
    ],
    data(){
        return{
            questionData:{
                title:'',
                imgUrl:[],
                otherUrl:[
                ],
                videoUrl:[

                ]
            },
            token:'',
        }
    },
    watch:{
       
    },
    mounted:function(){
        
        this.$watch('questionData',function(){
            this.$emit('contentData',this.questionData);
        },{deep:true})
        if(this.templateData && this.index <= this.templateData.length){
            this.questionData.title = this.templateData[this.index-1].title
            this.questionData.imgUrl = this.templateData[this.index-1].imgUrl
            this.questionData.otherUrl = this.templateData[this.index-1].otherUrl
            this.questionData.videoUrl = this.templateData[this.index-1].videoUrl
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
        this.$set(this.questionData,'index',this.index)
        this.$set(this.questionData,'contentType',this.contentType)
    },
    methods:{
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.questionData.imgUrl.push(res);
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
            this.questionData.otherUrl.push(res);
        },
        videoSuccess(res,file){
            let url = `/uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.questionData.videoUrl.push(res);
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
                    this.questionData.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.questionData.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.questionData.videoUrl.pop(fileName)
                }
            }
            if(res.status != 200){
                this.swr();
            }
        }
    }
}
</script>