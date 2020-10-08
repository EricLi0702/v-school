<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == undefined">
                <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=应用模板`">
                    <div class="category-title template">
                        <Icon type="ios-list-box-outline" />
                        <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
                    </div>
                </router-link>
                <div class="es-item">
                    <div class="es-item-left w-100">
                        <Input v-model="addData.title" class="customInput" placeholder="公告标题"/>
                    </div>
                    <div class="es-item-right">
                        
                    </div>
                </div>
                <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=调查范围`">
                    <div class="es-item">
                        <div class="es-item-left">
                            落款名称
                        </div>
                        <div class="es-item-right">
                            <span v-if="addData.signName != ''">{{addData.signName}}</span>
                            <span v-else>{{$store.state.user.name}}</span>
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=展示范围`">
                    <div class="es-item">
                        <div class="es-item-left">
                            展示范围
                        </div>
                        <div class="es-item-right">
                            <span v-if="addData.viewList">{{addData.viewList.length}}个群组</span>
                            <span v-else>必填</span>
                            <Icon type="ios-arrow-forward" /> 
                        </div>
                    </div>
                </router-link>
                <div class="es-item">
                    <div class="es-item-left">
                        签名反馈
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="addData.scopeFlag" />
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
                    <div  v-if="templateDataList.length">
                        <div class="template-item" v-for="(template ,i) in templateDataList" :key="i">
                            <router-link :to="{path:`${currentPath.path}?questionType=公告`,query:{myprop:template}}">
                                <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                                <img :src="template.imgUrl" alt="" class="picture" v-if="template.imgUrl">
                                <img src="/img/icon/33.jpg" alt="" class="picture" v-else>
                                <p class="text" v-if="template.templateName">{{template.templateName}}</p>
                                <p class="text" v-else>draft</p>
                            </router-link>
                        </div>
                    </div>
                    <router-link :to="`${currentPath.path}?questionType=公告&addQuestion=应用模板&template=add`">
                        <div class="template-item-add">
                            <Icon type="ios-add" size="120" color="#DEDEDE"/>
                        </div>
                    </router-link>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '调查范围'">
                <div class="es-item" @click="signName($store.state.user.name)">
                    {{$store.state.user.name}}
                </div>
                <div class="category-title"></div>
                <div class="es-item" @click="signName('从化第四中学')">
                    从化第四中学
                </div>
                <div class="es-item" @click="signName('全体老师')">
                    全体老师
                </div>
                <div class="es-item" @click="signName('宿舍管理员')">
                    宿舍管理员
                </div>
                <div class="es-item" @click="addName">
                    <div class="es-item-left"><Icon type="md-add" />自定义落款</div>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '展示范围'">
                <schoolList :type="'公告'"></schoolList>
            </div>
        </div>
        <div v-else-if="currentPath.query.template == 'add'">
            <div class="es-item">
                <div class="es-item-left">
                    模板名称
                </div>
                <div class="es-item-right">
                    <Input v-model="templateData.templateName" class="rightToLeft" maxlength="11" placeholder="必填" style="width: 200px" />
                </div>
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
                            <span>模板封面</span>
                    </Upload>
                </div>
                <div class="es-item-right">
                    <img :src="templateData.templateCover" alt="" style="width:40px;height:30px" v-if="templateData.templateCover">
                    <span v-else>必填</span>
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            <div class="category-title"></div>
            <div class="es-item">
                <div class="es-item-left">
                    标题
                </div>
                <div class="es-item-right">
                    <Input v-model="templateData.title" class="rightToLeft" maxlength="11" placeholder="必填" style="width: 200px" />
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
import schoolList from './schoolList'
import { Picker } from 'emoji-mart-vue'
export default {
    components: {
        VueEditor,
        schoolList,
        Picker,
    },
    data(){
        return{
            addData:{
                title:'',
                signName:'',
                viewList:[],
                scopeFlag:false,
                content:''
            },
            templateData:{
                templateName:'',
                imgUrl:'',
                title:'',
                content:{
                    text:'',
                    imgUrl:[],
                    otherUrl:[],
                    videoUrl:[]
                },
                contentType:5,
                templateType:1,
            },
            isLoading:false,
            isDrafting:false,
            isEditing:false,
            templateDataList:[],
            templateCnt:0,
            draftCnt:0,
            token:'',
            emoStatus:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    watch:{
        currentPath:{
            handler(val){
                if(val.query.viewList){
                    this.addData.viewList = val.query.viewList
                }
                if(val.query.signName){
                    this.addData.signName = val.query.signName
                }
                if(val.query.myprop){
                    
                    val.query.myprop.content = JSON.parse(val.query.myprop.content)
                    console.log('$$$$',val.query.myprop)
                    this.addData.title = val.query.myprop.title
                    this.addData.content = val.query.myprop.content.text
                }
            },
            deep:true
        }
    },
    created(){
        axios.get('/api/template',{params:{
            contentType:5
        }}).then(template=>{
            if(template.status == 200){
                this.templateDataList = template.data;
                for( let i =0; i < this.templateDataList.length; i++){
                    if(this.templateDataList[i].templateType == 1){
                        this.templateCnt +=1;
                    }else{
                        this.draftCnt += 1;
                    }
                }
            }
        })
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
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('title')
            }
            if(this.addData.content == ''){
                return this.error('content')
            }
            if(!(this.addData.viewList && this.addData.viewList.length > 0)){
                return this.error('select school')
            }
            console.log(this.addData)
            this.isLoading = true;
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:5})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
        },
        signName(name){
            console.log(name);
            this.$router.push({path:`${this.$route.path}?questionType=公告`,query:{signName:name}})
        },
        addName(){

        },
        draft(){

        },
        handleSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.templateData.templateCover = res;
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.templateData.content.imgUrl.push(res);
        },
        otherSuccess (res, file) {
            let url = `/uploads/other/${res.fileName}`;
            this.$set(res,'imgUrl',url)
            this.templateData.content.otherUrl.push(res);
        },
        videoSuccess(res,file){
            
            let url = `uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.templateData.content.videoUrl.push(res);
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
                    this.templateData.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.templateData.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.templateData.videoUrl.pop(fileName)
                }
            }else{
                this.swr();
            }
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
        async addTemplate(){
            if(this.templateData.templateName.trim() == ''){
                return this.error('templateName')
            }
            if(this.templateData.templateCover == ''){
                return this.error('templateCover')
            }
            if(this.templateData.content.text == ''){
                return this.error('templateContent')
            }
            if(this.templateData.title == ''){
                return this.error('templateDataTitle')
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/template',this.templateData)
            console.log(res)
            if(res.status == 201){
                this.templateDataList.unshift(res.data[0])
                this.$router.push(`${this.$route.path}?questionType=公告&addQuestion=应用模板`)
            }
            this.isLoading = false
        },
        removeTemplate(template){
            console.log(template)
        }
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