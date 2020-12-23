<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == undefined">
                <router-link :to="`${currentPath.path}?applicationType=问卷&questionType=问卷&addQuestion=应用模板`">
                    <div class="category-title template gray-font">
                        <Icon type="ios-list-box-outline" />
                        <span>可用模板 {{templateCnt}}， 草稿 {{draftCnt}}</span>
                        <span class="right">
                            <Icon type="ios-arrow-forward" size="22" />
                        </span>
                    </div>
                </router-link>
                <div class="questionaire-create">
                    <div class="vx-item">
                        <div class="vx-item-left w-100">
                            <Input v-model="addData.title" class="customInput px-0" placeholder="标题"/>
                        </div>
                        <div class="vx-item-right"></div>
                    </div>
                    <div class="vx-item">
                        <div class="vx-item-left w-100">
                            <Input v-model="addData.description" class="customInput px-0" placeholder="说明（选填）"/>
                        </div>
                        <div class="vx-item-right"></div>
                    </div>
                    <router-link :to="`${currentPath.path}?applicationType=问卷&questionType=问卷&addQuestion=调查范围`">
                        <div class="vx-item is-click">
                            <div class="vx-item-left">
                                调查范围
                            </div>
                            <div class="vx-item-right">
                                <span v-if="addData.viewList && addData.viewList.length > 0">{{addData.viewList.length}}个群组</span>
                                <span v-else>必填</span>
                                <Icon type="ios-arrow-forward" size="22" />
                            </div>
                        </div>
                    </router-link>
                    <div class="vx-item">
                        <div class="vx-item-left">
                            截止时间
                        </div>
                        <div class="vx-item-right">
                            <DatePicker type="datetime" :options="options"  v-model="addData.deadline" placeholder="选填" ></DatePicker>
                        </div>
                    </div>
                    <div class="vx-item is-click ">
                        <div class="vx-item-left">
                            匿名问卷
                        </div>
                        <div class="vx-item-right">
                            <i-switch true-color="#13ce66" v-model="addData.questionnaireFlag" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            答卷人可见结果
                        </div>
                        <div class="vx-item-right">
                            <i-switch true-color="#13ce66" v-model="addData.resultFlag" />
                        </div>
                    </div>
                    <div class="vx-item is-click">
                        <div class="vx-item-left">
                            外部人员可作答
                        </div>
                        <div class="vx-item-right">
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
                    <router-link :to="`${currentPath.path}?editType=问卷&applicationType=问卷&questionType=问卷&addQuestion=单选题`">
                        <div class="vx-item is-click">
                            <div class="vx-item-left gray-font">
                                <Icon type="ios-add" size="25" />
                                <span>单选题</span>
                            </div>
                            <div class="vx-item-right"></div>
                        </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?editType=问卷&applicationType=问卷&questionType=问卷&addQuestion=多选题`">
                        <div class="vx-item is-click">
                            <div class="vx-item-left gray-font">
                                <Icon type="ios-add" size="25" />
                                <span>多选题</span>
                            </div>
                            <div class="vx-item-right"></div>
                        </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?editType=问卷&applicationType=问卷&questionType=问卷&addQuestion=问答题`">
                        <div class="vx-item is-click">
                            <div class="vx-item-left gray-font">
                                <Icon type="ios-add" size="25" />
                                <span>问答题</span>
                            </div>
                            <div class="vx-item-right"></div>
                        </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?editType=问卷&applicationType=问卷&questionType=问卷&addQuestion=统计题`">
                        <div class="vx-item is-click">
                            <div class="vx-item-left gray-font">
                                <Icon type="ios-add" size="25" />
                                <span>统计题</span>
                            </div>
                            <div class="vx-item-right"></div>
                        </div>
                    </router-link>
                    <router-link :to="`${currentPath.path}?editType=问卷&applicationType=问卷&questionType=问卷&addQuestion=评分题`">
                        <div class="vx-item is-click">
                            <div class="vx-item-left gray-font">
                                <Icon type="ios-add" size="25" />
                                <span>评分题</span>
                            </div>
                            <div class="vx-item-right"></div>
                        </div>
                    </router-link>
                    <div class="es-model-operate question-modal-footer-operate">
                        <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
                        <Button class="mr-2" type="default" @click="draft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button>
                    </div>
                </div>
            </div>
            <div v-if="currentPath.query.addQuestion == '应用模板'">
                <div class="apps-template px-1">
                    <div   class="row m-0 p-0">
                        <div v-if="templateDataList.length" class="template-item-cu col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 p-0 m-0" v-for="(template ,i) in templateDataList" :key="i">
                            <router-link :to="{path:`${currentPath.path}?applicationType=问卷&questionType=问卷`,query:{myprop:template}}" class="">
                                <div class="template-item-container m-2 position-relative">
                                    <Icon class="icon-close" type="ios-close" v-if="isEditing" @click="removeTemplate(template)"/>
                                    <img :src="template.imgUrl" alt="" class="picture w-100" v-if="template.imgUrl">
                                    <img src="/img/icon/33.jpg" alt="" class="picture w-100" v-else>
                                    <p class="text" v-if="template.templateName">{{template.templateName}}</p>
                                    <p class="text" v-else>draft</p>
                                </div>
                            </router-link>
                        </div>
                        <router-link :to="`${currentPath.path}?applicationType=问卷&questionType=问卷&addQuestion=应用模板&template=add`" class="template-item-add-cu col-6 col-sm-6 col-md-6 col-lg-4 col-xl-3 p-0 m-0">
                            <div class="text-center m-2 border">
                                <Icon type="ios-add" size="120" color="#DEDEDE"/>
                                <p class="pb-2">新建模板</p>
                            </div>
                        </router-link>
                    </div>
                    <div class="row m-0 p-0">
                    </div>
                </div>
                <div class="edit-btn">
                    <Button type="primary" @click="editTemplate">编辑</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '调查范围'">
                <div v-if="this.currentPath.params.className == undefined">
                    <schoolList :type="'问卷'"></schoolList>
                </div>
                <div v-else>
                    <contact3Component @selectedUser="selViewUsers"></contact3Component>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '单选题'">
                <div v-for="index1 in count1" :key="index1">
                    <contentComponent
                        :index="index1"
                        :contentType="'singleContent'"
                        @contentData="singleContentData"
                    ></contentComponent>
                </div>
                <div class="vx-item is-click" @click="addContent1">
                    <div class="vx-item-left">
                        <Icon type="ios-add" size="25" color="#999999" class="font-weight-bold"/>
                        <span >添加选项</span>
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
                <div class="vx-item is-click" @click="addContent2">
                    <div class="vx-item-left">
                        <Icon type="ios-add" size="25" color="#999999" class="font-weight-bold"/>
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
                    <div class="vx-item">
                        <div class="vx-item-left">
                            数字范围
                        </div>
                        <div class="vx-item-right">
                            <Input v-model="from" type="number"  class="customInput" style="width:60px;" placeholder="起始值"/>至 
                            <Input v-model="to" type="number" class="customInput" style="width:60px;" placeholder="结束值"/>
                        </div>
                    </div>
                    <div class="vx-item">
                        <div class="vx-item-lef">
                            单位
                        </div>
                        <div class="vx-item-right">
                            <Input v-model="unit" type="number" class="customInput rightToLeft" placeholder="必填"/>
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
                    <div class="vx-item is-click" @click="toggleOpenDropdownMenuFive">
                        <div class="vx-item-left">
                            最高分
                        </div>
                        <div class="vx-item-right">
                            <Dropdown trigger="custom" :visible="isVisibleFive" style="margin-left: 20px" placement="bottom-end" @on-click="visible($event)">
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
            <div v-else-if="currentPath.query.addQuestion == 'edit'">
                <div v-for="(editData,index1) in JSON.parse(currentPath.query.editData)[0]" :key="index1">
                    <contentComponent
                        :editData="editData"
                        :index="index1"
                        :contentType="JSON.parse(currentPath.query.editData)[0].contentType"
                        @contentData="updateQuestion"
                    ></contentComponent>
                </div>
                <div v-if="JSON.parse(currentPath.query.editData)[0][0].contentType == 'statistics'">
                    <div class="vx-item">
                        <div class="vx-item-left">
                            数字范围
                        </div>
                        <div class="vx-item-right">
                            <Input v-model="addData.content.statisticsDataArr[currentPath.query.index][0].from" type="number" class="customInput" style="width:60px;" placeholder="起始值"/>至 
                            <Input v-model="addData.content.statisticsDataArr[currentPath.query.index][0].to" type="number" class="customInput" style="width:60px;" placeholder="结束值"/>
                        </div>
                    </div>
                    <div class="vx-item">
                        <div class="vx-item-lef">
                            单位
                        </div>
                        <div class="vx-item-right">
                            <Input v-model="addData.content.statisticsDataArr[currentPath.query.index][0].unit" class="customInput rightToLeft" placeholder="必填"/>
                        </div>
                    </div>
                </div>
                <div v-if="JSON.parse(currentPath.query.editData)[0][0].contentType == 'scoringQuestions'">
                    <div class="vx-item is-click" @click="toggleOpenDropdownMenuFive">
                        <div class="vx-item-left">
                            最高分
                        </div>
                        <div class="vx-item-right">
                            <Dropdown trigger="custom" :visible="isVisibleFive" style="margin-left: 20px" placement="bottom-end" @on-click="visible($event)">
                                <a href="javascript:void(0)">
                                    <!-- {{JSON.parse(currentPath.query.editData)[0][0].maxMinute}} -->
                                    {{addData.content.scoringQuestoinsDataArr[currentPath.query.index][0].maxMinute}}
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
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="editQuestion(JSON.parse(currentPath.query.editData)[0])" :disabled="isLoading" :loading="isLoading">提交</Button>
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
import {mapGetters,mapActions} from 'vuex'
import contact3Component from './contact3Component'
export default {
    components:{
        appTemplate,
        questionItemComponent,
        contentComponent,
        schoolList,
        contact3Component
    },
    props:['myprop','contentData'],
    data(){
        return{
            addData:{
                title:'',
                description:'',
                viewList:[],
                postShow:[],
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
            isVisibleFive:false,
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
        },
        ...mapGetters([
            'getEditContentData'
        ]),
        
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
            if(value.query.addQuestion == 'edit'){
            }
        },
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

        toggleOpenDropdownMenuFive(){
            this.isVisibleFive = !this.isVisibleFive;
        },

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
                this.$router.push(`${this.$route.path}?applicationType=问卷&questionType=问卷`)
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
                this.$router.push(`${this.$route.path}?applicationType=问卷&questionType=问卷`)
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
                this.$router.push(`${this.$route.path}?applicationType=问卷&questionType=问卷`)
            }
            this.isLoading = false;
        },
        statistics(){
            if(this.from == '' || this.to == '' || this.uint == ''){
                this.error('标题不能为空')
                return
            }
            if(this.from > this.to){
                this.error('起始值不能大于结束值')
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
                this.$router.push(`${this.$route.path}?applicationType=问卷&questionType=问卷`)
            }
            this.isLoading = false;
        },
        visible($event){
            
            if(this.currentPath.query.addQuestion == 'edit'){
                this.addData.content.scoringQuestoinsDataArr[this.currentPath.query.index][0].maxMinute = $event
            }else{
                this.maxMinute = $event;
            }
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
                this.maxMinute = 2;
                this.$router.push(`${this.$route.path}?applicationType=问卷&questionType=问卷`)
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
            if(!(this.addData.viewList && this.addData.viewList.length > 0)){
                return this.error('调查范围不能为空')
            }
            
            let userId = this.$store.state.user.id
            // this.$set(this.addData,'userId',userId)
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            if(this.currentPath.params.className == undefined){
                this.addData.postShow.push(0)
                this.addData.postShow.push(1)
            }else{
                this.addData.postShow.push(this.currentPath.params.className)
                this.addData.postShow.push(2)
            }
            this.isLoading = true;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:1,foamingPosition:foamingPosition})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
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
                this.success('操作成功')
                this.templateDataList.push(this.addData)
                // this.addData = [];
                this.$router.push(`${this.$route.path}?applicationType=问卷&questionType=问卷&addQuestion=应用模板`)
            }
            this.isDrafting = false;
        },
        editTemplate(){
            this.isEditing = !this.isEditing
        },
        removeTemplate(data){

        },
        editQuestion(data){
            this.$router.push({path:this.currentPath.path,query:{applicationType:this.currentPath.query.applicationType,questionType:this.currentPath.query.questionType}})
        },
        updateQuestion(value){
            let index = this.currentPath.query.index
            if(value.contentType == "singleContent"){   
                for(let i=0;i<this.addData.content.singleContentDataArr[index].length;i++){
                    if(this.addData.content.singleContentDataArr[index][i].index == value.index){
                        this.addData.content.singleContentDataArr[index][i] = value
                    }
                }
            }else if(value.contentType == 'multiContent'){
                for(let i=0;i<this.addData.content.multiContentDataArr[index].length;i++){
                    if(this.addData.content.multiContentDataArr[index][i].index == value.index){
                        this.addData.content.multiContentDataArr[index][i] = value
                    }
                }
            }else if(value.contentType == 'questionAnswer'){
                for(let i=0;i<this.addData.content.questionAnswerDataArr[index].length;i++){
                    if(this.addData.content.questionAnswerDataArr[index][i].index == value.index){
                        this.addData.content.questionAnswerDataArr[index][i] = value
                    }
                }
            }else if(value.contentType == 'statistics'){
                for(let i=0;i<this.addData.content.statisticsDataArr[index].length;i++){
                    if(this.addData.content.statisticsDataArr[index][i].index == value.index){
                        this.addData.content.statisticsDataArr[index][i] = value
                    }
                }
            }else if(value.contentType == 'scoringQuestions'){
                for(let i=0;i<this.addData.content.scoringQuestoinsDataArr[index].length;i++){
                    if(this.addData.content.scoringQuestoinsDataArr[index][i].index == value.index){
                        this.addData.content.scoringQuestoinsDataArr[index][i] = value
                    }
                }
            }
        },
        selViewUsers(val){
            for(let i=0;i<val.length;i++){
                this.addData.viewList.push(val[i].id)
            }
        }
    }
}
</script>