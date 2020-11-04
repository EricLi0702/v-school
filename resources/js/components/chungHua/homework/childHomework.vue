<template>
    <div>
        <div v-if="currentPath.query.correctAnswer == undefined">
            <div class="category-title"></div>
            <div class="es-item">
                <div class="es-item-left">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="questionType($event)">
                        <a href="javascript:void(0)">
                            <div>{{addData.selQuestion}}<Icon type="ios-arrow-down" /></div>
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="单选题">单选题</DropdownItem>
                            <DropdownItem name="多选题">多选题</DropdownItem>
                            <DropdownItem name="填空题">填空题</DropdownItem>
                            <DropdownItem name="解答题">解答题</DropdownItem>
                            <DropdownItem name="判断题">判断题</DropdownItem>
                            <DropdownItem name="综合题">综合题</DropdownItem>
                            <DropdownItem name="连线题">连线题</DropdownItem>
                            <DropdownItem name="文字排序题">文字排序题</DropdownItem>
                            <DropdownItem name="图片排序题">图片排序题</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="levelType($event)" v-if="difficultlySetting == false">
                        <a href="javascript:void(0)">
                            <div>{{addData.selLevel}}<Icon type="ios-arrow-down" /></div>
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="不限难度">不限难度</DropdownItem>
                            <DropdownItem name="简单">简单</DropdownItem>
                            <DropdownItem name="一般">一般</DropdownItem>
                            <DropdownItem name="困难">困难</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
                <div class="es-item-right">
                <span class="text-color" @click="removeQuestionData">删除</span> 
                </div>
            </div>
            <div>
                <vue-editor v-model="addData.contentData" placeholder="题干内容" :editor-toolbar="customToolbar"></vue-editor>
            </div>               
            <div class="p-2">
                <div v-if="addData.selQuestion == '单选题' || addData.selQuestion == '多选题' || addData.selQuestion == '填空题' || addData.selQuestion == '解答题' || addData.selQuestion == '判断题' || addData.selQuestion == '文字排序题' ">
                    <div class="es-item" v-if="addData.selQuestion == '单选题' || addData.selQuestion == '多选题' || addData.selQuestion == '填空题' || addData.selQuestion == '文字排序题'">
                        <div class="es-item-left">
                            选项
                        </div>
                        <div class="es-item-right text-color" @click="addQuestion()">
                            添加选项 
                        </div>
                    </div>
                    <div v-for="(el,i) in addData.questionDataArr" :key="i">
                        <editorComponent :element="el" :index="i" @removeItem="removeQuestion"></editorComponent>
                    </div>
                    <div class="es-item" v-if="addData.selQuestion == '单选题'">
                        <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'单选题'}}">
                            <div class="es-item-left">
                                答案：{{addData.answerData}}<Icon type="ios-arrow-forward" />
                            </div>
                        </router-link>
                    </div>
                    <div v-else-if="selQuestion == '多选题'">
                        <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'多选题'}}">
                            <div class="es-item-left">
                                答案：{{addData.answerDataArr}}<Icon type="ios-arrow-forward" />
                            </div>
                        </router-link>
                    </div>
                    <div class="es-item" v-else-if="addData.selQuestion == '判断题'">
                        <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'判断题'}}">
                            <div class="es-item-left">
                                答案：
                                <span v-if="addData.answerData == 'A'">正确</span> 
                                <span v-if="addData.answerData == 'B'">错误</span> 
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </router-link>
                    </div>
                </div>
                <div v-else-if="addData.selQuestion == '综合题'">
                    <!-- <div v-for=" count in questionCnt" :key="count">
                        <comprehensionQuestion></comprehensionQuestion>
                    </div>
                    <div class="category-title text-center text-color" @click="addComprehension">添加小题</div> -->
                </div>
                <div v-else-if="addData.selQuestion == '连线题'">
                    <div class="es-item">
                        <div class="es-item-left">
                            选项
                        </div>
                        <div class="es-item-right text-color" @click="addQuestion()">
                            添加选项 
                        </div>
                    </div>
                    <div v-for="(el,i) in addData.questionDataArr" :key="i">
                        <connectionQuestion :element="el" :index="i" @removeItem="removeQuestion"></connectionQuestion>
                    </div>
                    
                </div>
                <div v-else-if="addData.selQuestion == '图片排序题'">
                    <Upload
                        multiple
                        type="drag"
                        :on-success="handleSuccess"
                        action="/api/fileUpload/other">
                        <div style="padding: 20px 0">
                            点击添加图片
                        </div>
                    </Upload>
                </div>
                <div v-if="addData.selQuestion != '综合题'">
                    <div class="es-item">
                        <div class="es-item-left w-100" @click="addData.showAnalysis = !showAnalysis">
                            <div>解析：</div>
                            <div v-html="addData.analysisData" v-if="addData.analysisData"></div>
                            <div v-else>请输入解析内容</div>
                        </div>
                    </div>
                    <div class="homework-editor">
                        <vue-editor v-model="addData.analysisData" placeholder="请输入解析内容" v-if="addData.showAnalysis" :editor-toolbar="customToolbar"></vue-editor>
                    </div>
                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="addTitle">添加题目</Button>
                <Button type="primary" @click="submit">直接提交</Button>
                <Button type="default" @click="viewCategory">查看列表</Button>
                <Button type="default" @click="saveDraft">存为草稿</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.correctAnswer == '单选题'">
            <RadioGroup v-model="addData.answerData">
                <div class="es-item" v-for="count in addData.questionDataArr.length" :key="count">
                    <div class="es-item-left">
                        <Radio :label="alphabet[count-1]">{{alphabet[count-1]}}</Radio>
                    </div>
                    <div class="es-item-righ">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </RadioGroup>
            <div class="es-model-operate">
                <Button type="primary" @click="selAnswer">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.correctAnswer == '多选题'">
            <CheckboxGroup v-model="answerDataArr">
                <div class="es-item" v-for="count in addData.questionDataArr.length" :key="count">
                    <div class="es-item-left">
                        <Checkbox :label="alphabet[count-1]">{{alphabet[count-1]}}</Checkbox>
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </CheckboxGroup>
            <div class="es-model-operate">
                <Button type="primary" @click="selAnswer">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.correctAnswer == '判断题'">
            <RadioGroup v-model="answerData">
                <div class="es-item">
                    <div class="es-item-left">
                        <Radio label="A">正确</Radio>
                    </div>
                    <div class="es-item-righ">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Radio label="B">错误</Radio>
                    </div>
                    <div class="es-item-righ">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </RadioGroup>
            <div class="es-model-operate">
                <Button type="primary" @click="selAnswer">提交</Button>
            </div>
        </div>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
import editorComponent from './editorComponent'
import connectionQuestion from './connectionQuestion'
export default {
    components:{
        VueEditor,
        editorComponent,
        connectionQuestion,
    },
    props:['difficultlySetting','index'],
    data(){
        return{
            customToolbar:[
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["image", "code-block"]
            ],
            addData:{
                selLevel:'难度',
                contentData:'',
                questionDataArr:[],
                showAnalysis:false,
                analysisData:'',
                selQuestion:'解答题',
                answerData:'A',
                answerDataArr:['A'],
            },
            
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods:{
        questionType($event){
            this.selQuestion = $event;
            this.addData.questionDataArr = [];
            this.answerData = 'A'
            this.answerDataArr = ['A']
            this.analysisData = ''
            this.addQuestion()
            if(this.selQuestion == '连线题'){
                this.addQuestion()
            }
        },
        addQuestion(){
            if(this.selQuestion == '单选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '单选题'
                this.questionDataArr.push(element)
            }else if(this.selQuestion == '多选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '多选题'
                this.questionDataArr.push(element)
            }
            else if(this.selQuestion == '填空题'){
               let element = {}
               element.title = '请设置答案（必填）'
               element.type = '填空题'
               this.questionDataArr.push(element)
            }
            else if(this.selQuestion == '连线题'){
                let element = {
                    title:'',
                    type:'',
                    first:{
                        val:'',
                        key:''
                    },
                    second:{
                        val:'',
                        key:''
                    }
                }
                element.title = '选项内容'
                element.type = '连线题'
                this.questionDataArr.push(element)
            }else if(this.selQuestion == '文字排序题'){
                let element = {
                    title:'',
                    type:'',
                    contentData:{
                        val:'',
                        key:''
                    }
                }
                element.title = '请输入选项内容(必填)'
                element.type = '文字排序题'
                this.questionDataArr.push(element)
            }
        },
        removeQuestion(item){
            console.log(item)
            this.addData.questionDataArr.splice(item.index,1)
        },
        levelType($event){
            this.addData.selLevel = $event;
        },
        removeQuestionData(){
            console.log('removeQuestionData')
            this.$set(this.addData,'index',this.index)
            this.$emit('removeQuestionData',this.addData)
        },
        selAnswer(){
            this.$router.push({path:this.currentPath.path,query:{questionType:'习题'}})
        },
        async submit(){
            let element = {}

            if(this.selQuestion == '连线题'){
                for(let i=0;i<this.questionDataArr.length;i++){
                    this.questionDataArr[i].first.key = i;
                    this.questionDataArr[i].second.key = i;
                }
                for(let i=0;i<this.questionDataArr.length;i++){
                    let indexA = Math.floor(Math.random() * this.questionDataArr.length);
                    let indexB = Math.floor(Math.random() * this.questionDataArr.length);
                    if( i % 2 ==0){
                        this.swapFirst(this.questionDataArr,indexA,indexB)
                    }else{
                        this.swapSecond(this.questionDataArr,indexA,indexB)
                    }
                }
            }

            if(this.selQuestion == '文字排序题'){
                for(let i=0;i<this.questionDataArr.length;i++){
                    this.questionDataArr[i].contentData.key=i
                }
                for(let i=0;i<this.questionDataArr.length;i++){
                    let indexA = Math.floor(Math.random() * this.questionDataArr.length);
                    let indexB = Math.floor(Math.random() * this.questionDataArr.length);
                    this.swap(this.questionDataArr,indexA,indexB)
                }
                // element.answerData = this.questionDataArr
            }
            // console.log(this.questionDataArr)
            // return
            element.questionDataArr = this.questionDataArr;
            element.selQuestion = this.selQuestion
            if(this.selQuestion == '单选题' || this.selQuestion == '判断题'){
                element.answerData = this.answerData
            }else if(this.selQuestion == '多选题'){
                element.answerData = this.answerDataArr
            }
            if(this.selQuestion == '判断题'){
                let el = {}
                el.contentData = '正确'
                element.questionDataArr.push(el)
                el.contentData = '错误'
                element.questionDataArr.push(el)
            }
            element.analysisData = this.analysisData
            this.addData.questionDataArr.push(element)
            console.log(this.addData);
            // return
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:20})
            console.log(res)
            if(res.status == 201){
                 this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})
            }else{
                this.swr()
            }
        },
        swapFirst(list,indexA,indexB){
            let tempA = list[indexA].first
            list[indexA].first = list[indexB].first
            list[indexB].first = tempA
        },
        swapSecond(list,indexA,indexB){
            let tempB = list[indexB].second
            list[indexB].second = list[indexA].second
            list[indexA].second = tempB
        },
        swap(list,indexA,indexB){
            let temp = list[indexB].contentData;
            list[indexB].contentData = list[indexA].contentData;
            list[indexA].contentData =temp
        },
        addTitle(){
            console.log('title')
        },
        saveDraft(){
            console.log('saveDraft')
        },
        viewCategory(){
            console.log('viewCategory')
        },
        handleSuccess(res){
            console.log(res)
        },
    }
}
</script>

<style>

</style>