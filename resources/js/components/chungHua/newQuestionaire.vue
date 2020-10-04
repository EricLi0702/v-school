<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == undefined">
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板`">
                    <div class="category-title template">
                        <Icon type="ios-list-box-outline" />
                        <span>可用模板{{templateCnt}}，草稿{{draftCnt}}</span>
                    </div>
                </router-link>
                <div class="questionaire-create">
                    <div class="es-item">
                        <div class="es-item-left w-100">
                            <Input v-model="addData.title" class="customInput" placeholder="标题"/>
                        </div>
                        <div class="es-item-right"></div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left w-100">
                            <Input v-model="addData.description" class="customInput" placeholder="说明（选填）"/>
                        </div>
                        <div class="es-item-right"></div>
                    </div>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=调查范围`">
                        <div class="es-item">
                            <div class="es-item-left">
                                调查范围
                            </div>
                            <div class="es-item-right">
                                <span v-if="addData.viewList && addData.viewList.length > 1">{{addData.viewList.length}}个群组</span>
                                <span v-else>必填</span>
                                <Icon type="ios-arrow-forward" /> 
                            </div>
                        </div>
                    </router-link>
                    <div class="es-item">
                        <div class="es-item-left">
                            截止时间
                        </div>
                        <div class="es-item-right">
                            <DatePicker type="datetime" :options="options"  v-model="addData.deadline" placeholder="选填" ></DatePicker>
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            匿名问卷
                        </div>
                        <div class="es-item-right">
                            <i-switch true-color="#13ce66" v-model="addData.questionnaireFlag" />
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            答卷人可见结果
                        </div>
                        <div class="es-item-right">
                            <i-switch true-color="#13ce66" v-model="addData.resultFlag" />
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-left">
                            外部人员可作答
                        </div>
                        <div class="es-item-right">
                            <i-switch true-color="#13ce66" v-model="addData.answerFlag" />
                        </div>
                    </div>
                    <div id="templateData" v-if="addData.content">
                        <div id="singleContentDataArr" v-if="addData.content.singleContentDataArr.length">
                            <questionItemComponent
                                :addData="addData.content.singleContentDataArr"
                                :type="'单选题'"
                            ></questionItemComponent>
                        </div>
                        <div id="multiContentDataArr" v-if="addData.content.multiContentDataArr.length">
                            <questionItemComponent
                                :addData="addData.content.multiContentDataArr"
                                :type="'多选题'"
                            ></questionItemComponent>
                        </div>
                        <div id="questionAnswerDataArr" v-if="addData.content.questionAnswerDataArr.length">
                            <questionItemComponent
                                :addData="addData.content.questionAnswerDataArr"
                                :type="'问答题'"
                            ></questionItemComponent>
                        </div>
                        <div id="statisticsDataArr" v-if="addData.content.statisticsDataArr.length">
                            <questionItemComponent
                                :addData="addData.content.statisticsDataArr"
                                :type="'统计题'"
                            ></questionItemComponent>
                        </div>
                        <div id="scoringQuestoinsDataArr" v-if="addData.content.scoringQuestoinsDataArr.length">
                            <questionItemComponent
                                :addData="addData.content.scoringQuestoinsDataArr"
                                :type="'评分题'"
                            ></questionItemComponent>
                        </div>
                    </div>
                    <div class="category-title"></div>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=单选题`">
                        <div class="es-item">
                            <div class="es-item-left">
                                <Icon type="ios-add" />
                                <span>单选题</span>
                            </div>
                            <div class="es-item-right"></div>
                        </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=多选题`">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Icon type="ios-add" />
                            <span>多选题</span>
                        </div>
                        <div class="es-item-right"></div>
                    </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=问答题`">
                        <div class="es-item">
                            <div class="es-item-left">
                                <Icon type="ios-add" />
                                <span>问答题</span>
                            </div>
                            <div class="es-item-right"></div>
                        </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=统计题`">
                        <div class="es-item">
                            <div class="es-item-left">
                                <Icon type="ios-add" />
                                <span>统计题</span>
                            </div>
                            <div class="es-item-right"></div>
                        </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=评分题`">
                        <div class="es-item">
                            <div class="es-item-left">
                                <Icon type="ios-add" />
                                <span>评分题</span>
                            </div>
                            <div class="es-item-right"></div>
                        </div>
                    </router-link>
                    <div class="es-model-operate">
                        <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
                        <Button type="default" @click="draft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button>
                    </div>
                </div>
            </div>
            <div v-if="currentPath.query.addQuestion == '应用模板'">
                <div class="apps-template">
                    <div  v-if="templateDataList.length">
                        <div class="template-item" v-for="(template ,i) in templateDataList" :key="i">
                            <router-link :to="{path:`${currentPath.path}?questionType=问卷`,query:{myprop:template}}">
                                <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                                <img :src="template.imgUrl" alt="" class="picture" v-if="template.imgUrl">
                                <img src="/img/icon/33.jpg" alt="" class="picture" v-else>
                                <p class="text" v-if="template.templateName">{{template.templateName}}</p>
                                <p class="text" v-else>draft</p>
                            </router-link>
                        </div>
                    </div>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=add`">
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
                <schoolList :type="'问卷'"></schoolList>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '单选题'">
                <div v-for="index1 in count1" :key="index1">
                    <contentComponent
                        :index="index1"
                        :contentType="'singleContent'"
                        @contentData="singleContentData"
                    ></contentComponent>
                </div>
                <div class="es-item" @click="addContent1">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>添加选项</span>
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="singleSelect" :disabled="isLoading" :loading="isLoading">提交</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '多选题'">
                <div v-for="index2 in count2" :key="index2">
                    <contentComponent
                        :index="index2"
                        :contentType="'multiContent'"
                        @contentData="multiContentData"
                    ></contentComponent>
                </div>
                <div class="es-item" @click="addContent2">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>添加选项</span>
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="multiSelect" :disabled="isLoading" :loading="isLoading">提交</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '问答题'">
                <contentComponent
                    :index="'1'"
                    :contentType="'questionAnswer'"
                    @contentData="qaContentData"
                ></contentComponent>
                <div class="es-model-operate">
                    <Button type="primary" @click="questionAnswer" :disabled="isLoading" :loading="isLoading">提交</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '统计题'">
                <contentComponent
                    :index="'1'"
                    :contentType="'statistics'"
                    @contentData="stContentData"
                ></contentComponent>
                <div>
                    <div class="es-item">
                        <div class="es-item-left">
                            数字范围
                        </div>
                        <div class="es-item-right">
                            <Input v-model="from" class="customInput" style="width:50px;" placeholder="起始值"/>至 
                            <Input v-model="to" class="customInput" style="width:50px;" placeholder="结束值"/>
                        </div>
                    </div>
                    <div class="es-item">
                        <div class="es-item-lef">
                            单位
                        </div>
                        <div class="es-item-right">
                            <Input v-model="unit" class="customInput rightToLeft" placeholder="必填"/>
                        </div>
                    </div>
                    <div class="es-model-operate">
                        <Button type="primary" @click="statistics" :disabled="isLoading" :loading="isLoading">提交</Button>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '评分题'">
                <contentComponent
                    :index="'1'"
                    :contentType="'scoringQuestions'"
                    @contentData="sqContentData"
                ></contentComponent>
                <div>
                    <div class="es-item">
                        <div class="es-item-left">
                            最高分
                        </div>
                        <div class="es-item-right">
                            <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="visible($event)">
                                <a href="javascript:void(0)">
                                    {{maxMinute}}
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
                        <Button type="primary" @click="scoringQuestions" :disabled="isLoading" :loading="isLoading">提交</Button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <appTemplate></appTemplate>
        </div>
    </div>
</template>

<script>
import appTemplate from './appTemplate'
import questionItemComponent from './questionItemComponent'
import contentComponent from './contentComponent'
import schoolList from './schoolList'
export default {
    components:{
        appTemplate,
        questionItemComponent,
        contentComponent,
        schoolList
    },
    props:['myprop'],
    data(){
        return{
            addData:{
                title:'',
                description:'',
                viewList:[],
                deadline:'',
                questionnaireFlag:true,
                resultFlag:true,
                answerFlag:false,
                type:0,
                content:{
                    singleContentDataArr:[],
                    multiContentDataArr:[],
                    questionAnswerDataArr:[],
                    statisticsDataArr:[],
                    scoringQuestoinsDataArr:[],
                },
            },
            count1:4,
            count2:4,
            maxMinute:2,
            templateCnt:0,
            draftCnt:0,
            options: {
                disabledDate (date) {
                    return date && date.valueOf() < Date.now() - 86400000;
                }
            },
            templateDataList:[],
            tmeplateData:{},
            templateContent:{},
            isLoading:false,
            isDrafting:false,
            singleContentDataArr:[],
            multiContentDataArr:[],
            questionAnswerDataArr:[],
            statisticsDataArr:[],
            scoringQuestoinsDataArr:[],
            lessonList:[],
            isEditing:false,
            from:'',
            to:'',
            unit:'',

        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    watch:{
        async currentPath(value){
            if(value.query.myprop){
                this.templateData = value.query.myprop
                // this.addData = this.templateData
                this.addData.title = this.templateData.title
                this.addData.description = this.templateData.description
                if(typeof this.templateData.content == "string"){
                    this.templateContent = JSON.parse(this.templateData.content)
                }else{
                    this.templateContent = this.templateData.content
                }
                this.addData.content.singleContentDataArr = this.templateContent.singleContentDataArr
                this.addData.content.multiContentDataArr = this.templateContent.multiContentDataArr
                this.addData.content.questionAnswerDataArr = this.templateContent.questionAnswerDataArr
                this.addData.content.statisticsDataArr = this.templateContent.statisticsDataArr
                this.addData.content.scoringQuestoinsDataArr = this.templateContent.scoringQuestoinsDataArr
            }
            if(value.query.addQuestion == '应用模板'){
                await axios.get('/api/template',{params:{
                    contentType:1
                }}).then(res=>{
                    if(res.status == 200){
                        this.templateDataList = res.data
                    }
                })
            }
            if(value.query.viewList){
                this.addData.viewList = value.query.viewList;
            }
        }
    },
    async created(){
        axios.get('/api/template',{params:{
            contentType:1
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
        const lesson = await this.callApi('get','/api/surveyLesson')
        if(lesson.status == 200){
            this.lessonList = lesson.data;
        }
    },
    methods:{
        addContent1(){
            this.count1 += 1;
        },
        addContent2(){
            this.count2 += 1;
        },
        singleContentData(value){
            let index = this.singleContentDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1){
                this.singleContentDataArr.push(value);
            }else{
                this.singleContentDataArr[index] = value;
            } 
        },
        multiContentData(value){
            let index = this.multiContentDataArr.findIndex((el)=>
               el.index == value.index
            )
            if(index == -1){
                this.multiContentDataArr.push(value);
            }else{
                this.multiContentDataArr[index] = value;
            } 
        },
        qaContentData(value){
            let index = this.questionAnswerDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1)
                this.questionAnswerDataArr.push(value);
            else
                this.questionAnswerDataArr[index] = value;
        },
        stContentData(value){
            let index = this.statisticsDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1)
                this.statisticsDataArr.push(value);
            else
                this.statisticsDataArr[index] = value
        },
        sqContentData(value){
            let index = this.scoringQuestoinsDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1)
                this.scoringQuestoinsDataArr.push(value)
            else
                this.scoringQuestoinsDataArr[index] = value
        },
        singleSelect(){
            let found = this.singleContentDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true
            if(this.singleContentDataArr.length<3 || found != undefined){
                this.error('标题不能为空')
            }else{
                this.addData.content.singleContentDataArr.push(this.singleContentDataArr)
                this.singleContentDataArr = [];
                this.$router.push(`${this.$route.path}?questionType=问卷`)
            }
            this.isLoading = false
        },
        multiSelect(){
            let found = this.multiContentDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true;
            if(this.multiContentDataArr.length < 3 || found != undefined){
                this.error('标题不能为空')
            }else{
                this.addData.content.multiContentDataArr.push(this.multiContentDataArr)
                this.multiContentDataArr = [];
                this.$router.push(`${this.$route.path}?questionType=问卷`)
            }
            this.isLoading = false;
        },
        questionAnswer(){
            let found = this.questionAnswerDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true;
            if(this.questionAnswerDataArr.length < 1 || found != undefined){
                this.error('标题不能为空')
            }else{
                this.addData.content.questionAnswerDataArr.push(this.questionAnswerDataArr)
                this.questionAnswerDataArr = [];
                this.$router.push(`${this.$route.path}?questionType=问卷`)
            }
            this.isLoading = false;
        },
        statistics(){
            if(this.from == '' || this.to == '' || this.uint == ''){
                this.error('标题不能为空')
                return
            }
            this.isLoading = true;
            let found = this.statisticsDataArr.find(function(el){
                return el.title == ''
            })
            if(this.statisticsDataArr.length < 1 || found != undefined){
                this.error('标题不能为空')
            }else{
                this.$set(this.statisticsDataArr[0],'from',this.from)
                this.$set(this.statisticsDataArr[0],'to',this.to)
                this.$set(this.statisticsDataArr[0],'unit',this.unit)
                this.addData.content.statisticsDataArr.push(this.statisticsDataArr)
                this.statisticsDataArr = [];
                this.$router.push(`${this.$route.path}?questionType=问卷`)
            }
            this.isLoading = false;
        },
        visible($event){
            this.maxMinute = $event;
        },
        scoringQuestions(){
            let found = this.scoringQuestoinsDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true;
            if(this.scoringQuestoinsDataArr.length < 1 || found != undefined){
                this.error('标题不能为空')
            }else{
                this.$set(this.scoringQuestoinsDataArr[0],'maxMinute',this.maxMinute)
                this.addData.content.scoringQuestoinsDataArr.push(this.scoringQuestoinsDataArr)
                this.scoringQuestoinsDataArr = [];
                this.$router.push(`${this.$route.path}?questionType=问卷`)
            }
            this.isLoading = false;
        },
        async submit(){
            if(this.addData.title == ''){
                return this.error('标题/说明至少填写一项')
            }
            if(this.addData.deadline == ''){
                return this.error('截止时间不能为空')
            }
            if(!(this.addData.viewList && this.addData.viewList.length > 1)){
                return this.error('调查范围不能为空')
            }
            
            let userId = this.$store.state.user.id
            // this.$set(this.addData,'userId',userId)
            this.isLoading = true;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:1})
            if(res.status == 201){
                this.success('ok')
                this.$store.commit('setShowQuestionModal',false);
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.swr();
            }
            this.isLoading = false;
        },
        async draft(){
            if(this.addData.title == ''){
                return this.error('标题/说明至少填写一项')
            }
            this.isDrafting = true;
            const res = await this.callApi('post','/api/template',{title:this.addData.title,description:this.addData.description,content:this.addData.content,contentType:1,templateType:2})
            if(res.status == 201){
                this.success('ok')
                this.templateDataList.push(this.addData)
                // this.addData = [];
                this.$router.push(`${this.$route.path}?questionType=问卷&addQuestion=应用模板`)
            }
            this.isDrafting = false;
        },
        editTemplate(){
            this.isEditing = !this.isEditing
        },
        removeTemplate(data){

        }
    }
}
</script>