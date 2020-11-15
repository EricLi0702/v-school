<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
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
                            <DropdownItem name="图文消息">图文消息</DropdownItem>
                            <DropdownItem name="纯文字">纯文字</DropdownItem>
                            <DropdownItem name="视频">视频</DropdownItem>
                            <DropdownItem name="走马灯提示">走马灯提示</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
            </div>
            <router-link :to="{path:currentPath.path,query:{questionType:currentPath.query.questionType,addQuestion:releaseScope}}">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>发布范围</span>
                    </div>
                    <div class="es-item-right">
                        <span>{{addData.target}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    展示开始
                </div>
                <div class="es-item-right">
                    <DatePicker type="datetime" v-model="addData.startShow" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    展示结束
                </div>
                <div class="es-item-right">
                    <DatePicker type="datetime" v-model="addData.endShow" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <div v-if="addData.type == '图文消息'">
                <div class="es-item">
                    <textarea @keydown.enter.exact.prevent @keyup.enter.exact="submit" @keydown.enter.shift.exact="newline" v-model="addData.description" class="text-content" cols="30" rows="10" placeholder="消息内容（仅限100字）"></textarea>
                </div>
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
                <div class="es-item">
                    <div class="es-item-left">
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
                    </div>
                </div>
            </div>
            <div v-else-if="addData.type == '纯文字'">
                <div class="es-item">
                    <textarea @keydown.enter.exact.prevent @keyup.enter.exact="submit" @keydown.enter.shift.exact="newline" v-model="addData.description" class="text-content" cols="30" rows="10" placeholder="消息内容（仅限100字）"></textarea>
                </div>
            </div>
            <div v-else-if="addData.type == '视频'">
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
            <div v-else-if="addData.type == '走马灯提示'">
                <div class="es-item">
                    <div class="es-item-left w-100">
                        <Input v-model="addData.name" class="customInput" placeholder="署名（必填）"/>
                    </div>
                </div>
                <div class="es-item">
                    <textarea @keydown.enter.exact.prevent @keyup.enter.exact="submit" @keydown.enter.shift.exact="newline" v-model="addData.description" class="text-content height-200" cols="30" rows="10" placeholder="消息内容（仅限100字）"></textarea>
                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'releaseScope'">
            <div class="es-tabs theme-1">
                
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                type:'图文消息',
                imgUrl:[],
                startShow:'',
                endShow:'',
                description:'',
                videoUrl:[],
                name:'',
                target:'全部'
            },
            token:'',
            isLoading:false
        }
    },
    created(){
        this.token = window.Laravel.csrfToken
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    methods:{
        newline(){
            this.addData.description = `${this.addData.description}\n`
        },
        chooseType($event){
            this.addData.type = $event;
        },
        imageSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.addData.imgUrl.push(res);
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
                desc: '文件 ' + file.name + ' 太大，不超过2M。'
            });
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
            if(this.addData.type == '图文消息'){
                if(this.addData.description.trim() == ''){
                    return this.error('描述')
                }
                if(this.addData.imgUrl.length == 0){
                    return this.error('图片')
                }
                if(this.addData.startShow == '' || this.addData.endShow == ''){
                    return this.error('日期')
                }
            }
            if(this.addData.type == '纯文字'){
                if(this.addData.description.trim() == 0){
                    return this.error('描述')
                }
                if(this.addData.startShow == '' || this.addData.endShow == ''){
                    return this.error('日期')
                }
            }
            if(this.addData.type == '视频'){
                if(this.addData.videoUrl.length == 0){
                    return this.error('视频')
                }
                if(this.addData.startShow == '' || this.addData.endShow == ''){
                    return this.error('日期')
                }
            }
            if(this.addData.type == '走马灯提示'){
                if(this.addData.name.trim() == ''){
                    return this.error('名称')
                }
                if(this.addData.description.trim() == ''){
                    return this.error('描述')
                }
                if(this.addData.startShow == '' || this.addData.endShow == ''){
                    return this.error('日期')
                }
            }
            this.isLoading = true
            let userId= this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:8})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
            this.isLoading = false
        }
    }
}
</script>