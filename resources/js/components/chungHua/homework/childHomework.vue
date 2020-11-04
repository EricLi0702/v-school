<template>
    <div>
        <div v-if="currentPath.query.correctAnswer == undefined">
            <div class="category-title"></div>
            <div class="es-item">
                <div class="es-item-left">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="questionType($event)">
                        <a href="javascript:void(0)">
                            <div>{{addDataProp.selQuestion}}<Icon type="ios-arrow-down" /></div>
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
                            <div>{{addDataProp.selLevel}}<Icon type="ios-arrow-down" /></div>
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
                <vue-editor v-model="addDataProp.contentData" placeholder="题干内容" :editor-toolbar="customToolbar"></vue-editor>
            </div>               
            <div class="p-2">
                <div v-if="addDataProp.selQuestion == '单选题' || addDataProp.selQuestion == '多选题' || addDataProp.selQuestion == '填空题' || addDataProp.selQuestion == '解答题' || addDataProp.selQuestion == '判断题' || addDataProp.selQuestion == '文字排序题' ">
                    <div class="es-item" v-if="addDataProp.selQuestion == '单选题' || addDataProp.selQuestion == '多选题' || addDataProp.selQuestion == '填空题' || addDataProp.selQuestion == '文字排序题'">
                        <div class="es-item-left">
                            选项
                        </div>
                        <div class="es-item-right text-color" @click="addQuestion()">
                            添加选项 
                        </div>
                    </div>
                    <div v-for="(el,i) in addDataProp.questionDataArr" :key="i">
                        <editorComponent :element="el" :index="i" @removeItem="removeQuestion"></editorComponent>
                    </div>
                    <div class="es-item" v-if="addDataProp.selQuestion == '单选题'">
                        <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'单选题',index:index}}">
                            <div class="es-item-left">
                                答案：{{addDataProp.answerData}}<Icon type="ios-arrow-forward" />
                            </div>
                        </router-link>
                    </div>
                    <div v-else-if="addDataProp.selQuestion == '多选题'">
                        <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'多选题',index:index}}">
                            <div class="es-item-left">
                                答案：{{addDataProp.answerDataArr}}<Icon type="ios-arrow-forward" />
                            </div>
                        </router-link>
                    </div>
                    <div class="es-item" v-else-if="addDataProp.selQuestion == '判断题'">
                        <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'判断题',index:index}}">
                            <div class="es-item-left">
                                答案：
                                <span v-if="addDataProp.answerData == 'A'">正确</span> 
                                <span v-if="addDataProp.answerData == 'B'">错误</span> 
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </router-link>
                    </div>
                </div>
                <div v-else-if="addDataProp.selQuestion == '综合题'">
                    <div v-for="(propsData,i) in addDataProp.questionDataArr" :key="i">
                        <comprehensionQuestion
                            :propsData="propsData"
                            :index="i"
                            @removeQuestionData="removeQuestionData"
                        >
                        </comprehensionQuestion>
                    </div>
                    <div class="category-title text-center text-color" @click="addQuestion">添加小题</div>
                </div>
                <div v-else-if="addDataProp.selQuestion == '连线题'">
                    <div class="es-item">
                        <div class="es-item-left">
                            选项
                        </div>
                        <div class="es-item-right text-color" @click="addQuestion()">
                            添加选项 
                        </div>
                    </div>
                    <div v-for="(el,i) in addDataProp.questionDataArr" :key="i">
                        <connectionQuestion :element="el" :index="i" @removeItem="removeQuestion"></connectionQuestion>
                    </div>
                    
                </div>
                <div v-else-if="addDataProp.selQuestion == '图片排序题'">
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
                <div v-if="addDataProp.selQuestion != '综合题'">
                    <div class="es-item">
                        <div class="es-item-left w-100" @click="addDataProp.showAnalysis = !addDataProp.showAnalysis">
                            <div>解析：</div>
                            <div v-html="addDataProp.analysisData" v-if="addDataProp.analysisData"></div>
                            <div v-else>请输入解析内容</div>
                        </div>
                    </div>
                    <div class="homework-editor">
                        <vue-editor v-model="addDataProp.analysisData" placeholder="请输入解析内容" v-if="addDataProp.showAnalysis" :editor-toolbar="customToolbar"></vue-editor>
                    </div>
                </div>
            </div>
            <!-- <div class="es-model-operate">
                <Button type="primary" @click="addTitle">添加题目</Button>
                <Button type="primary" @click="submit">直接提交</Button>
                <Button type="default" @click="viewCategory">查看列表</Button>
                <Button type="default" @click="saveDraft">存为草稿</Button>
            </div> -->
        </div>
        <div v-else-if="currentPath.query.correctAnswer == '单选题'">
            <RadioGroup v-model="addDataProp.answerData">
                <div class="es-item" v-for="count in addDataProp.questionDataArr.length" :key="count">
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
            <CheckboxGroup v-model="addDataProp.answerDataArr">
                <div class="es-item" v-for="count in addDataProp.questionDataArr.length" :key="count">
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
            <RadioGroup v-model="addDataProp.answerData">
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
import comprehensionQuestion from './comprehensionQuestion'
export default {
    components:{
        VueEditor,
        editorComponent,
        connectionQuestion,
        comprehensionQuestion
    },
    props:['difficultlySetting','index','addDataProp'],
    data(){
        return{
            customToolbar:[
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["image", "code-block"]
            ],
            // addDataProp:{
            //     selLevel:'难度',
            //     contentData:'',
            //     questionDataArr:[],
            //     showAnalysis:false,
            //     analysisData:'',
            //     selQuestion:'解答题',
            //     answerData:'A',
            //     answerDataArr:['A'],
            // },
            
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    created(){
        console.log('+++',this.addDataProp)
    },
    watch:{
        currentPath:{
            handler(val){
                if(val.query.correctAnswer){
                    console.log(this.addDataProp)
                    console.log(this.index)
                }
            },
            deep:true
        }
    },
    methods:{
        questionType($event){
            this.addDataProp.selQuestion = $event;
            this.addDataProp.questionDataArr = [];
            this.addDataProp.answerData = 'A'
            this.addDataProp.answerDataArr = ['A']
            this.addDataProp.analysisData = ''
            this.addQuestion()
            if(this.addDataProp.selQuestion == '连线题'){
                this.addQuestion()
            }
        },
        addQuestion(){
            if(this.addDataProp.selQuestion == '单选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '单选题'
                this.addDataProp.questionDataArr.push(element)
            }else if(this.addDataProp.selQuestion == '多选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '多选题'
                this.addDataProp.questionDataArr.push(element)
            }
            else if(this.addDataProp.selQuestion == '填空题'){
               let element = {}
               element.title = '请设置答案（必填）'
               element.type = '填空题'
               this.addDataProp.questionDataArr.push(element)
            }
            else if(this.addDataProp.selQuestion == '连线题'){
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
                this.addDataProp.questionDataArr.push(element)
            }
            else if(this.addDataProp.selQuestion == '文字排序题'){
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
                this.addDataProp.questionDataArr.push(element)
            }
            else if(this.addDataProp.selQuestion == '综合题'){
                let element = {
                    selLevel:'难度',
                    contentData:'',
                    questionDataArr:[],
                    showAnalysis:false,
                    analysisData:'',
                    selQuestion:'解答题',
                    answerData:'A',
                    answerDataArr:['A'],
                };

                this.addDataProp.questionDataArr.push(element)
            }
        },
        removeQuestion(item){
            console.log(item)
            this.addDataProp.questionDataArr.splice(item.index,1)
        },
        levelType($event){
            this.addDataProp.selLevel = $event;
        },
        removeQuestionData(){
            console.log('removeQuestionData')
            this.$set(this.addDataProp,'index',this.index)
            this.$emit('removeQuestionData',this.addDataProp)
        },
        selAnswer(){
            this.$router.push({path:this.currentPath.path,query:{questionType:'习题'}})
        },
        
    }
}
</script>

<style>

</style>