<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="`${currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=应用模板`">
                <div class="category-title template">
                    <Icon type="ios-list-box-outline" />
                    <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">打卡名称</div>
                <div class="es-item-right">
                    <Input v-model="addData.title" class="customInput rightToLeft" placeholder="必填" width="500px" />
                </div>
            </div>
            <router-link :to="`${currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=打卡对象`">
                <div class="es-item">
                    <div class="es-item-left">
                        打卡对象
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=打卡时间`">
                <div class="es-item">
                    <div class="es-item-left">
                        打卡时间
                    </div>
                    <div class="es-item-right">
                        21天
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=打卡规则`">
                <div class="es-item">
                    <div class="es-item-left">
                        打卡规则
                    </div>
                    <div class="es-item-right">
                        选填
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=打卡内容`">
                <div class="es-item">
                    <div class="es-item-left">
                        打卡内容
                    </div>
                    <div class="es-item-right">
                        选填
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <textarea v-model="addData.content.text" class="text-content" style="height:250px" cols="30" rows="10" placeholder="输入内容" ></textarea>
            <div class="image-item" v-if="addData.content.imgUrl">
                <div class="image-block">
                    <div class="image-upload-list" v-for="(imgUrl,i) in addData.content.imgUrl" :key="i">
                        <img :src="imgUrl" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-item row" v-if="addData.content.otherUrl.length">
                <div class="col-4" v-for="(otherUrl,j) in addData.content.otherUrl" :key="j">
                    <div class="image-upload-list float-left">
                        <img src="/img/icon/icon_rar@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('other',otherUrl)"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2">
                        <div class="text-break">{{addData.content.fileOriName}}</div>
                        <div class="text-secondary">{{addData.content.fileSize}}</div>
                    </div>
                    <div class="remark"></div>
                </div>
            </div>
            <div class="file-item row" v-if="addData.content.videoUrl.length">
                <div class="col-4" v-for="(videoUrl,j) in addData.content.videoUrl" :key="j">
                    <div class="image-upload-list float-left">
                        <img src="/img/icon/icon_mp4@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('video',videoUrl)"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2">
                        <div class="text-break">{{addData.content.fileOriName}}</div>
                        <div class="text-secondary">{{addData.content.fileSize}}</div>
                    </div>
                </div>
                <div class="remark"></div>
            </div>
            <div class="ke-custom-toolbar pl-0">
                <div class="es-item position-relative bg-white cursor-unset">
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
                        <router-link :to="`${currentPath}?applicationType=养成打卡&questionType=养成打卡&addQuestion=contact`">
                            <img src="/img/icon/at.png" alt="" class="uploadicon">
                        </router-link>
                    </div>
                </div>
            </div>
            
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
                <Button type="default" @click="draft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '应用模板'">
            应用模板
        </div>
        <div v-else-if="currentPath.query.addQuestion == '打卡对象'">
            打卡对象
        </div>
        <div v-else-if="currentPath.query.addQuestion == '打卡时间'">
            打卡时间
        </div>
        <div v-else-if="currentPath.query.addQuestion == '打卡规则'">
            打卡规则
        </div>
        <div v-else-if="currentPath.query.addQuestion == '打卡内容'">
            打卡内容
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'contact'">
            <contactComponent></contactComponent>
        </div>
    </div>
</template>

<script>
import contactComponent from './contactComponent'
export default {
    components:{
        contactComponent,
    },
    data(){
        return{
            addData:{
                title:'',
                content:{
                    text:'',
                    imgUrl:[],
                    otherUrl:[],
                    videoUrl:[],
                }
            },
            templateCnt:0,
            draftCnt:0,
            isLoading:false,
            isDrafting:false,
            token:'',
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
    },
    methods:{
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.addData.content.imgUrl.push(res);
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
            this.addData.content.otherUrl.push(res);
        },
        videoSuccess(res,file){
            
            let url = `uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.addData.content.videoUrl.push(res);
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
                    this.addData.content.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.addData.content.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.addData.content.videoUrl.pop(fileName)
                }
            }else{
                this.swr();
            }
        },
        async submit(){
            if(this.addData.content.text == ''){
                return this.error('')
            }
            this.isLoading = true
            let userId = this.$store.state.user.id
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:19})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
            this.isLoading = false;
        },
        async draft(){
            this.isDrafting = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/template',{content:this.addData,userId:userId,contentType:19,templateType:2})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push(this.$route.path)

            }else{
                this.swr();
            }
            this.isDrafting = false;
        }
    }
}
</script>