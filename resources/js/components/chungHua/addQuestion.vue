<template>
    <div>
        <div v-if="currentPath.query.template == undefined">
            <div v-if="currentPath.query.addQuestion == '调查范围'">
                <Menu>
                    <Submenu name="1">
                        <template slot="title">
                            <Checkbox>
                                内容管理
                            </Checkbox>
                        </template>
                        <MenuItem name="1-1"><Checkbox>文章管理</Checkbox></MenuItem>
                    </Submenu>
                </Menu>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '应用模板'">
                <div class="apps-template">
                    <div class="template-item" v-if="templateData.length">
                        <img src="" alt="" class="picture">
                        <p class="text"></p>
                    </div>
                    <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=add`">
                        <div class="template-item-add">
                            <Icon type="ios-add" size="120" color="#DEDEDE"/>
                        </div>
                    </router-link>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '单选题'">
                 <div v-for="index1 in count1" :key="index1">
                    <contentComponent></contentComponent>
                </div>
                <div class="es-item" @click="addContent1">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>添加选项</span>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '多选题'">
                 <div v-for="index2 in count2" :key="index2">
                    <contentComponent></contentComponent>
                </div>
                <div class="es-item" @click="addContent2">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>添加选项</span>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '问答题'">
                <contentComponent></contentComponent>
                <div class="es-model-operate">
                    <Button type="primary">提交</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '统计题'">
                <contentComponent></contentComponent>
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
                        <Button type="primary">提交</Button>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.addQuestion == '评分题'">
                 <contentComponent></contentComponent>
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
                        <Button type="primary">提交</Button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else-if="currentPath.query.template == 'add'">
            <div>
                <div class="es-item">
                    <div class="es-item-left">
                        模板名称
                    </div>
                    <div class="es-item-right">
                        <Input v-model="addData.templateName" class="rightToLeft" maxlength="11" placeholder="选填" style="width: 200px" />
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
                        <img :src="addData.imgUrl" alt="" style="width:40px;height:30px" v-if="addData.imgUrl">
                        <span v-else>必填</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title"></div>
                <div class="es-item">
                    <Input v-model="addData.title" class="customInput w-100" placeholder="标题"/>
                </div>
                <div class="es-item">
                    <Input v-model="addData.description" class="customInput w-100" placeholder="说明（选填）"/>
                </div>
                <div class="category-title"></div>
                <div v-if="``"></div>
                <div class="category-title"></div>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=单选题`">
                    <div class="es-item">
                        <div class="es-item-left">
                            <Icon type="ios-add"/>
                            <span>单选题</span>
                        </div>
                        <div class="es-item-right"></div>
                    </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=多选题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>多选题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=问答题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>问答题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=统计题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>统计题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <router-link :to="`${currentPath.path}?questionType=问卷&addQuestion=应用模板&template=评分题`">
                <div class="es-item">
                    <div class="es-item-left">
                        <Icon type="ios-add"/>
                        <span>评分题</span>
                    </div>
                    <div class="es-item-right"></div>
                </div>
                </router-link>
                <div class="es-model-operate">
                    <Button type="primary">提交</Button>
                </div>
            </div>
            
        </div>
        <div id="单选题" v-else-if="currentPath.query.template == '单选题'">
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
        <div id="多选题" v-else-if="currentPath.query.template == '多选题'">
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
        <div id="问答题" v-else-if="currentPath.query.template == '问答题'">
            <contentComponent
                :index="'1'"
                :contentType="'questionAnswer'"
                @contentData="qaContentData"
            ></contentComponent>
            <div class="es-model-operate">
                <Button type="primary" @click="questionAnswer" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div id="统计题" v-else-if="currentPath.query.template == '统计题'">
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
        <div id="评分题" v-else-if="currentPath.query.template == '评分题'">
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
</template>

<script>
import contentComponent from './contentComponent'
export default {
    components:{
        contentComponent,
    },
    data(){
        return{
            templateData:[],
            token:'',
            addData:{
                templateName:'',
                imgUrl:'',
                title:'',
                description:'',
                singleContentDataArr:[],
                multiContentDataArr:[],
                questionAnswerDataArr:[],
                statisticsDataArr:[],
                scoringQuestoinsDataArr:[],
            },
            count1:4,
            count2:4,
            from:'',
            to:'',
            unit:'',
            maxMinute:2,
            singleContentDataArr:[],
            multiContentDataArr:[],
            questionAnswerDataArr:[],
            statisticsDataArr:[],
            scoringQuestoinsDataArr:[],
            isLoading:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    async created(){
        this.token = window.Laravel.csrfToken;
        const lesson = await this.callApi('get','/api/surveyLesson')
        if(lesson.status == 200){
            console.log('!!!!!!!!!',lesson.data);
        }
    },
    mounted(){
        console.log('@@@@@@@',this.templateData)
    },
    methods:{
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
            console.log('multiContentDataArr',this.multiContentDataArr);
        },
        qaContentData(value){
            let index = this.questionAnswerDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1)
                this.questionAnswerDataArr.push(value);
            else
                this.questionAnswerDataArr[index] = value;
            console.log('questionAnswerData',this.questionAnswerDataArr)
        },
        stContentData(value){
            let index = this.statisticsDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1)
                this.statisticsDataArr.push(value);
            else
                this.statisticsDataArr[index] = value
            console.log('statisticsData',this.statisticsDataArr)
        },
        sqContentData(value){
            let index = this.scoringQuestoinsDataArr.findIndex((el)=>
                el.index == value.index
            )
            if(index == -1)
                this.scoringQuestoinsDataArr.push(value)
            else
                this.scoringQuestoinsDataArr[index] = value
            console.log('scoringQuestions',this.scoringQuestoinsDataArr)
        },
        addContent1(){
            this.count1 += 1;
        },
        addContent2(){
            this.count2 += 1;
        },
        visible($event){
            this.maxMinute = $event;
        },
        handleSuccess (res, file) {
            res = `/uploads/image/${res}`
            this.addData.imgUrl = res;
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
        async singleSelect(){
            console.log('single select');
            let found = this.singleContentDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true
            if(this.singleContentDataArr.length<3 || found != undefined){
                this.error('标题不能为空')
            }else{
                const res = await this.callApi('post','/api/templateContent',{type:'1',contentData:this.singleContentDataArr})
                if(res.status == 201){
                    this.success('ok')
                    this.addData.singleContentDataArr.push(this.singleContentDataArr)
                    this.singleContentDataArr = [];
                    this.$router.push(`${this.$route.path}?questionType=问卷&addQuestion=应用模板&template=add`)
                    console.log(res.data)
                }else if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            this.isLoading = false
        },
        async multiSelect(){
            console.log('multiSelect');
            let found = this.multiContentDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true;
            if(this.multiContentDataArr.length < 3 || found != undefined){
                this.error('标题不能为空')
            }else{
                const res = await this.callApi('post','/api/templateContent',{type:'2',contentData:this.multiContentDataArr})
                if(res.status == 201){
                    this.success('ok')
                    console.log(res.data)
                    this.addData.multiContentDataArr.push(this.multiContentDataArr)
                    this.multiContentDataArr = [];
                    this.$router.push(`${this.$route.path}?questionType=问卷&addQuestion=应用模板&template=add`)
                }else if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            this.isLoading = false;
        },
        async questionAnswer(){
            console.log('questionAnswer');
            let found = this.questionAnswerDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true;
            if(this.questionAnswerDataArr.length < 1 || found != undefined){
                this.error('标题不能为空')
            }else{
                const res = await this.callApi('post','/api/templateContent',{type:'3',contentData:this.questionAnswerDataArr})
                if(res.status == 201){
                    this.success('ok')
                    console.log(res.data)
                    this.addData.questionAnswerDataArr.push(this.questionAnswerDataArr)
                    this.questionAnswerDataArr = [];
                    this.$router.push(`${this.$route.path}?questionType=问卷&addQuestion=应用模板&template=add`)
                }else if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            this.isLoading = false;
        },
        async statistics(){
            console.log('statistics');
            console.log('111',this.from,this.to,this.unit)
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
                this.$set(this.statisticsDataArr,'from',this.from)
                this.$set(this.statisticsDataArr,'to',this.to)
                this.$set(this.statisticsDataArr,'unit',this.unit)
                const res = await this.callApi('post','/api/templateContent',{type:'4',contentData:this.statisticsDataArr})
                if(res.status == 201){
                    this.success('ok')
                    console.log(res.data)
                    this.addData.statisticsDataArr.push(this.statisticsDataArr)
                    this.statisticsDataArr = [];
                    this.$router.push(`${this.$route.path}?questionType=问卷&addQuestion=应用模板&template=add`)
                }else if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            this.isLoading = false;
        },
        async scoringQuestions(){
            console.log('scoringQuestions');
            let found = this.scoringQuestoinsDataArr.find(function(el){
                return el.title == ''
            })
            this.isLoading = true;
            if(this.scoringQuestoinsDataArr.length < 1 || found != undefined){
                this.error('标题不能为空')
            }else{
                this.$set(this.scoringQuestoinsDataArr,'maxMinute',this.maxMinute)
                const res = await this.callApi('post','/api/templateContent',{type:'5',contentData:this.scoringQuestoinsDataArr})
                if(res.status == 201){
                    this.success('ok')
                    console.log(res.data)
                    this.addData.scoringQuestoinsDataArr.push(this.scoringQuestoinsDataArr)
                    this.scoringQuestoinsDataArr = [];
                    this.$router.push(`${this.$route.path}?questionType=问卷&addQuestion=应用模板&template=add`)
                }else if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            this.isLoading = false;
        }
    }
}
</script>