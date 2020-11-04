<template>
    <div>
        <div v-if="currentPath.query.correctAnswer == undefined">
            <div class="category-title text-right" @click="uploadModal = true">
                从文件中批量导入习题
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    习题名称
                </div>
                <div class="es-item-right w-50">
                    <Input v-model="addData.title" class="customInput rightToLeft" placeholder="选填"/>
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    批量设置难度
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="difficultlySetting" />
                </div>
            </div>
            <div class="es-item" v-if="difficultlySetting == true">
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
        </div>    
        <div v-for="(addData,i) in addDataList" :key="i">
            <homeworkQuestion 
                :difficultlySetting="difficultlySetting" 
                :addData="addData"
                :index="i"
                @removeQuestionData="removeQuestionData"
            ></homeworkQuestion>
        </div>
        <div v-if="currentPath.query.correctAnswer == undefined" class="es-model-operate">
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
import homeworkQuestion from './homework/childHomework'
export default {
    components:{
        VueEditor,
        homeworkQuestion
    },
    data(){
        return{
            difficultlySetting:false,
            addData:{
                title:'',
                batchLevel:'难度',
                questionDataArr:[],
            },
            addDataList:[],
            uploadModal:false,
        }
    },
    mounted(){

    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods:{

        addTemplate(){

        },
        batchType($event){
            this.addData.batchLevel = $event
        },
        addTitle(){
            console.log('title')
            let element = {};
            element.selQuestion = '解答题';
            element.showAnalysis = false;
            element.analysisData = '';
            element.answerData = 'A',
            element.answerDataArr = ['A'];
            this.addDataList.push(element)

        },
        submit(){
            console.log('submit')
        },
        viewCategory(){
            console.log('category')
        },
        saveDraft(){
            console.log('draft')
        },
        handleSuccess(res){
            console.log(res)
        },
        removeQuestionData(item){
            console.log(item)
            this.addDataList.splice(item.index,1)
        } 
        
    }
}
</script>