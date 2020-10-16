<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == undefined">
                <router-link :to="`${currentPath.path}?applicationType=校园动态&questionType=校园动态&addQuestion=应用模板`">
                    <div class="category-title template">
                        <Icon type="ios-list-box-outline" />
                        <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
                    </div>
                </router-link>
                <div class="es-item">
                    <div class="es-item-left">
                        栏目
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left w-100">
                        <Input v-model="addData.title" class="customInput" placeholder="标题"/>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Upload
                            ref="uploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="handleSuccess"
                            :on-error="handleError"
                            :format="['jpg','jpeg','png']"
                            :max-size="2048"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="api/fileUpload/image">
                                <span>封面</span>
                        </Upload>
                    </div>
                    <div class="es-item-right">
                        <img :src="addData.imgUrl" alt="" style="width:40px;height:30px" v-if="addData.imgUrl">
                        <span v-else>必填</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div>
                    <vue-editor v-model="addData.content" placeholder="公告内容"></vue-editor>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
                    <Button type="default" @click="draft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '应用模板'">
                <div class="apps-template">
                    <div v-if="templateDataList.length">
                        <div class="template-item" v-for="(template ,i) in templateDataList" :key="i">
                            <router-link :to="{path:`${currentPath.path}?applicationType=校园动态&questionType=校园动态`,query:{myprop:template}}">
                                <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                                <img :src="template.imgUrl" alt="" class="picture" v-if="template.imgUrl">
                                <img src="/img/icon/muban_def.57036a0.png" alt="" style="width:150px;height:78px" v-else>
                                <p class="text" v-if="template.templateName">{{template.templateName}}</p>
                                <p class="text" v-else>{{template.title}}</p>
                            </router-link>
                        </div>
                    </div>
                    <router-link :to="`${currentPath.path}?applicationType=校园动态&questionType=校园动态&addQuestion=应用模板&template=add`">
                        <div class="template-item-add">
                            <Icon type="ios-add" size="120" color="#DEDEDE"/>
                        </div>
                    </router-link>
                </div>
                <div class="edit-btn">
                    <Button type="primary" @click="editTemplate">编辑</Button>
                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.template == 'add'">
            <div class="es-item">
                <div class="es-item-left w-100">
                    <Input v-model="templateData.title" class="customInput" placeholder="标题"/>
                </div>
                <div class="es-item-right"></div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <Upload
                        ref="uploads"
                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                        :on-success="templateSuccess"
                        :on-error="handleError"
                        :format="['jpg','jpeg','png']"
                        :max-size="2048"
                        :show-upload-list="false"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="api/fileUpload/image">
                            <span>封面</span>
                    </Upload>
                </div>
                <div class="es-item-right">
                    <img :src="templateData.imgUrl" alt="" style="width:40px;height:30px" v-if="templateData.imgUrl">
                    <span v-else>必填</span>
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            <textarea v-model="templateData.content.text" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
            <div class="image-item" v-if="templateData.content.imgUrl && templateData.content.imgUrl.length >0">
                <div class="image-block">
                    <div class="image-upload-list" v-for="(imgUrl,i) in templateData.content.imgUrl" :key="i">
                        <img :src="imgUrl" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-item row" v-if="templateData.content.otherUrl.length &&templateData.content.otherUrl.length>0">
                <div class="col-4" v-for="(otherUrl,j) in templateData.content.otherUrl" :key="j">
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
            <div class="file-item row" v-if="templateData.content.videoUrl.length && templateData.content.videoUrl.length>0">
                <div class="col-4" v-for="(videoUrl,j) in templateData.content.videoUrl" :key="j">
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
                <div class="es-item position-relative">
                    <div class="emoji-area-popup sms-emoji" id="emoji">
                        <Picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji..." />
                    </div> 
                    <div class="es-item-left">
                        <Upload
                            ref="imageUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="contentSuccess"
                            :on-error="handleError"
                            :format="['jpg','gif','png']"
                            :max-size="2048"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/image">
                                <img src="/img/icon/photo.png" alt="" class="uploadicon">
                        </Upload>
                        <img src="/img/icon/emoji.png" alt="" class="uploadicon" @click="toggleEmo">
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
                        <router-link :to="`${currentPath}?applicationType=校园动态&questionType=短信&addQuestion=contact`">
                            <img src="/img/icon/at.png" alt="" class="uploadicon">
                        </router-link>
                        <img src="/img/icon/topic.png" alt="" class="uploadicon">
                    </div>
                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="addTemplate" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
    </div>  
</template>

<script>
import {VueEditor} from "vue2-editor"
import { Picker } from 'emoji-mart-vue'
export default {
    components: {
        VueEditor,
        Picker,
    },
    data(){
        return{
            addData:{
                title:'',
                imgUrl:'',
                content:''
            },
            templateData:{
                title:'',
                imgUrl:'',
                contentType:4,
                templateType:1,
                content:{
                    text:'',
                    imgUrl:[],
                    otherUrl:[],
                    videoUrl:[]
                },
                contentType:4,
                templateType:1
            },
            token:'',
            isLoading:false,
            isDrafting:false,
            isEditing:false,
            templateCnt:0,
            draftCnt:0,
            templateDataList:[],
            emoStatus:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
        this.start()
    },
    watch:{
        async currentPath(value){
            if(value.query.myprop){
                this.templateData = value.query.myprop
                // this.addData = this.templateData
                if(typeof this.templateData.content == "string"){
                    this.templateContent = JSON.parse(this.templateData.content)
                }else{
                    this.templateContent = this.templateData.content
                }
                // this.addData = this.templateContent;
                this.addData.title = this.templateData.title
                this.addData.imgUrl = this.templateData.imgUrl
                this.addData.content = this.templateData.content.text
            }
            if(value.query.addQuestion == '应用模板'){
                // const template = await this.callApi('get','/api/template',{contentType:2})
                // if(template.status == 200){
                //     this.templateDataList = template.data;
                // }
                console.log('--------------')
                await axios.get('/api/template',{params:{
                    contentType:4
                }}).then(res=>{
                    if(res.status == 200){
                        this.templateDataList = res.data
                        for( let i =0; i < this.templateDataList.length; i++){
                            this.templateDataList[i].content = JSON.parse(this.templateDataList[i].content)
                        }
                    }
                })
            }
        }
    },
    methods:{
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.templateData.content.text){
                this.templateData.content.text = e.native
            }else{
                this.templateData.content.text = this.templateData.content.text + e.native
            }
        },
        newline(){
            this.templateData.content.text = `${this.templateData.content.text}\n`
            console.log('newline')
        },
        //addData cover image
        handleSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.addData.imgUrl = res;
        },

        //templateData cover image
        templateSuccess(res,file){
            res = `/uploads/image/${res}`
            this.templateData.imgUrl = res;
        },

        //template content data images
        contentSuccess(res,file){
            res = `/uploads/image/${res}`
            this.templateData.content.imgUrl = res;
        },

        //template content data other file 
        otherSuccess (res, file) {
            let url = `/uploads/other/${res.fileName}`;
            this.$set(res,'imgUrl',url)
            this.templateData.content.otherUrl.push(res);
        },

        //template content data video file
        videoSuccess(res,file){
            
            let url = `uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.templateData.content.videoUrl.push(res);
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'文件格式不正确',
                desc:`${file.errors.file.length ? file.errors.file[0] : '出问题了！'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式的 ' + file.name + ' 错误，请选择其他文件类型。'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制。',
                desc: '文件  ' + file.name + ' 太大，不超过2M。'
            });
        },
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('标题')
            }
            if(this.addData.imgUrl == ''){
                return this.error('图片网址')
            }
            let userId = this.$store.state.user.id;
            this.isLoading = true
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:4})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
        },
        async draft(){
            if(this.addData.title.trim() == ''){
                return this.error('标题')
            }
            this.isDrafting = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/template',{title:this.addData.title,imgUrl:this.addData.imgUrl,content:this.addData.content,contentType:4,templateType:2})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push(this.$route.path)

            }else{
                this.swr();
            }
            this.isDrafting = false;
        },
        start(){
            axios.get('/api/template',{params:{
                contentType:4
            }}).then(template=>{
                if(template.status == 200){
                    this.templateDataList = template.data;
                    for( let i =0; i < this.templateDataList.length; i++){
                        this.templateDataList[i].content = JSON.parse(this.templateDataList[i].content)
                        if(this.templateDataList[i].templateType == 1){
                            this.templateCnt +=1;
                        }else{
                            this.draftCnt += 1;
                        }
                    }
                }
            })
        },
        async addTemplate(){
            if(this.templateData.title.trim() == ''){
                return this.error('标题')
            }
            if(this.templateData.imgUrl == ''){
                return this.error('封面图片')
            }
            if(this.templateData.content.text == ''){
                return this.error('内容')
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/template',this.templateData)
            if(res.status == 201){
                this.$router.push(`${this.$route.path}?applicationType=校园动态&questionType=校园动态&addQuestion=应用模板`)
                this.templateData = [];
            }
            this.isLoading = false
        },
        editTemplate(){
            this.isEditing = !this.isEditing
        },
        removeTemplate(data){

        },

    }
}
</script>

<style scoped>
.sms-emoji{
    position: absolute;
    top: 46px;
    left: -8px;
}
.emoji-mart{
    height:330px!important;
}
</style>