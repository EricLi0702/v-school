<template>
     <div>
        <textarea name="" id="" v-model="questionData.title" class="text-content" cols="30" rows="10" placeholder="标题"></textarea>
        <div class="image-item" v-if="questionData.imgUrl">
            <div class="image-block">
                <div class="image-upload-list" v-for="(imgUrl,i) in questionData.imgUrl" :key="i">
                    <img :src="imgUrl" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="file-item row" v-if="questionData.otherUrl.length">
            <div class="col-4" v-for="(otherUrl,j) in questionData.otherUrl" :key="j">
                <div class="image-upload-list float-left">
                    <img src="/img/icon/icon_rar@2x.png" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('other',otherUrl)"></Icon>
                    </div>
                </div>
                <div class="title pt-2">
                    <div class="text-break">{{otherUrl.fileOriName}}</div>
                    <div class="text-secondary">{{otherUrl.fileSize}}</div>
                </div>
                <div class="remark"></div>
            </div>
        </div>
        <div class="file-item row" v-if="questionData.videoUrl.length">
            <div class="col-4" v-for="(videoUrl,j) in questionData.videoUrl" :key="j">
                <div class="image-upload-list float-left">
                    <img src="/img/icon/icon_mp4@2x.png" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('video',videoUrl)"></Icon>
                    </div>
                </div>
                <div class="title pt-2">
                    <div class="text-break">{{videoUrl.fileOriName}}</div>
                    <div class="text-secondary">{{videoUrl.fileSize}}</div>
                </div>
            </div>
            <div class="remark"></div>
        </div>
        <div class="ke-custom-toolbar">
            <div class="es-item">
                <div class="es-item-left">
                    <Upload
                        ref="imageUploads"
                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                        :on-success="imageSuccess"
                        :on-error="handleError"
                        :format="['jpg','gif','png']"
                        :max-size="2048"
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
        // if(this.templateData && this.index <= this.templateData.length){
        //     this.questionData.title = this.templateData[this.index-1].title
        //     this.questionData.imgUrl = this.templateData[this.index-1].imgUrl
        //     this.questionData.otherUrl = this.templateData[this.index-1].otherUrl
        //     this.questionData.videoUrl = this.templateData[this.index-1].videoUrl
        // }
        this.$set(this.questionData,'index',this.index)
        this.$set(this.questionData,'contentType',this.contentType)
        // console.log('singleSelectData',this.questionData);
        // this.$emit('contentData',this.questionData);
    },
    methods:{
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.questionData.imgUrl.push(res);
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select another file type.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
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