<template>
    <div>
        <router-link :to="`${currentPath.path}?questionType=活动&addQuestion=应用模板`">
            <div class="category-title template">
                <Icon type="ios-list-box-outline" />
                <span>可用模板3，草稿0</span>
            </div>
        </router-link>
        <div class="es-item">
            <div class="es-item-left w-100">
                <Input v-model="addData.title" class="customInput" placeholder="标题"/>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                时间
            </div>
            <div class="es-item-right">
                <DatePicker type="datetime" v-model="addData.deadline" placeholder="选择时间" ></DatePicker>
            </div>
        </div>
        <div class="es-item">
            <textarea name="" id="" v-model="addData.description" class="text-content" cols="30" rows="10" placeholder="简介（限200字）"></textarea>
        </div>
        <div class="image-item" v-if="addData.imgUrl">
            <div class="image-block">
                <div v-for="(imgUrl,i) in addData.imgUrl" :key="i">
                    <img :src="imgUrl" alt="">
                </div>
            </div>
        </div>
        <div class="file-item" v-if="addData.otherUrl.length">
            <div v-for="(otherUrl,j) in addData.otherUrl" :key="j">
                <div class="logo">
                    <img src="/img/icon/icon_rar@2x.png" alt="" class="avatar">
                </div>
                <div class="title">
                    <div>{{otherUrl.fileOriName}}</div>
                    <div>{{otherUrl.fileSize}}</div>
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
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                title:'',
                deadline:'',
                description:'',
                imgUrl:[],
                otherUrl:[]
            },
            token:''
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    methods:{
        imageSuccess (res, file) {
            console.log('@@@@@@@@',res);
            res = `/uploads/image/${res}`
            this.addData.imgUrl.push(res);
        },
        handleError (res, file) {
            console.log('##########',res);
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            console.log('$$$$$$$$$$');
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select another file type.'
            });
        },
        handleMaxSize (file) {
            console.log('%%%%%%%')
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        otherSuccess (res, file) {
            console.log('@@@@@@@@',res);
            let url = `/uploads/other/${res.fileName}`;
            this.$set(res,'imgUrl',url)
            this.addData.otherUrl.push(res);
            console.log('!!!!!!!!!!!',this.questionData.otherUrl)
        },
    }
}
</script>