<template>
    <div>
        <div class="category-title text-right" @click="uploadModal = true">
            从文件中批量导入习题
        </div>
        <div class="es-item">
            <div class="es-item-left">
                习题名称
            </div>
            <div class="es-item-right w-50">
                <Input v-model="addData.homeworkName" class="customInput rightToLeft" placeholder="选填"/>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                批量设置难度
            </div>
            <div class="es-item-right">
                <i-switch true-color="#13ce66" v-model="addData.difficultlySetting" />
            </div>
        </div>
        <div class="es-item" v-if="addData.difficultlySetting == true">
            <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="batchType($event)">
                <a href="javascript:void(0)">
                    <div>{{addData.batchLevel}}<Icon type="ios-arrow-down" /></div>
                </a>
                <DropdownMenu slot="list">
                    <DropdownItem name="不限难度">不限难度</DropdownItem>
                    <DropdownItem name="简单">简单</DropdownItem>
                    <DropdownItem name="一般">一般</DropdownItem>
                    <DropdownItem name="困难">困难</DropdownItem>
                </DropdownMenu>
            </Dropdown>
        </div>
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
                        <DropdownItem name="填空题">填空题</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="levelType($event)" v-if="addData.difficultlySetting == false">
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
               <span class="text-color">删除</span> 
            </div>
        </div>
        <div>
            <vue-editor v-model="addData.contentData" placeholder="题干内容" :editor-toolbar="customToolbar"></vue-editor>
        </div>
        
        <div class="p-2">
            <div class="es-item" v-if="addData.selQuestion == '单选题' || addData.selQuestion == '多选题' || addData.selQuestion == '填空题'">
                <div class="es-item-left">
                    选项
                </div>
                <div class="es-item-right text-color" @click="addQuestion()">
                    添加选项 
                </div>
            </div>
            <div v-for="(el,i) in questionDataArr" :key="i">
                <editorComponent :element="el" :index="i" @removeItem="removeQuestion"></editorComponent>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    答案：<Icon type="ios-arrow-forward" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left w-100" @click="showAnalysis = !showAnalysis">
                    <div>解析：</div>
                    <div v-html="addData.analysisData" v-if="addData.analysisData"></div>
                    <div v-else>请输入解析内容</div>
                </div>
            </div>
            <div class="homework-editor">
                <vue-editor v-model="addData.analysisData" placeholder="请输入解析内容" v-if="showAnalysis" :editor-toolbar="customToolbar"></vue-editor>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="addTitle">添加题目</Button>
            <Button type="primary" @click="submit">直接提交</Button>
            <Button type="default" @click="viewCategory">查看列表</Button>
            <Button type="default" @click="saveDraft">存为草稿</Button>
        </div>
        
        <Modal
            v-model="uploadModal"
            class="uploadModal"
            title="导入习题"
            :styles="{top:'140px',left:'64px'}">
                <Upload
                    multiple
                    type="drag"
                     :on-success="handleSuccess"

                    action="/api/fileUpload/other">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>将文件拖到此处，或 <span class="text-color">点击上传</span></p>
                    </div>
                </Upload>
                <div class="es-item-tooltip">
                    <div>导入说明</div> 
                    <div>1、必须按正确的格式将数据填入模板 <a href="/download/doc/praxisTemplate.doc" class="text-color" download>（ 下载模板 ）</a></div>
                    <div>2、文件格式必须为xls、xlsx、doc。</div>
                </div>
        </Modal>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
import editorComponent from './editorComponent'
export default {
    components:{
        VueEditor,
        editorComponent,

    },
    data(){
        return{
            addData:{
                homeworkName:'',
                difficultlySetting:false,
                batchLevel:'难度',
                selQuestion:'解答题',
                selLevel:'难度',
                contentData:'',
                analysisData:''
            },
            customToolbar:[
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["image", "code-block"]
            ],
            showAnalysis:false,
            uploadModal:false,
            questionCnt:0,
            questionTitile:'',
            // questionData:{
            //     singleSel:[],
            //     multiSel:[],
            //     fillBlank:[],
            //     answer:[],
            //     trueOrFalse:[],
            //     comprehensive:[],
            //     connection:[],
            //     textSoriting:[],
            //     pictureSorting:[]
            // },
            questionDataArr:[],
            newQuestion:{},
            
        }
    },
    methods:{
        addTemplate(){

        },
        batchType($event){
            this.addData.batchType = $event
        },
        questionType($event){
            this.addData.selQuestion = $event;
            this.questionDataArr = [];
            this.addQuestion()
        },
        
        addQuestion(){
            if(this.addData.selQuestion == '单选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '单选题'
                this.questionDataArr.push(element)
            }else if(this.addData.selQuestion == '多选题'){
                let element = {}
                element.title = '请输入选项内容（必填）'
                element.type = '多选题'
                this.questionDataArr.push(element)
            }
            else if(this.addData.selQuestion == '填空题'){
               let element = {}
               element.title = '请设置答案（必填）'
               element.type = '填空题'
               this.questionDataArr.push(element)
            }
        },
        removeQuestion(item){
            console.log(item)
            this.questionDataArr.splice(item.index,1)
        },
        levelType($event){
            this.addData.selLevel = $event;
        },
        saveDraft(){
            console.log('saveDraft')
        },
        viewCategory(){
            console.log('viewCategory')
        },
        submit(){
            console.log('submit')
        },
        addTitle(){
            console.log('title')
        },
        
        handleSuccess(res){
            console.log(res)
        }
    }
}
</script>