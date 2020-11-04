<template>
    <div>
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
            <div v-if="currentPath.query.index ">
                <div v-for="(addDataProp,i) in addData.addDataList" :key="i">
                    <div v-if="currentPath.query.index == i">
                        <homeworkQuestion 
                            :difficultlySetting="difficultlySetting" 
                            :addDataProp="addDataProp"
                            :index="i"
                            @removeQuestionData="removeQuestionData"
                        ></homeworkQuestion>
                    </div>
                    
                </div>
            </div>
            <div v-else>
                <div v-for="(addDataProp,j) in addData.addDataList" :key="j">
                    <homeworkQuestion 
                        :difficultlySetting="difficultlySetting" 
                        :addDataProp="addDataProp"
                        :index="j"
                        @removeQuestionData="removeQuestionData"
                    ></homeworkQuestion>
                </div>
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
                addDataList:[],
            },
            // addDataList:[],
            uploadModal:false,
            correctAnswer:null,
            index:0
        }
    },
    mounted(){

    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    
    created(){
        console.log('created')
        this.addTitle()
    },
    methods:{

        addTemplate(){

        },
        batchType($event){
            this.addData.batchLevel = $event
        },
        addTitle(){
            console.log('title')
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

            this.addData.addDataList.push(element)

        },
        // submit(){
        //     console.log('submit')
        //     console.log(this.addData)
        // },
        // viewCategory(){
        //     console.log('category')
        // },
        // saveDraft(){
        //     console.log('draft')
        // },
        // handleSuccess(res){
        //     console.log(res)
        // },
        removeQuestionData(item){
            console.log(item)
            this.addData.addDataList.splice(item.index,1)
        },
        async submit(){
            let element = {}

            if(this.addDataProp.selQuestion == '连线题'){
                for(let i=0;i<this.addDataProp.questionDataArr.length;i++){
                    this.addDataProp.questionDataArr[i].first.key = i;
                    this.addDataProp.questionDataArr[i].second.key = i;
                }
                for(let i=0;i<this.questionDataArr.length;i++){
                    let indexA = Math.floor(Math.random() * this.addDataProp.questionDataArr.length);
                    let indexB = Math.floor(Math.random() * this.addDataProp.questionDataArr.length);
                    if( i % 2 ==0){
                        this.swapFirst(this.addDataProp.questionDataArr,indexA,indexB)
                    }else{
                        this.swapSecond(this.addDataProp.questionDataArr,indexA,indexB)
                    }
                }
            }

            if(this.addDataProp.selQuestion == '文字排序题'){
                for(let i=0;i<this.addDataProp.questionDataArr.length;i++){
                    this.addDataProp.questionDataArr[i].contentData.key=i
                }
                for(let i=0;i<this.addDataProp.questionDataArr.length;i++){
                    let indexA = Math.floor(Math.random() * this.addDataProp.questionDataArr.length);
                    let indexB = Math.floor(Math.random() * this.addDataProp.questionDataArr.length);
                    this.swap(this.addDataProp.questionDataArr,indexA,indexB)
                }
                // element.answerData = this.questionDataArr
            }
            // console.log(this.questionDataArr)
            // return
            element.questionDataArr = this.addDataProp.questionDataArr;
            element.selQuestion = this.addDataProp.selQuestion
            if(this.addDataProp.selQuestion == '单选题' || this.addDataProp.selQuestion == '判断题'){
                element.answerData = this.addDataProp.answerData
            }else if(this.addDataProp.selQuestion == '多选题'){
                element.answerData = this.addDataProp.answerDataArr
            }
            if(this.addDataProp.selQuestion == '判断题'){
                let el = {}
                el.contentData = '正确'
                element.questionDataArr.push(el)
                el.contentData = '错误'
                element.questionDataArr.push(el)
            }
            element.analysisData = this.addDataProp.analysisData
            this.addDataProp.questionDataArr.push(element)
            console.log(this.addDataProp);
            // return
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addDataProp,userId:userId,contentType:20})
            console.log(res)
            if(res.status == 201){
                 this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addDataProp:res.data}})
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