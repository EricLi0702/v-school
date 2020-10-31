<template>
    <div>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="questionType($event)">
                    <a href="javascript:void(0)">
                        <div>{{selQuestion}}<Icon type="ios-arrow-down" /></div>
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
            <span class="text-color" @click="removeQuestion">删除</span> 
            </div>
        </div>
        <div>
            <vue-editor v-model="addData.contentData" placeholder="题干内容" :editor-toolbar="customToolbar"></vue-editor>
        </div>               
        <div class="p-2">
            <div v-if="selQuestion == '单选题' || selQuestion == '多选题' || selQuestion == '填空题' || selQuestion == '解答题' || selQuestion == '判断题' || selQuestion == '文字排序题' ">
                <div class="es-item" v-if="selQuestion == '单选题' || selQuestion == '多选题' || selQuestion == '填空题' || selQuestion == '文字排序题'">
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
                <div class="es-item" v-if="selQuestion == '单选题'">
                    <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'单选题'}}">
                        <div class="es-item-left">
                            答案：{{answerData}}<Icon type="ios-arrow-forward" />
                        </div>
                    </router-link>
                </div>
                <div v-else-if="selQuestion == '多选题'">
                    <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'多选题'}}">
                        <div class="es-item-left">
                            答案：{{answerDataArr}}<Icon type="ios-arrow-forward" />
                        </div>
                    </router-link>
                </div>
                <div class="es-item" v-else-if="selQuestion == '判断题'">
                    <router-link :to="{path:`${currentPath.path}`,query:{questionType:'习题',correctAnswer:'判断题'}}">
                        <div class="es-item-left">
                            答案：
                            <span v-if="answerData == 'A'">正确</span> 
                            <span v-if="answerData == 'B'">错误</span> 
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </router-link>
                </div>
            </div>
            <div v-else-if="selQuestion == '综合题'">
                <!-- <div v-for=" count in questionCnt" :key="count">
                    <comprehensionQuestion></comprehensionQuestion>
                </div>
                <div class="category-title text-center text-color" @click="addComprehension">添加小题</div> -->
            </div>
            <div v-else-if="selQuestion == '连线题'">
                <div class="es-item">
                    <div class="es-item-left">
                        选项
                    </div>
                    <div class="es-item-right text-color" @click="addQuestion()">
                        添加选项 
                    </div>
                </div>
                <div v-for="(el,i) in questionDataArr" :key="i">
                    <connectionQuestion :element="el" :index="i" @removeItem="removeQuestion"></connectionQuestion>
                </div>
                
            </div>
            <div v-else-if="selQuestion == '图片排序题'">
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
            <div v-if="selQuestion != '综合题'">
                <div class="es-item">
                    <div class="es-item-left w-100" @click="showAnalysis = !showAnalysis">
                        <div>解析：</div>
                        <div v-html="analysisData" v-if="analysisData"></div>
                        <div v-else>请输入解析内容</div>
                    </div>
                </div>
                <div class="homework-editor">
                    <vue-editor v-model="analysisData" placeholder="请输入解析内容" v-if="showAnalysis" :editor-toolbar="customToolbar"></vue-editor>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {VueEditor} from "vue2-editor"
export default {
    props:[
        'propsData',
    ],
    components:{
        VueEditor
    },
    data(){
        return{
            addData:{
                selQuestion:'解答题',
                contentData:"",
            },
            customToolbar:[
                ["bold", "italic", "underline"],
                [{ list: "ordered" }, { list: "bullet" }],
                ["image", "code-block"]
            ],
            showAnalysis:false,
        }
    }
}
</script>

<style>

</style>