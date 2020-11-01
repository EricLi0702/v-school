<template>
<div>
    <div v-if="currentPath.query.addQuestion == undefined">
        <router-link :to="`${currentPath.path}?questionType=${currentPath.query.questionType}&addQuestion=应用模板`">
            <div class="category-title template">
                <Icon type="ios-list-box-outline" />
                <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
            </div>
        </router-link>
        <router-link :to="`${currentPath.path}?questionType=${currentPath.query.questionType}&addQuestion=全体成员`">
            <div class="es-item">
                <div class="es-item-left">
                    家访对象
                </div>
                <div class="es-item-right">
                    <span v-if="visitData.userInfo != null">{{visitData.userInfo.name}}</span>
                    <span v-else>全体成员</span>
                    <Icon type="ios-arrow-forward" /> 
                </div>
            </div>
        </router-link>
        <div class="es-item">
            <div class="es-item-left">
                截止时间
            </div>
            <div class="es-item-right">
                <DatePicker type="datetime"  :options="options" v-model="visitData.deadline" placeholder="选择日期" ></DatePicker>
            </div>
        </div>
        <router-link :to="`${currentPath.path}?questionType=${currentPath.query.questionType}&addQuestion=家访内容`">
            <div class="es-item">
                <div class="es-item-left">
                    家访内容
                </div>
                <div class="es-item-right">
                    <span>{{visitData.type}}</span>
                    <Icon type="ios-arrow-forward" /> 
                </div>
            </div>
        </router-link>
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
    <div v-else-if="currentPath.query.addQuestion == '全体成员'">
        <contact2Component  @selectedUser="selUser"></contact2Component>
    </div>
    <div v-else-if="currentPath.query.addQuestion == '家访内容'">
        <questionItemComponent
            :addData="visitData.description"
        ></questionItemComponent>
    </div>
</div>
</template>

<script>
import { Picker } from 'emoji-mart-vue'
import contact2Component from './contact2Component'
import questionItemComponent from './questionItemComponent'
export default {
    components:{
        Picker,
        contact2Component,
        questionItemComponent,
    },
    data(){
        return{
            templateCnt:0,
            draftCnt:0,
            emoStatus:false,
            visitData:{
                userInfo:null,
                deadline:'',
                type:'15题',
                description:[
                [
                    {title:"孩子的家庭成员有哪些？（多选题）"},
                    {title:"妈妈"},
                    {title:"外公外婆"},
                    {title:"爷爷奶奶"},
                    {title:"爸爸"},
                ],
                [
                    {title:"孩子是否住宿（单选题）"},
                    {title:"否"},
                    {title:"是"}
                ],
                [
                    {title:"您的孩子中午是否在校休息（单选题）"},
                    {title:"是"},
                    {title:"否"}
                ],
                [
                    {title:"您重视对孩子哪方面能力的培养？ （多选题）"},
                    {title:"个人卫生"},
                    {title:"语言能力"},
                    {title:"思维能力"},
                    {title:"文明礼貌"},
                    {title:"学习能力"},
                    {title:"自主劳动"}
                ],
                [
                    {title:"孩子比较擅长哪方面的能力？（多选题）"},
                    {title:"文明礼貌"},
                    {title:"学习能力"},
                    {title:"自主劳动"},
                    {title:"个人卫生"},
                    {title:"语言能力"},
                    {title:"思维能力"}
                ],
                [
                    {title:"您家里适合孩子阅读的课外书数量？（单选题）"},
                    {title:"文明礼貌"},
                    {title:"学习能力"},
                    {title:"自主劳动"},
                ],
                [
                    {title:"孩子的学习环境如何？（单选题）"},
                    {title:"有独立的房间"},
                    {title:"与兄弟姐妹共用房间"},
                    {title:"无独立房间"},
                ],
                [
                    {title:"家里的卫生情况如何（单选题）"},
                    {title:"很干净"},
                    {title:"一般"},
                    {title:"不干净"},
                ],
                [
                    {title:"孩子和家庭成员的关系？"},
                    {title:"操作成功"},
                    {title:"一般"},
                    {title:"差"},
                ],
                [
                    {title:"当您发现孩子成绩下降后，您的常用做法是？（单选题）"},
                    {title:"帮助分析原因，制定措施"},
                    {title:"口头训斥"},
                    {title:"打骂"},
                    {title:"老师教育方法不对"},
                    {title:"成绩不代表什么，身体健康就操作成功"},
                ],
                [
                    {title:"间隔多长时间主动与老师联系一次？（单选题）"},
                    {title:"一个月"},
                    {title:"一周"},
                    {title:"每天"},
                    {title:"从来没有"},
                    {title:"一学期"},
                ],
                [
                    {title:"您孩子定期帮助家人做家务吗？（单选题）"},
                    {title:"经常有"},
                    {title:"偶尔有"},
                    {title:"从来没有"},
                ],
                [
                    {title:"孩子对课外阅读的兴趣如何？（单选题）"},
                    {title:"很喜欢书，涉猎广泛，懂得很多课外知识"},
                    {title:"喜欢看书，但范围较窄"},
                    {title:"比较喜欢"},
                    {title:"很少看书"},
                    {title:"完全不看书"},
                ],
                [
                    {title:"孩子经常有朋友到家里来玩吗？（单选题）"},
                    {title:"他有很操作成功的朋友，经常来家里"},
                    {title:"几乎没有朋友，也从不邀请到家"},
                    {title:"有一些朋友，但不常到家"},
                    {title:"有自己的朋友，但我不喜欢他带朋友到家里"},
                ],
                [
                    {title:"孩子为人处世的态度，您觉得比较符合下列哪一条？（单选题）"},
                    {title:"态度粗暴，比较自我，不容易被人接受"},
                    {title:"态度蛮横，性情暴躁，容易与人发生矛盾"},
                    {title:"态度冷淡，与世无争，性格较孤僻"},
                    {title:"态度友善，情感真诚，容易被人喜爱"},
                    {title:"态度畏缩，容易跟随，缺乏自我和自信"},
                ]
                
            ],
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

            },
            options: {
                disabledDate (date) {
                    return date && date.valueOf() < Date.now() - 86400000;
                }
            },
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
                desc: '文件  ' + file.name + '太大，不超过2M。'
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
            console.log(this.visitData)
            if(this.visitData.userInfo == null){
                return this.error('user')
            }
            if(this.visitData.deadline == ''){
                return this.error('截止时间不能为空')
            }
            if(this.visitData.content == ''){
                return this.error('内容不能为空')
            }
            this.isLoading = true
            this.emoStatus = false;
            let userId = this.$store.state.user.id
            
            const res = await this.callApi('post','/api/questionnaire',{data:this.visitData,userId:userId,contentType:18})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
            this.isLoading = false;
        },
        draft(){

        },
        selUser(value){
            this.visitData.userInfo = value
        },
    }
}
</script>