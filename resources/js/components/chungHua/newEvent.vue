<template>
<div>
    <div v-if="currentPath.query.template == undefined">
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="`${currentPath.path}?questionType=活动&addQuestion=应用模板`">
                <div class="category-title template">
                    <Icon type="ios-list-box-outline" />
                    <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    标题
                </div>
                <div class="es-item-right">
                    <Input v-model="addData.title" class="customInput rightToLeft" placeholder="必填"/>
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    时间
                </div>
                <div class="es-item-right">
                    <DatePicker type="datetime" v-model="addData.deadline" placeholder="选择时间" ></DatePicker>
                </div>
            </div>
            <router-link :to="`${currentPath.path}?questionType=活动&addQuestion=参加对象`">
                <div class="es-item">
                    <div class="es-item-left">
                        参加对象
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div class="es-item">
                <textarea v-model="addData.description" class="text-content height-200" cols="30" rows="10" placeholder="简介（限200字）"></textarea>
            </div>
            <div class="image-item" v-if="addData.imgUrl.length">
                <div class="image-block">
                    <div class="image-upload-list" v-for="(imgUrl,i) in addData.imgUrl" :key="i">
                        <img :src="imgUrl" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl,'addData')"></Icon>
                        </div>
                    </div>
                </div>
            </div>
            <div class="file-item row" v-if="addData.otherUrl.length">
                <div class="col-4" v-for="(otherUrl,j) in addData.otherUrl" :key="j">
                    <div class="image-upload-list float-left">
                        <img src="/img/icon/icon_rar@2x.png" alt="">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-trash-outline" @click="deleteFile('other',otherUrl,'addData')"></Icon>
                        </div>
                    </div>
                    <div class="title pt-2">
                        <div class="text-break">{{otherUrl.fileOriName}}</div>
                        <div class="text-secondary">{{otherUrl.fileSize}}</div>
                    </div>
                    <div class="remark"></div>
                </div>
            </div>
            <div class="ke-custom-toolbar">
                <div class="es-item">
                    <div class="es-item-left">
                        <Upload
                            ref="imageUploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="addDataImg"
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
                            :on-success="addDataFile"
                            :on-error="handleError"
                            :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                            :max-size="524288"
                            :show-upload-list="false"
                            :on-format-error="handleFormatError"
                            :on-exceeded-size="handleMaxSize"
                            action="/api/fileUpload/other">
                                <img src="/img/icon/file.png" alt="" class="uploadicon">
                        </Upload>
                    </div>
                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '应用模板'">
            <div class="apps-template">
                <div v-if="templateDataList.length">
                    <div class="template-item" v-for="(template ,i) in templateDataList" :key="i">
                        <router-link :to="{path:`${currentPath.path}?questionType=活动`,query:{myprop:template}}">
                            <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                            <img :src="template.imgUrl" alt="" class="picture">
                            <p class="text">{{template.templateName}}</p>
                        </router-link>
                    </div>
                </div>
                <router-link :to="`${currentPath.path}?questionType=活动&addQuestion=应用模板&template=add`">
                    <div class="template-item-add">
                        <Icon type="ios-add" size="120" color="#DEDEDE"/>
                    </div>
                </router-link>
            </div>
            <div class="edit-btn">
                <Button type="primary" @click="editTemplate">编辑</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '参加对象'">

        </div>
    </div>
    <div v-else-if="currentPath.query.template == 'add'">
        <div class="es-item">
            <div class="es-item-left">
                模板名称
            </div>
            <div class="es-item-right">
                <Input v-model="templateData.templateName" class="rightToLeft" placeholder="选填" style="width: 200px" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                <Upload
                    ref="uploads"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="templateCoverImg"
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
                <img :src="templateData.imgUrl" alt="" style="width:40px;height:30px" v-if="templateData.imgUrl">
                <span v-else>必填</span>
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                模板名称
            </div>
            <div class="es-item-right">
                <Input v-model="templateData.content.title" class="rightToLeft" placeholder="选填" style="width: 200px" />
            </div>
        </div>
        <textarea name="" id="" v-model="templateData.content.description" class="text-content height-200" cols="30" rows="10" placeholder="输入内容"></textarea>
        <div class="image-item" v-if="templateData.content.imgUrl.length">
            <div class="image-block">
                <div class="image-upload-list" v-for="(imgUrl,i) in templateData.content.imgUrl" :key="i">
                    <img :src="imgUrl" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('image',imgUrl,'templateData')"></Icon>
                    </div>
                </div>
            </div>
        </div>
        <div class="file-item row" v-if="templateData.content.otherUrl.length">
            <div class="col-4" v-for="(otherUrl,j) in templateData.content.otherUrl" :key="j">
                <div class="image-upload-list float-left">
                    <img src="/img/icon/icon_rar@2x.png" alt="">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-trash-outline" @click="deleteFile('other',otherUrl,'templateData')"></Icon>
                    </div>
                </div>
                <div class="title pt-2">
                    <div class="text-break">{{otherUrl.fileOriName}}</div>
                    <div class="text-secondary">{{otherUrl.fileSize}}</div>
                </div>
                <div class="remark"></div>
            </div>
        </div>
        <div class="ke-custom-toolbar">
            <div class="es-item">
                <div class="es-item-left">
                    <Upload
                        ref="imageUploads"
                        :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                        :on-success="templateDataImg"
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
                        :on-success="templateDataFile"
                        :on-error="handleError"
                        :format="['doc','docx','zip','pdf','xls','xlsx','rp','mp3','rp','ppt','pptx','pptm','apk','rar']"
                        :max-size="524288"
                        :show-upload-list="false"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        action="/api/fileUpload/other">
                            <img src="/img/icon/file.png" alt="" class="uploadicon">
                    </Upload>
                </div>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="saveTemplate" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                title:'',
                deadline:'',
                description:'',
                imgUrl:[],
                otherUrl:[]
            },
            templateDataList:[],
            templateData:{
                templateName:'',
                imgUrl:'',
                content:{
                    title:'',
                    description:'',
                    imgUrl:[],
                    otherUrl:[]
                },
                templateType:1,
                contentType:9
            },
            token:'',
            isEditing:false,
            isLoading:false,
            templateCnt:0,
            draftCnt:0,
            templateProp:{}
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
        axios.get('/api/template',{params:{
            contentType:9
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
    watch:{
        async currentPath(value){
            if(value.query.myprop){
                console.log(value.query.myprop)
                this.templateProp = value.query.myprop
                if(typeof this.templateProp.content == "string"){
                    this.templateProp = JSON.parse(this.templateProp.content)
                }else{
                    this.templateProp = this.templateData.content
                }
                this.addData.title = this.templateProp.title
                this.addData.description = this.templateProp.description
                this.addData.imgUrl = this.templateProp.imgUrl
                this.addData.otherUrl = this.templateProp.otherUrl
            }
            if(value.query.addQuestion == '应用模板'){
                await axios.get('/api/template',{params:{
                    contentType:9
                }}).then(res=>{
                    if(res.status == 200){
                        this.templateDataList = res.data
                    }
                })
            }
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    methods:{
        addDataImg (res, file) {
            res = `/uploads/image/${res}`
            this.addData.imgUrl.push(res);
        },
        templateCoverImg (res, file) {
            res = `/uploads/image/${res}`
            this.templateData.imgUrl = res
        },
        templateDataImg (res, file) {
            res = `/uploads/image/${res}`
            this.templateData.content.imgUrl.push(res);
        },
        addDataFile (res, file) {
            let url = `/uploads/other/${res.fileName}`;
            this.$set(res,'imgUrl',url)
            this.addData.otherUrl.push(res);
        },
        templateDataFile (res, file) {
            let url = `/uploads/other/${res.fileName}`;
            this.$set(res,'imgUrl',url)
            this.templateData.content.otherUrl.push(res);
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
        
        async deleteFile(type,fileName,data){
            let filePath = '';
            if(type == 'image'){
                filePath = fileName
            }else {
                filePath = fileName.imgUrl
            }
            const res = await this.callApi('delete','/api/fileUpload/file',{fileName:filePath});
            if(res.status == 200){
                if(data == 'addData'){
                    if(type == 'image'){
                        this.addData.imgUrl.pop(fileName)
                    }else if(type == 'other'){
                        this.addData.otherUrl.pop(fileName)
                    }    
                }else if(data == 'templateData'){
                    if(type == 'image'){
                        this.templateData.imgUrl.pop(fileName)
                    }else if(type == 'other'){
                        this.templateData.otherUrl.pop(fileName)
                    }
                }
            }
            if(res.status != 200){
                this.swr();
            }
        },
        editTemplate(){
            this.isEditing = !this.isEditing
        },
        removeTemplate(data){

        },
        async saveTemplate(){
            console.log(this.templateData)
            if(this.templateData.templateName.trim() == ''){
                return this.error('')
            }
            if(this.templateData.tempalteCover == ''){
                return this.error('')
            }
            if(this.templateData.content.title.trim() == ''){
                return this.error('')
            }
            if(this.templateData.content.description.trim() == ''){
                return this.error('')
            }
            this.isLoading = true
            const res = await this.callApi('post','/api/template',this.templateData)
            console.log(res)
            if(res.status == 201){
                this.success('ok')
                this.templateDataList.push(this.templateData)
                this.$router.push(`${this.$route.path}?questionType=活动&addQuestion=应用模板`)
                this.templateData = [];
            }
            this.isLoading = false
        },
        async submit(){
            if(this.addData.title.trim() == ''){
                return this.error('')
            }
            if(this.addData.description.trim() == ''){
                return this.error('')
            }
            if(this.addData.deadline == ''){
                return this.error('')
            }
            this.isLoading = true
            let userId = this.$store.state.user.id
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:9})
            console.log(res)
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.swr();
            }
            this.isLoading = false;
        },
    }
}
</script>