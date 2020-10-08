<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == undefined">
                <router-link :to="`${currentPath.path}?questionType=校园动态&addQuestion=应用模板`">
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
                            <router-link :to="{path:`${currentPath.path}?questionType=校园动态`,query:{myprop:template}}">
                                <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                                <img :src="template.imgUrl" alt="" class="picture" v-if="template.imgUrl">
                                <img src="/img/icon/muban_def.57036a0.png" alt="" style="width:150px;height:78px" v-else>
                                <p class="text" v-if="template.templateName">{{template.templateName}}</p>
                                <p class="text" v-else>{{template.content.title}}</p>
                            </router-link>
                        </div>
                    </div>
                    <router-link :to="`${currentPath.path}?questionType=校园动态&addQuestion=应用模板&template=add`">
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
            <textarea @keydown.enter.exact.prevent @keyup.enter.exact="submit" @keydown.enter.shift.exact="newline" v-model="templateData.content.text" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
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
                        <router-link :to="`${currentPath}?questionType=短信&addQuestion=contact`">
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
                this.addData = this.templateContent;
            }
            if(value.query.addQuestion == '应用模板'){
                // const template = await this.callApi('get','/api/template',{contentType:2})
                // if(template.status == 200){
                //     this.templateDataList = template.data;
                // }
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
            if(value.query.viewList){
                this.votingResult.viewList = value.query.viewList
            }
        }
    },
    methods:{
        handleSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.addData.imgUrl = res;
        },
        templateSuccess(res,file){
            res = `/uploads/image/${res}`
            this.templateData.imgUrl = res;
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
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('title')
            }
            if(this.addData.imgUrl == ''){
                return this.error('imgUrl')
            }
            let userId = this.$store.state.user.id;
            this.isLoading = true
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:4})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
        },
        async draft(){
            if(this.addData.title.trim() == ''){
                return this.error('title')
            }
            this.isDrafting = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/template',{content:this.addData,userId:userId,contentType:4,templateType:2})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
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
        editTemplate(){
            this.isEditing = !this.isEditing
        },
        removeTemplate(data){

        },
    }
}
</script>