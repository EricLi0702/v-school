<template>
    <div>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="questionType($event)">
                    <a href="javascript:void(0)">
                        <div>{{propsData.selQuestion}}<Icon type="ios-arrow-down" /></div>
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="单选题">单选题</DropdownItem>
                        <DropdownItem name="多选题">多选题</DropdownItem>
                        <!-- <DropdownItem name="填空题">填空题</DropdownItem> -->
                        <DropdownItem name="解答题">解答题</DropdownItem>
                        <DropdownItem name="判断题">判断题</DropdownItem>
                        <!-- <DropdownItem name="连线题">连线题</DropdownItem>
                        <DropdownItem name="文字排序题">文字排序题</DropdownItem>
                        <DropdownItem name="图片排序题">图片排序题</DropdownItem> -->
                    </DropdownMenu>
                </Dropdown>
            </div>
            <div class="es-item-right">
            <span class="text-color" @click="removeComprehension">删除</span> 
            </div>
        </div>
        <div>
            <vue-editor v-model="propsData.contentData" placeholder="题干内容" :editor-toolbar="customToolbar"></vue-editor>
        </div>               
        <div class="p-2">
            <div v-if="propsData.selQuestion == '单选题' || propsData.selQuestion == '多选题' || propsData.selQuestion == '填空题' || propsData.selQuestion == '解答题' || propsData.selQuestion == '判断题' || propsData.selQuestion == '文字排序题' ">
                <div class="es-item" v-if="propsData.selQuestion == '单选题' || propsData.selQuestion == '多选题' || propsData.selQuestion == '填空题' || propsData.selQuestion == '文字排序题'">
                    <div class="es-item-left">
                        选项
                    </div>
                    <div class="es-item-right text-color" @click="addQuestion()">
                        添加选项 
                    </div>
                </div>
                <div v-for="(el,i) in propsData.questionDataArr" :key="i">
                    <editorComponent :element="el" :index="i" @removeItem="removeQuestion"></editorComponent>
                </div>
                <div class="es-item" v-if="propsData.selQuestion == '单选题'">
                    <div class="es-item-left">
                        <router-link :to="{path:currentPath.path,query:{questionType:'习题',correctAnswer:'单选题',parentId:parentId,index:index,routerData:propsData}}">
                            答案：{{propsData.answerData}}<Icon type="ios-arrow-forward" />
                        </router-link>
                    </div>
                </div>
                <div v-else-if="propsData.selQuestion == '多选题'">
                    <div class="es-item-left">
                        <router-link :to="{path:currentPath.path,query:{questionType:'习题',correctAnswer:'多选题',parentId:parentId,index:index,routerData:propsData}}">
                            答案：{{propsData.answerDataArr}}<Icon type="ios-arrow-forward" />
                        </router-link>
                    </div>
                </div>
                <div class="es-item" v-else-if="propsData.selQuestion == '判断题'">
                    <router-link :to="{path:currentPath.path,query:{questionType:'习题',correctAnswer:'判断题',parentId:parentId,index:index,routerData:propsData}}">
                        <div class="es-item-left">
                            答案：
                            <span v-if="propsData.answerData == 'A'">正确</span> 
                            <span v-if="propsData.answerData == 'B'">错误</span> 
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </router-link>
                </div>
            </div>
            <div v-else-if="propsData.selQuestion == '连线题'">
                <div class="es-item">
                    <div class="es-item-left">
                        选项
                    </div>
                    <div class="es-item-right text-color" @click="addQuestion()">
                        添加选项 
                    </div>
                </div>
                <div v-for="(el,i) in propsData.questionDataArr" :key="i">
                    <connectionQuestion :element="el" :index="i" @removeItem="removeQuestion"></connectionQuestion>
                </div>
                
            </div>
            <div v-else-if="propsData.selQuestion == '图片排序题'">
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
        </div>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
import editorComponent from './editorComponent'
import connectionQuestion from './connectionQuestion'
export default {
    props:[
        'propsData','index','parentId'
    ],
    components:{
        VueEditor,
        editorComponent,
        connectionQuestion,
    },
    created(){
        console.log('comprehension component',this.propsData)
    },
    data(){
        return{
            customToolbar:[
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["image", "code-block"]
            ],
            showAnalysis:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods:{
        questionType($event){
            this.propsData.selQuestion = $event;
            this.propsData.questionDataArr = [];
            this.propsData.answerData = 'A'
            this.propsData.answerDataArr = ['A']
            this.propsData.analysisData = ''
            this.addQuestion()
            if(this.propsData.selQuestion == '连线题'){
                this.addQuestion()
            }
        },
        addQuestion(){
            if(this.propsData.selQuestion == '单选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '单选题'
                this.propsData.questionDataArr.push(element)
            }else if(this.propsData.selQuestion == '多选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '多选题'
                this.propsData.questionDataArr.push(element)
            }
            else if(this.propsData.selQuestion == '填空题'){
               let element = {}
               element.title = '请设置答案（必填）'
               element.type = '填空题'
               this.propsData.questionDataArr.push(element)
            }
            else if(this.propsData.selQuestion == '连线题'){
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
                this.propsData.questionDataArr.push(element)
            }
            else if(this.propsData.selQuestion == '文字排序题'){
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
                this.propsData.questionDataArr.push(element)
            }
        },
        removeQuestion(item){
            console.log(item)
            this.propsData.questionDataArr.splice(item.index,1)
        },
        removeComprehension(){
            console.log('------')
            let removeItem = {}
            this.$set(removeItem,'index',this.index)
            this.$emit('removeComprehension',removeItem)
            console.log('comprehension component',this.index);
        }
    }
}
</script>

<style>

</style>