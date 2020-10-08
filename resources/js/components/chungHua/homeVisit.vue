<template>
<div>
    <div v-if="currentPath.query.addQuestion == undefined">
        <router-link :to="`${currentPath.path}?questionType=宿舍考勤&addQuestion=应用模板`">
            <div class="category-title template">
                <Icon type="ios-list-box-outline" />
                <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
            </div>
        </router-link>
        <div class="es-item">
            <div class="es-item-left">
                家访对象
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" @on-click="chooseType($event)">
                        <a href="javascript:void(0)">
                            {{visitData.name}}
                            <Icon type="ios-arrow-forward" />
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="全体成员">全体成员</DropdownItem>
                            <DropdownItem name="课室">课室</DropdownItem>
                            <DropdownItem name="公共区域">公共区域</DropdownItem>
                            <DropdownItem name="食堂">食堂</DropdownItem>
                            <DropdownItem name="会议室">会议室</DropdownItem>
                            <DropdownItem name="校门">校门</DropdownItem>
                            <DropdownItem name="其他">其他</DropdownItem>
                            <DropdownItem name="宿舍">宿舍</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                截止时间
            </div>
            <div class="es-item-right">
                <DatePicker type="datetime" v-model="visitData.deadline" placeholder="选择日期" ></DatePicker>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                家访内容
            </div>
            <div class="es-item-right">
                <span>{{visitData.type}}</span>
                <Icon type="ios-arrow-forward" /> 
            </div>
        </div>
        <textarea @keydown.enter.exact.prevent @keyup.enter.exact="submit" @keydown.enter.shift.exact="newline" v-model="visitData.content.text" class="text-content" style="height:250px" cols="30" rows="10" placeholder="输入内容" ></textarea>
        <div class="image-item" v-if="visitData.content.imgUrl">
            <div class="image-block">
                <div class="image-upload-list" v-for="(imgUrl,i) in visitData.content.imgUrl" :key="i">
                    <img :src="imgUrl" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="file-item row" v-if="visitData.content.otherUrl.length">
            <div class="col-4" v-for="(otherUrl,j) in visitData.content.otherUrl" :key="j">
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
        <div class="file-item row" v-if="visitData.content.videoUrl.length">
            <div class="col-4" v-for="(videoUrl,j) in visitData.content.videoUrl" :key="j">
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
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            <Button type="default" @click="draft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button>
        </div>
    </div>
    <div v-else-if="currentPath.query.addQuestion == '应用模板'">
        <!-- <div class="apps-template">
            <div v-if="templateDataList.length">
                <div class="template-item" v-for="(template ,i) in templateDataList" :key="i">
                    <router-link :to="{path:`${currentPath.path}?questionType=投票`,query:{myprop:template}}">
                        <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                        <img :src="template.imgUrl" alt="" class="picture">
                        <p class="text">{{template.templateName}}</p>
                    </router-link>
                </div>
            </div>
            <router-link :to="`${currentPath.path}?questionType=投票&addQuestion=应用模板&template=add`">
                <div class="template-item-add">
                    <Icon type="ios-add" size="120" color="#DEDEDE"/>
                </div>
            </router-link>
        </div>
        <div class="edit-btn">
            <Button type="primary" @click="editTemplate">编辑</Button>
        </div> -->
    </div>
</div>
</template>

<script>
import { Picker } from 'emoji-mart-vue'
export default {
    components:{
        Picker,
    },
    data(){
        return{
            templateCnt:0,
            draftCnt:0,
            emoStatus:false,
            visitData:{
                name:'全体成员',
                deadline:'',
                type:'15题',
                content:{
                    text:'',
                    imgUrl:[],
                    otherUrl:[],
                    videoUrl:[]
                }, 
            },
            isLoading:false,
            isDrafting:false,
            token:'',
            templateDataList:[],
            templateData:{

            }
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    methods:{
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.visitData.content.text){
                this.visitData.content.text = e.native
            }else{
                this.visitData.content.text = this.visitData.content.text + e.native
            }
        },
        newline(){
            this.visitData.content.text = `${this.visitData.content.text}\n`
            console.log('newline')
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.visitData.content.imgUrl.push(res);
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
            this.visitData.content.otherUrl.push(res);
        },
        videoSuccess(res,file){
            
            let url = `uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.visitData.content.videoUrl.push(res);
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
                    this.visitData.content.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.visitData.content.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.visitData.content.videoUrl.pop(fileName)
                }
            }else{
                this.swr();
            }
        },
        async submit(){
            if(this.visitData.deadline == ''){
                return this.error('deadline')
            }
            if(this.visitData.content == ''){
                return this.error('content')
            }
            this.isLoading = true
            this.emoStatus = false;
            let userId = this.$store.state.user.id
            const res = await this.callApi('post','/api/questionnaire',{data:this.smsData,userId:userId,contentType:17})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
            this.isLoading = false;
        },
        draft(){

        }
    }
}
</script>