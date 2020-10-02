<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="`${currentPath.path}?questionType=场所预约&addQuestion=选择使用人`">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>使用人</span>
                    </div>
                    <div class="es-item-right">
                        <span>{{addData.userName}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>        
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    <span>类型</span>
                </div>
                <div class="es-item-right">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" @on-click="chooseType($event)">
                        <a href="javascript:void(0)">
                            {{addData.type}}
                            <Icon type="ios-arrow-forward" />
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="办公室">办公室</DropdownItem>
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
                    日期
                </div>
                <div class="es-item-right">
                    <DatePicker type="date" v-model="addData.deadline" placeholder="必填" ></DatePicker>
                </div>
            </div>
            <router-link :to="`${currentPath.path}?questionType=场所预约&addQuestion=时段`">
                <div class="es-item">
                    <div class="es-item-left">
                        时段
                    </div>
                    <div class="es-item-right">
                        <span>必填 </span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    场所
                </div>
                <div class="es-item-right">
                    <span>
                    必填 
                    </span>
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            <textarea style="height:200px"  @keydown.enter.exact.prevent @keyup.enter.exact="submit" @keydown.enter.shift.exact="newline" v-model="addData.description" class="text-content" cols="30" rows="10" placeholder="用途说明"></textarea>
            <div class="image-item" v-if="addData.imgUrl">
                <div class="image-block">
                    <div class="image-upload-list" v-for="(imgUrl,i) in addData.imgUrl" :key="i">
                        <img :src="imgUrl" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-item row" v-if="addData.otherUrl.length">
                <div class="col-4" v-for="(otherUrl,j) in addData.otherUrl" :key="j">
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
            <div class="file-item row" v-if="addData.videoUrl.length">
                <div class="col-4" v-for="(videoUrl,j) in addData.videoUrl" :key="j">
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
                        <img src="/img/icon/at.png" alt="" class="uploadicon">
                        <img src="/img/icon/topic.png" alt="" class="uploadicon">
                    </div>
                </div>
            </div>
            
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion='选择使用人'">
            <contactComponent></contactComponent>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '时段'">

        </div>
    </div>
</template>

<script>
import contactComponent from './contactComponent'
import { Picker } from 'emoji-mart-vue'
export default {
    components:{
        contactComponent,
        Picker,
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    data(){
        return{
            addData:{
                userName:'test',
                type:'办公室',
                deadline:'',
                timePeriod:'',
                place:'',
                description:'',
                imgUrl:[],
                otherUrl:[],
                videoUrl:[],
            },
            emoStatus:false,
            token:'',
            isLoading:false,
        }
    },
    created(){
        this.token = window.Laravel.csrfToken
    },
    methods:{
        chooseType($event){
            this.addData.type=$event;
        },
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.addData.description){
                this.addData.description = e.native
            }else{
                this.addData.description = this.addData.description + e.native
            }
        },
        newline(){
            this.addData.description = `${this.addData.description}\n`
            console.log('newline')
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.addData.imgUrl.push(res);
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
            this.addData.otherUrl.push(res);
        },
        videoSuccess(res,file){
            
            let url = `uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.addData.videoUrl.push(res);
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
                    this.addData.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.addData.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.addData.videoUrl.pop(fileName)
                }
            }else{
                this.swr();
            }
        },
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('')
            }
            if(this.addData.imgUrl.trim() == ''){
                return this.error('')
            }
            let userId = this.$store.state.user.id;
            this.isLoading = true
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:6})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
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