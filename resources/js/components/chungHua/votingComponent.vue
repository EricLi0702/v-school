<template>
    <div>
        <div v-if="currentPath.query.template ==undefined">
            <div v-if="currentPath.query.addQuestion == undefined">
                <router-link :to="`${currentPath.path}?questionType=投票&addQuestion=应用模板`">
                    <div class="category-title template">
                        <Icon type="ios-list-box-outline" />
                        <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
                    </div>
                </router-link>
                <div class="es-item">
                    <div class="es-item-left">
                        投票人是否可见结果
                    </div>
                    <div class="es-item-right">
                        <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="visible($event)">
                            <a href="javascript:void(0)">
                                {{votingResult.vResult}}
                                <Icon type="ios-arrow-forward" />
                            </a>
                            <DropdownMenu slot="list">
                                <DropdownItem name="投票后可见">投票后可见</DropdownItem>
                                <DropdownItem name="投票前后均可见">投票前后均可见</DropdownItem>
                                <DropdownItem name="投票前后均不可见">投票前后均不可见</DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </div>
                </div>
                <router-link :to="`${currentPath.path}?questionType=投票&addQuestion=调查范围`">
                    <div class="es-item">
                        <div class="es-item-left">
                            调查范围
                        </div>
                        <div class="es-item-right">
                            <span v-if="votingResult.viewList && votingResult.viewList.length > 1">{{votingResult.viewList.length}}个群组</span>
                            <span>必填</span>
                            <Icon type="ios-arrow-forward" /> 
                        </div>
                    </div>
                </router-link>
                <div class="es-item">
                    <div class="es-item-left">
                        截止时间
                    </div>
                    <div class="es-item-right">
                        <DatePicker type="datetime" v-model="votingResult.deadline" placeholder="选填" ></DatePicker>
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        匿名问卷
                    </div>
                    <div class="es-item-right">
                        <i-switch true-color="#13ce66" v-model="votingResult.anonyVote" />
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        最高分
                    </div>
                    <div class="es-item-right">
                        <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseMaxVote($event)">
                            <a href="javascript:void(0)">
                                {{votingResult.maxVote}}
                                <Icon type="ios-arrow-forward" />
                            </a>
                            <DropdownMenu slot="list">
                                <DropdownItem name="1">1</DropdownItem>
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
                <div v-for="index1 in count" :key="index1">
                    <contentComponent
                        :index="index1"
                        :contentType="'singleContent'"
                        :templateData="votingResult.content.votingDataArr[0]"
                        @contentData="singleContentData"
                    ></contentComponent>
                </div>
                <div class="es-item" @click="addContent">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>添加选项</span>
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addVoting" :disabled="isLoading" :loading="isLoading">提交</Button>
                    <Button type="default" @click="addVotingDraft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button>
                    
                </div>
            </div>
            <div v-else>
                <div v-if="currentPath.query.addQuestion == '应用模板'">
                    <div class="apps-template">
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
                    </div>
                </div>
                <div v-else-if="currentPath.query.addQuestion == '调查范围'">
                    <schoolList :type="'投票'"></schoolList>
                </div>
            </div>
        </div>
        <div v-else>
            <div v-if="currentPath.query.template == 'add'">
                <div class="es-item">
                    <div class="es-item-left">
                        模板名称
                    </div>
                    <div class="es-item-right">
                        <Input v-model="templateData.templateName" class="rightToLeft" maxlength="11" placeholder="选填" style="width: 200px" />
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Upload
                            ref="uploads"
                            :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                            :on-success="handleSuccess"
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
                <div v-for="index1 in contentCnt" :key="index1">
                        <contentComponent
                            :index="index1"
                            :contentType="'singleContent'"
                            @contentData="singleContentData"
                        ></contentComponent>
                    </div>
                    <div class="es-item" @click="addTemplateContent">
                        <div class="es-item-left">
                            <Icon type="ios-add"/>
                            <span>添加选项</span>
                        </div>
                    </div>
                    <div class="es-model-operate">
                        <Button type="primary" @click="addVotingTemplate" :disabled="isLoading" :loading="isLoading">提交</Button>

                    </div>
            </div>
        </div>
    </div>
</template>

<script>
import contentComponent from './contentComponent'
import schoolList from './schoolList'
export default {
    components:{
        contentComponent,
        schoolList,
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    watch:{
        async currentPath(value){
            if(value.query.myprop){
                this.templateData = value.query.myprop
                // this.addData = this.templateData
                if(typeof this.templateData.content == "string"){
                    this.templateContent = JSON.parse(this.templateData.content)
                }else{
                    this.templateContent = this.templateData.content
                }
                this.votingResult.content.votingDataArr = this.templateContent.votingDataArr;
            }
            if(value.query.addQuestion == '应用模板'){
                // const template = await this.callApi('get','/api/template',{contentType:2})
                // if(template.status == 200){
                //     this.templateDataList = template.data;
                // }
                await axios.get('/api/template',{params:{
                    contentType:2
                }}).then(res=>{
                    if(res.status == 200){
                        this.templateDataList = res.data
                    }
                })
            }
            if(value.query.viewList){
                this.votingResult.viewList = value.query.viewList
            }
        }
    },
    created(){
        this.token = window.Laravel.csrfToken;
        
        axios.get('/api/template',{params:{
            contentType:2
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
    data(){
        return{
            votingResult:{
                vResult:'投票后可见',
                viewList:[],
                vScope:'',
                maxVote:1,
                deadline:'',
                anonyVote:true,
                content:{
                    votingDataArr:[],
                },
                
            },
            votingDataArr:[],
            templateData:{
                templateName:'',
                imgUrl:'',
                contentType:2,
                templateType:1,
                content:{
                    votingDataArr:[],
                }
            },
            templateDataList:[],
            templateContent:null,
            count:4,
            token:'',
            contentCnt:3,
            templateCnt:0,
            draftCnt:0,
            isLoading:false,
            isDrafting:false,
            isEditing:false,
        }
    },
    methods:{
        visible($event){
            this.votingResult.vResult = $event;
        },
        chooseMaxVote($event){
            this.votingResult.maxVote = $event;
        },
        addContent(){
            this.count += 1
        },
        singleContentData(value){
            let index = this.votingDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1){
                this.votingDataArr.push(value);
            }else{
                this.votingDataArr[index] = value;
            }
        },
        async addVoting(){
            if(!(this.votingResult.viewList && this.votingResult.viewList.length)){
                return this.error('调查范围不能为空');
            }
            if(this.votingResult.deadline == ''){
                return this.error("截止时间不能为空")
            }
            let found = this.votingDataArr.find(function(el){
                return el.title == ''
            })
            if(this.votingDataArr.length<3 || found != undefined){
                return this.error('投票内容不能为空')
            }
            this.votingResult.content.votingDataArr[0] = this.votingDataArr
            this.isLoading = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.votingResult,userId:userId,contentType:2})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                console.log(res.data)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.swr();
            }
            this.isLoading = false;
        },
        async addVotingDraft(){
            if(this.votingResult.deadline == ''){
                return this.error("截止时间不能为空")
            }
            let found = this.votingDataArr.find(function(el){
                return el.title == ''
            })
            if(this.votingDataArr.length<3 || found != undefined){
                return this.error('投票内容不能为空')
            }
            this.votingResult.content.votingDataArr[0] = this.votingDataArr
            this.isDrafting = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/template',{content:this.votingResult.content,userId:userId,contentType:2,templateType:2})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push(this.$route.path)

            }else{
                this.swr();
            }
            this.isDrafting = false;
        },
        handleSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.templateData.imgUrl = res;
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
        addTemplateContent(){
            this.contentCnt +=1
        },
        async addVotingTemplate(){
            if(this.templateData.templateName.trim().length == 0){
                return this.error('模板名称不能为空')
            }
            if(this.templateData.imgUrl.trim().length == 0){
                return this.error('模板封面不能为空')
            }
            let found = this.votingDataArr.find(function(el){
                return el.title == ''
            })
            if(this.votingDataArr.length<3 || found != undefined){
                return this.error('投票内容不能为空')
            }
            this.templateData.content.votingDataArr.push(this.votingDataArr)
            console.log(this.templateData)
            this.isLoading = true;
            const res = await this.callApi('post','api/template',this.templateData)
            if(res.status == 201){
                this.success('ok')
                this.$router.push(`${this.$route.path}?questionType=投票&addQuestion=应用模板`)
                this.addData = [];
                
            }
            this.isLoading = false;
        },
        editTemplate(){
            this.isEditing = !this.isEditing
        },
        removeTemplate(data){

        },

    }
}
</script>