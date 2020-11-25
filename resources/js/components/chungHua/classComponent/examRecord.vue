<template>
    <div>
        <div v-if="currentPath.query.selType == undefined">
            <div class="es-item">
                <Input v-model="addData.examName" class="customInput w-100" placeholder="考试名称"/>
            </div>
            <div class="es-item">
                <DatePicker type="date"  v-model="addData.examTime" placeholder="选填" ></DatePicker>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    向学生家长显示排名
                </div>
                <div class="es-item-right">
                    <i-switch v-model="addData.rankingShow"/>
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    向学生家长显示平均分
                </div>
                <div class="es-item-right">
                    <i-switch v-model="addData.averageScore" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    向学生家长显示分数
                </div>
                <div class="es-item-right">
                    <i-switch v-model="addData.allScore" />
                </div>
            </div>
            <div class="es-item" @click="goLadderSetting" v-if="addData.allScore == false">
                <div class="es-item-left">
                    分数阶梯设置
                </div>
                <div class="es-item-right">
                    <span v-if="addData.ladderSetting.a != ''"> A ≥ {{addData.ladderSetting.a}},</span> 
                    <span v-if="addData.ladderSetting.b != ''"> B ≥ {{addData.ladderSetting.b}},</span> 
                    <span v-if="addData.ladderSetting.c != ''"> C ≥ {{addData.ladderSetting.c}},</span> 
                    <span v-if="addData.ladderSetting.d != ''"> D &lt; {{addData.ladderSetting.d}}</span> 
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="importData">导入</Button>
            </div>
        </div>

        <div v-else-if="currentPath.query.selType == '分数阶梯设置'">
            <div v-if="currentPath.query.ladderEdit == undefined">
                <div class="es-item" @click="ladderEdit(1)">
                    <div class="es-item-left">
                        <Checkbox v-model="first.flag">100分</Checkbox>
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
                <div class="es-item" @click="ladderEdit(2)">
                    <div class="es-item-left">
                        <Checkbox v-model="second.flag">120分</Checkbox>
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
                <div class="es-item" @click="ladderEdit(3)">
                    <div class="es-item-left">
                        <Checkbox v-model="third.flag">150分</Checkbox>
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
            </div>
            <div v-else-if="currentPath.query.ladderEdit == '1'">
                <div class="es-item">
                    <div class="es-item-left">
                        评价A
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="first.a" class="customInput pl-3 pr-0" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价B
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="first.b" class="customInput pl-3 pr-0" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价C
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="first.c" class="customInput pl-3 pr-0" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价D
                    </div>
                    <div class="es-item-right">
                         <span> &lt; </span> <Input v-model="first.d" class="customInput pl-3 pr-0" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addLadder(first)">提交</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.ladderEdit == '2'">
                <div class="es-item">
                    <div class="es-item-left">
                        评价A
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="second.a" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价B
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="second.b" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价C
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="second.c" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价D
                    </div>
                    <div class="es-item-right">
                         <span> &lt; </span> <Input v-model="second.d" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addLadder(second)">提交</Button>
                </div>
            </div>
            <div v-else-if="currentPath.query.ladderEdit == '3'">
                <div class="es-item">
                    <div class="es-item-left">
                        评价A
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="third.a" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价B
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="third.b" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价C
                    </div>
                    <div class="es-item-right">
                         ≥ <Input v-model="third.c" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        评价D
                    </div>
                    <div class="es-item-right">
                         <span> &lt; </span> <Input v-model="third.d" class="customInput" style="width:50px;" maxlength="3" placeholder=""/> 分 
                    </div>
                </div>
                <div class="es-model-operate">
                    <Button type="primary" @click="addLadder(third)">提交</Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                examName:'',
                examTime:'',
                rankingShow:false,
                averageScore:false,
                allScore:false,
                ladderSetting:{
                    a:'',
                    b:'',
                    c:'',
                    d:''
                },
            },
            first:{
                flag:false,
                a:85,
                b:70,
                c:60,
                d:60,
            },
            second:{
                flag:false,
                a:100,
                b:85,
                c:72,
                d:72
            },
            third:{
                flag:false,
                a:130,
                b:110,
                c:90,
                d:90
            }
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods:{
        goLadderSetting(){
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType,selType:'分数阶梯设置'}})
        },
        ladderEdit(index){
            if(index == 1){
                this.first.flag = true
                this.second.flag = false
                this.third.flag = false
            }else if(index == 2){
                this.first.flag = false
                this.second.flag = true
                this.third.flag = false
            }else if(indes ==3 ){
                this.first.flag = false
                this.second.flag = false
                this.third.flag = true
            }
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType,selType:'分数阶梯设置',ladderEdit:index}})
        },
        addLadder(val){
            this.addData.ladderSetting = val;
            console.log(this.addData)
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType}})
        },
        importData(){
            this.showModal = true
        }
    }
}
</script>

<style>

</style>