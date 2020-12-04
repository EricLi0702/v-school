<template>
     <div>
        <div class="vx-item is-click" @click="toggleOpenDropdownMenuType">
            <div class="vx-item-left">
                栏目
            </div>
            <div class="vx-item-right">
                <Dropdown style="margin-left: 20px" :visible="isVisibleType" placement="bottom-end" trigger="custom" @on-click="chooseType($event)">
                    <a href="javascript:void(0)">
                        {{questionData.type}}
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="互问互答">互问互答</DropdownItem>
                        <DropdownItem name="拼团报名">拼团报名</DropdownItem>
                        <DropdownItem name="公共区域">公共区域</DropdownItem>
                        <DropdownItem name="旧物漂流">旧物漂流</DropdownItem>
                        <DropdownItem name="诚意推荐">诚意推荐</DropdownItem>
                        <DropdownItem name="寻物启事">寻物启事</DropdownItem>
                        <DropdownItem name="失物招领">失物招领</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <textarea @keydown.enter.exact.prevent @keyup.enter.exact="submit" @keydown.enter.shift.exact="newline" style="height:200px" name="" id="" v-model="questionData.title" class="text-content pl-4 pr-3 pt-2 border-right-0 border-top-0 border-left-0 border-bottom" cols="30" rows="10" placeholder="输入内容"></textarea>
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
        <div class="ke-custom-toolbar p-0">
            <div class="vx-item">
                <div class="emoji-area-popup sms-emoji" id="emoji">
                    <Picker v-if="emoStatus" set="emojione" @select="onInput" title="Pick your emoji..." />
                </div> 
                <div class="vx-item-left ml-0">
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
                </div>
            </div>
        </div>
        <div class="category-title"></div>
        <div class="es-model-operate">
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
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
            questionData:{
                title:'',
                type:'',
                imgUrl:[],
                otherUrl:[
                ],
                videoUrl:[

                ]
            },
            token:'',
            emoStatus:false,
            isLoading:false,
            isVisibleType: false,
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    methods:{
        toggleOpenDropdownMenuType(){
            this.isVisibleType = !this.isVisibleType;
        },
        newline(){
            this.questionData.title = `${this.questionData.title}\n`
        },
        chooseType($event){
            this.questionData.type = $event
        },
        toggleEmo(){
            this.emoStatus = !this.emoStatus;
        },
        onInput(e){
            if(!e){
                return false;
            }
            if(!this.questionData.title){
                this.questionData.title = e.native
            }else{
                this.questionData.title = this.questionData.title + e.native
            }
        },
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
            
            let url = `uploads/video/${res.fileName}`
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
            }else{
                this.swr();
            }
        },
        async submit(){
            if(this.questionData.title.trim() == ''){
                return this.error('')
            }
            if(this.questionData.type.trim() == ''){
                return this.error('')
            }
            let userId = this.$store.state.user.id;
            this.isLoading = true
            const res = await this.callApi('post','/api/questionnaire',{data:this.questionData,userId:userId,contentType:7})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
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