<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == undefined">
                <router-link :to="`${currentPath.path}?applicationType=公告&questionType=公告&addQuestion=应用模板`">
                    <div class="category-title template gray-font">
                        <Icon type="ios-list-box-outline" />
                        <span>可用模板 {{templateCnt}}， 草稿 {{draftCnt}}</span>
                        <span class="right">
                            <Icon type="ios-arrow-forward" size="22" />
                        </span>
                    </div>
                </router-link>
                <div class="vx-item">
                    <div class="vx-item-left w-100">
                        <Input v-model="addData.title" class="customInput px-0" placeholder="公告标题"/>
                    </div>
                    <div class="vx-item-right">
                        
                    </div>
                </div>
                <router-link :to="`${currentPath.path}?applicationType=公告&questionType=公告&addQuestion=调查范围`">
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            落款名称
                        </div>
                        <div class="vx-item-right">
                            <span>{{addData.signName}}</span>
                            <!-- <span>{{$store.state.user.name}}</span> -->
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                </router-link>
                <router-link :to="`${currentPath.path}?applicationType=公告&questionType=公告&addQuestion=展示范围`">
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            展示范围
                        </div>
                        <div class="vx-item-right">
                            <span v-if="addData.viewList.length>0">{{addData.viewList.length}}个群组</span>
                            <span v-else>必填</span>
                            <Icon type="ios-arrow-forward" /> 
                        </div>
                    </div>
                </router-link>
                <div class="vx-item is-click">
                    <div class="vx-item-left">
                        签名反馈
                    </div>
                    <div class="vx-item-right">
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
                <div class="apps-template px-1">
                    <div class="row m-0 p-0">
                        <div v-if="templateDataList.length" class="template-item-cu col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 p-0 m-0" v-for="(template ,i) in templateDataList" :key="i">
                            <router-link :to="{path:`${currentPath.path}?applicationType=公告&questionType=公告`,query:{myprop:template}}">
                                <div class="template-item-container m-2 position-relative">
                                    <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                                    <img :src="template.imgUrl" alt="" class="picture w-100" v-if="template.imgUrl">
                                    <img src="/img/icon/33.jpg" alt="" class="picture w-100" v-else>
                                    <p class="text" v-if="template.templateName">{{template.templateName}}</p>
                                    <p class="text" v-else>draft</p>
                                </div>
                            </router-link>
                        </div>
                        <router-link :to="`${currentPath.path}?applicationType=公告&questionType=公告&addQuestion=应用模板&template=add`" class="template-item-add-cu col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 p-0 m-0">
                            <div class="text-center m-2 border">
                                <Icon type="ios-add" size="120" color="#DEDEDE"/>
                                <p class="pb-2">新建模板</p>
                            </div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '调查范围'">
                <div class="vx-item is-click" @click="signName($store.state.user.name)">
                    {{$store.state.user.name}}
                </div>
                <div class="category-title"></div>
                <div class="vx-item is-click" v-for="(signName,i) in signNameList" :key="i" @click="selSignName(signName.name)">
                    {{signName.name}}
                </div>
                <div class="vx-item is-click" @click="showAddDiv = !showAddDiv">
                    <div class="vx-item-left"><Icon type="md-add" />自定义落款</div>
                </div>
                <div class="vx-item" v-if="showAddDiv">
                    <div class="vx-item-left">
                        <Input v-model="addName" class="customInput w-100" placeholder="评估名称"/>
                    </div>
                    <div class="vx-item-right">
                        <Button type="primary" @click="addSignName" :loading="isLoading" :disabled="isLoading">提交</Button>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '展示范围'">
                <schoolList :type="'公告'"></schoolList>
            </div>
        </div>
        <div v-else-if="currentPath.query.template == 'add'">
            <div class="vx-item">
                <div class="vx-item-left">
                    模板名称
                </div>
                <div class="vx-item-right">
                    <Input v-model="templateData.templateName" class="rightToLeft" maxlength="11" placeholder="选填" style="width: 200px" />
                </div>
            </div>
            <Upload
                ref="uploads"
                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                :on-success="handleSuccess"
                :on-error="handleError"
                :format="['jpg','jpeg','png']"
                :max-size="10240"
                :show-upload-list="false"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                class="user-gravatar-upload"
                action="/api/fileUpload/image">
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            <span>模板封面</span>
                        </div>
                        <div class="vx-item-right">
                            <img :src="templateData.imgUrl" alt="" style="width:40px;height:30px" v-if="templateData.imgUrl">
                            <span v-else>必填</span>
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
            </Upload>
            <div class="category-title"></div>
            <div class="vx-item">
                <div class="vx-item-left">
                    标题
                </div>
                <div class="vx-item-right">
                    <Input v-model="templateData.title" class="rightToLeft" maxlength="11" placeholder="必填" style="width: 200px" />
                </div>
            </div>
            <textarea v-model="templateData.content.text" class="text-content pl-4 pr-3 pt-2 border-right-0 border-top-0 border-left-0 border-bottom" style="height:250px" cols="30" rows="10" placeholder="输入内容" ></textarea>
            <div class="image-item row m-0 p-0 px-4" v-if="templateData.content.imgUrl && templateData.content.imgUrl.length >0">
                <div class="image-block">
                    <div class="image-upload-list" v-for="(imgUrl,i) in templateData.content.imgUrl" :key="i">
                        <img :src="imgUrl" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-item row col-12 px-4" v-if="templateData.content.otherUrl.length &&templateData.content.otherUrl.length>0">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(otherUrl,j) in templateData.content.otherUrl" :key="j">
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
            <div class="file-item row col-12 px-4" v-if="templateData.content.videoUrl.length && templateData.content.videoUrl.length>0">
                <div class="col-12 col-sm-6 col-md-4 col-lg-4 shadow-none p-0 pr-3 d-flex mt-2" v-for="(videoUrl,j) in templateData.content.videoUrl" :key="j">
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
                    <div class="emoji-area-popup sms-emoji" id="emoji">
                        <Picker v-if="emoStatus" set="emojione" @select="onInput" title="选择你的表情符号..." />
                    </div> 
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
                        <router-link :to="`${currentPath}?applicationType=公告&questionType=短信&addQuestion=contact`">
                            <img src="/img/icon/at.png" alt="" class="uploadicon">
                        </router-link>
                        <!-- <img src="/img/icon/topic.png" alt="" class="uploadicon"> -->
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
            signNameList:[],
            showAddDiv:false,
            addName:'',
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
                    // val.query.myprop.content = JSON.parse(val.query.myprop.content)
                    this.addData.title = val.query.myprop.title
                    this.addData.content = JSON.parse(val.query.myprop.content).text
                }
            },
            deep:true
        }
    },
    async created(){
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
        const res = await this.callApi('get','/api/signName')
        if(res.status == 200){
            this.signNameList = res.data
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
        selSignName(signName){
            this.addData.signName = signName
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType}})
        },
        newline(){
            this.templateData.content.text = `${this.templateData.content.text}\n`
        },
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('标题')
            }
            if(this.addData.content == ''){
                return this.error('内容')
            }
            if(this.addData.signName.trim() == ''){
                return this.error('名称')
            }
            if(!(this.addData.viewList && this.addData.viewList.length > 0)){
                return this.error('选择学校')
            }
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            this.isLoading = true;
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:5,foamingPosition:foamingPosition})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
        },
        signName(name){
            this.$router.push({path:`${this.$route.path}?applicationType=公告&questionType=公告`,query:{signName:name}})
        },
        // addName(){

        // },
        draft(){

        },
        handleSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.templateData.imgUrl = res;
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
                title: '超出文件大小限制',
                desc: '文件  ' + file.name + ' 太大，不超过2M。'
            });
        },
        async addTemplate(){
            if(this.templateData.templateName.trim() == ''){
                return this.error('模板名称')
            }
            if(this.templateData.imgUrl == ''){
                return this.error('模板封面')
            }
            if(this.templateData.content.text == ''){
                return this.error('模板内容')
            }
            if(this.templateData.title == ''){
                return this.error('模板数据标题')
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/template',this.templateData)
            if(res.status == 201){
                this.templateDataList.unshift(res.data)
                this.$router.push(`${this.$route.path}?applicationType=公告&questionType=公告&addQuestion=应用模板`)
            }
            this.isLoading = false
        },
        removeTemplate(template){
        },
        async addSignName(){
            if(this.addName.trim() == ''){
                return this.error('内容不能为空')
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/signName',{addName:this.addName})
            if(res.status == 201){
                this.addName = ''
                this.success('操作成功')
                this.signNameList.push(res.data)
                this.showAddDiv = false
            }
            this.isLoading = false
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