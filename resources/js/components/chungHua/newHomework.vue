<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="{path:currentPath.path,query:{questionType:'作业',addQuestion:'subject'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        科目
                    </div>
                    <div class="es-item-right">
                        <span v-if="homeworkData.subject != ''">{{homeworkData.subject}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    类型
                </div>
                <div class="es-item-right">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="homeworkType($event)">
                        <a href="javascript:void(0)">
                            <div>{{homeworkData.type}}<Icon type="ios-arrow-forward" /></div>
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="常规作业">常规作业</DropdownItem>
                            <DropdownItem name="在线作业">在线作业</DropdownItem>
                            <DropdownItem name="在线测试">在线测试</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
            </div>
            <router-link v-if="homeworkData.type == '在线测试'" :to="{path:currentPath.path,query:{questionTYpe:'作业',addQuestion:'homeworkQuestion'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        作业习题
                    </div>
                    <div class="es-item-right">
                        <div>必填</div>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="{path:currentPath.path,query:{questionType:'作业',addQuestion:'publishingRules'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        发布规则
                    </div>
                    <div class="es-item-right">
                        <div>即时发布</div>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div>
                <textarea v-model="homeworkData.text" class="text-content" style="height:250px" cols="30" rows="10" placeholder="输入内容" ></textarea>
                <div class="image-item" v-if="homeworkData.imgUrl">
                    <div class="image-block">
                        <div class="image-upload-list" v-for="(imgUrl,i) in homeworkData.imgUrl" :key="i">
                            <img :src="imgUrl" alt="">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl)"></Icon>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="file-item row" v-if="homeworkData.otherUrl.length">
                    <div class="col-4" v-for="(otherUrl,j) in homeworkData.otherUrl" :key="j">
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
                <div class="file-item row" v-if="homeworkData.videoUrl.length">
                    <div class="col-4" v-for="(videoUrl,j) in homeworkData.videoUrl" :key="j">
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
                <div class="ke-custom-toolbar pl-0">
                    <div class="es-item position-relative bg-white cursor-unset">
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
                            <router-link :to="{path:currentPath.path,query:{questionType:'作业',addQuestion:'contact'}}">
                                <img src="/img/icon/at.png" alt="" class="uploadicon">
                            </router-link>
                            <img src="/img/icon/topic.png" alt="" class="uploadicon">
                        </div>
                    </div>
                </div>
            </div>
            <div class="es-model-operate" id="add">
                <Button type="primary" @click="addHomework">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'subject'">
            <div class="es-item" v-for="(subject,i) in subjects" :key="i" @click="selSubject(subject)">
                <div class="es-item-left">
                    {{subject}}
                </div>
                <div class="es-item-right">
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'publishingRules'">
            <div class="es-item">
                <div class="es-item-left">
                    发布时间
                </div>
                <div class="es-item-right">
                    <DatePicker type="datetime" v-model="homeworkData.publishingRules.releaseTime" placeholder="即时发布" ></DatePicker>
                </div>
            </div>
            <router-link :to="{path:currentPath.path,query:{questionType:'作业',addQuestion:'classPresident'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        课代表
                    </div>
                    <div class="es-item-right">
                        选填
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div v-if="homeworkData.type != '常规作业'">
                <router-link v-if="homeworkData.type == '在线作业'" :to="{path:currentPath.path,query:{questionType:'作业',addQuestion:'ReferAnswer'}}">
                    <div class="es-item">
                        <div class="es-item-left">
                            参考答案
                        </div>
                        <div class="es-item-right">
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
                </router-link>
                <div class="es-item">
                    <div class="es-item-left">
                        截止时间
                    </div>
                    <div class="es-item-right">
                        <DatePicker type="datetime" v-model="homeworkData.publishingRules.deadline" placeholder="不限定" ></DatePicker>
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        逾期可提交作业
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="homeworkData.publishingRules.overdueFlag" />
                    </div>
                </div>
                <div class="category-title">
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        成员相互可见作业内容
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="homeworkData.publishingRules.showFlag" />
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="category-title"></div>
                <div class="es-item">
                    <div class="es-item-left">
                        提示家长评价
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="homeworkData.publishingRules.referFlag" />
                    </div>
                </div>
            </div>
            <div class="es-model-operate" id="publish">
                <Button type="primary" @click="addPublishingRules">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'classPresident'">
            classPresident
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'ReferAnswer'">
            <contentComponent
                :index="1"
                :contentType="'referAnswer'"
                @contentData="referAnswer"
            ></contentComponent>
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'contact'">
            <contactComponent></contactComponent>
        </div>
    </div>    
</template>

<script>
import {VueEditor} from "vue2-editor"
import editorComponent from './homework/editorComponent'
import comprehensionQuestion from './homework/comprehensionQuestion'
import connectionQuestion from './homework/connectionQuestion'
import { Picker } from 'emoji-mart-vue'
import contactComponent from './contactComponent'
import contentComponent from './contentComponent'
export default {
    components:{
        Picker,
        contactComponent,
        contentComponent,
    },
    data(){
        return{
            homeworkData:{
                subject:'',
                type:'在线作业',
                text:'',
                imgUrl:[],
                otherUrl:[],
                videoUrl:[],
                publishingRules:{
                    releaseTime:'',
                    monitor:'',
                    referAnswers:{
                        contentType:'',
                        index:'',
                        title:'',
                        imgUrl:[],
                        otherUrl:[],
                        videoUrl:[]
                    },
                    deadline:'',
                    referFlag:true,
                    overdueFlag:true,
                    showFlag:true,
                },
            },
            token:window.Laravel.csrfToken,
            emoStatus:false,
            
            subjects:[
                '语文','数学','英语','语文','物理','化学','生物','地理','音乐','美术'
            ],
            
        }
    },
    computed:{
        currentPath(){
            return this.$route
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
            if(!this.homeworkData.text){
                this.homeworkData.text = e.native
            }else{
                this.homeworkData.text = this.homeworkData.text + e.native
            }
        },
        selSubject(sentence){
            this.homeworkData.subject = sentence;
            this.$router.push({path:this.currentPath.path,query:{questionType:'作业'}})
        },
        homeworkType($event){
            this.homeworkData.type = $event
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.homeworkData.imgUrl.push(res);
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
            this.homeworkData.otherUrl.push(res);
        },
        videoSuccess(res,file){
            
            let url = `uploads/video/${res.fileName}`
            this.$set(res,'imgUrl',url)
            this.homeworkData.videoUrl.push(res);
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
                    this.homeworkData.imgUrl.pop(fileName)
                }else if(type == 'other'){
                    this.homeworkData.otherUrl.pop(fileName)
                }else if(type == 'video'){
                    this.homeworkData.videoUrl.pop(fileName)
                }
            }else{
                this.swr();
            }
        },
        addPublishingRules(){
            console.log(this.currentPath.path)
            if(this.homeworkData.type == '常规作业'){

            }else{

            }
            this.$router.push({path:this.currentPath.path,query:{questionType:'作业'}})
        },
        referAnswer(val){
            console.log(val)
            let index = this.homeworkData.publishingRules.referAnswers.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1){
                this.homeworkData.publishingRules.referAnswers.push(value);
            }else{
                this.homeworkData.publishingRules.referAnswers[index] = value;
            } 
        },
        async addHomework(){
            console.log(this.homeworkData)
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.homeworkData,userId:userId,contentType:15})
            console.log(res)
            if(res.status == 201){
                 this.success('好')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                console.log(res)
                this.swr()
            }
        }
    }
}
</script>

<style>

</style>