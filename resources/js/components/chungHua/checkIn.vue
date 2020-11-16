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
                        <span v-if="addData.viewList && addData.viewList.length>0">{{addData.viewList.length}}个班级</span>
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
                        {{addData.checkInTime.clockCycle}}天
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
                        <span v-if="addData.checkInRule != null">已填</span>
                        <span v-else>选填</span>
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
                        <span v-if="addData.checkInContent.length > 0">{{addData.checkInContent.length}}题</span>
                        <span v-else>选填</span>
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
                            :max-size="10240"
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
                        <router-link :to="`${currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=contact`">
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
            <schoolList :type="'养成打卡'" @viewList="selObject"></schoolList>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '打卡时间'">
            <checkInDate @checkInTime="checkInTime"></checkInDate>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '打卡规则'">
            <checkInRule @checkInRule="checkInRule"></checkInRule>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '打卡内容'">
            <div v-if="currentPath.query.addContent == undefined">
                <div class="category-title">默认已有打卡简答输入</div>
                <div v-if="addData.checkInContent.length > 0">
                    <div v-for="(content,i) in addData.checkInContent" :key="i">
                        <div v-if="content.type == 'content1'">
                            <div class="es-item">
                                <div class="es-item-left">
                                    {{content.description}}
                                </div>
                                <div class="es-item-right">
                                    单位：{{content.unit}}
                                </div>
                            </div>
                            <div class="category-title"></div>
                        </div>
                        <div v-else-if="content.type == 'content2'">
                            <div class="es-item" v-if="content.description1 != ''">
                                {{content.description1}}（单选题）
                            </div>
                            <div class="es-item" v-if="content.description2 != ''">
                                A {{content.description2}}
                            </div>
                            <div class="es-item" v-if="content.description3 != ''">
                                B {{content.description3}}
                            </div>
                            <div class="category-title"></div>
                        </div>
                        <div v-else-if="content.type == 'content3'">
                            <div class="es-item" v-if="content.description1 != ''">
                                {{content.description1}}（多选题）
                            </div>
                            <div class="es-item" v-if="content.description2 != ''">
                                A {{content.description2}}
                            </div>
                            <div class="es-item" v-if="content.description3 != ''">
                                B {{content.description3}}
                            </div>
                            <div class="category-title"></div>
                        </div>
                        <div v-else-if="content.type == 'content4'">
                            <div class="es-item">
                                {{content.description}}（评分题）
                            </div>
                            <div class="category-title"></div>
                        </div>
                        <div v-else-if="content.type == 'content5'">
                            <div class="es-item">
                                {{content.description}}（问答题）
                            </div>
                            <div class="category-title"></div>
                        </div>
                    </div>
                </div>
                <div class="es-item">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" @on-click="visible($event)">
                        <a href="javascript:void(0)">
                            <Icon type="ios-add" />
                            添加
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="数据采集">数据采集</DropdownItem>
                            <DropdownItem name="单选题">单选题</DropdownItem>
                            <DropdownItem name="多选题">多选题</DropdownItem>
                            <DropdownItem name="评分题">评分题</DropdownItem>
                            <DropdownItem name="问答题">问答题</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="checkIn">提交</Button>
                </div>
            </div>
            <div v-if="currentPath.query.addContent == '数据采集'">
                <textarea v-model="content1.description" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                <div class="es-item"></div>
                <div class="category-title"></div>
                <div class="es-item">
                    <div class="es-item-left">
                        单位
                    </div>
                    <div class="es-item-right w-50">
                        <Input v-model="content1.unit" class="customInput rightToLeft" placeholder="必填" width="500px" />
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addContent('content1')">提交</Button>
                </div>
            </div>
            <div v-if="currentPath.query.addContent == '单选题'">
                <textarea v-model="content2.description1" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                <div class="es-item"></div>
                <div class="category-title"></div>
                <textarea v-model="content2.description2" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                <div class="es-item"></div>
                <div class="category-title"></div>
                <textarea v-model="content2.description3" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                 <div class="es-model-operate">
                    <Button type="primary" @click="addContent('content2')">提交</Button>
                </div>
            </div>
            <div v-if="currentPath.query.addContent == '多选题'">
                <textarea v-model="content3.description1" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                <div class="es-item"></div>
                <div class="category-title"></div>
                <textarea v-model="content3.description2" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                <div class="es-item"></div>
                <div class="category-title"></div>
                <textarea v-model="content3.description3" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                 <div class="es-model-operate">
                    <Button type="primary" @click="addContent('content3')">提交</Button>
                </div>
            </div>
            <div v-if="currentPath.query.addContent == '评分题'">
                <textarea v-model="content4.description" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                <div class="es-item"></div>
                <div class="es-item">
                    <div class="es-item-left">最高分</div>
                    <div class="es-item-right">
                        <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="changeMinute($event)">
                            <a href="javascript:void(0)">
                                {{content4.maxMinute}}
                                <Icon type="ios-arrow-forward" />
                            </a>
                            <DropdownMenu slot="list">
                                <DropdownItem name="2">2</DropdownItem>
                                <DropdownItem name="3">3</DropdownItem>
                                <DropdownItem name="4">4</DropdownItem>
                                <DropdownItem name="5">5</DropdownItem>
                                <DropdownItem name="6">6</DropdownItem>
                                <DropdownItem name="7">7</DropdownItem>
                                <DropdownItem name="8">8</DropdownItem>
                                <DropdownItem name="9">9</DropdownItem>
                                <DropdownItem name="10">10</DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addContent('content4')">提交</Button>
                </div>
            </div>
            <div v-if="currentPath.query.addContent == '问答题'">
                <textarea v-model="content5.description" class="text-content" style="height:250px" cols="30" rows="10" placeholder="标题" ></textarea>
                <div class="es-model-operate">
                    <Button type="primary" @click="addContent('content5')">提交</Button>
                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.addQuestion == 'contact'">
            <contactComponent @selectedUser="selUser"></contactComponent>
        </div>
    </div>
</template>

<script>
import contactComponent from './contactComponent'
import schoolList from './schoolList'
import checkInDate from './checkInDate'
import checkInRule from './checkInRule'
export default { 
    components:{
        contactComponent,
        schoolList,
        checkInDate,
        checkInRule,
    },
    data(){
        return{
            addData:{
                title:'',
                viewList:[],
                checkInTime:{
                    clockCycle:21,
                    selDate:['每周一','每周二','每周三','每周四','每周五','每周六','每周日']
                },
                checkInRule:null,
                checkInContent:[],
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
            content1:{
                description:'',
                unit:''
            },
            content2:{
                description1:'',
                description2:'',
                description3:''
            },
            content3:{
                description1:'',
                description2:'',
                description3:''
            },
            content4:{
                description:'',
                maxMinute:2
            },
            content5:{
                desecription:''
            }
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
                this.success('操作成功')
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
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push(this.$route.path)

            }else{
                this.swr();
            }
            this.isDrafting = false;
        },
        selUser(value){
            this.addData.content.text += value
        },
        selObject(value){
            this.addData.viewList = value
        },
        checkInTime(value){
            this.addData.checkInTime = value
        },
        checkInRule(value){
            this.addData.checkInRule = value
        },
        visible($event){
            this.$router.push(`${this.currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=打卡内容&addContent=${$event}`)
        },
        changeMinute($event){
            this.content4.maxMinute = $event
        },
        addContent(type){
            if(type == 'content1'){
                this.$set(this.content1,'type','content1')
                this.addData.checkInContent.push(this.content1)
                this.content1 = null;
            }else if(type == 'content2'){
                this.$set(this.content2,'type','content2')
                this.addData.checkInContent.push(this.content2)
                this.content2 = null;
            }else if(type == 'content3'){
                this.$set(this.content3,'type','content3')
                this.addData.checkInContent.push(this.content3)
                this.content3 = null;
            }
            else if(type == 'content4'){
                this.$set(this.content4,'type','content4')
                this.addData.checkInContent.push(this.content4)
                this.content4 = null;
            }else if(type == 'content5'){
                this.$set(this.content5,'type','content5')
                this.addData.checkInContent.push(this.content5)
                this.content5 = null;
            }
            this.$router.push(`${this.currentPath.path}?applicationType=养成打卡&questionType=养成打卡&addQuestion=打卡内容`)
        },
        checkIn(){
            this.$router.push(`${this.currentPath.path}?applicationType=养成打卡&questionType=养成打卡`)
        }
    }
}
</script>