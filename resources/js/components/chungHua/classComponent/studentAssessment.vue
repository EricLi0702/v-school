<template>
    <div>
        <div v-if="currentPath.query.selType == undefined">
            <div class="es-item">
                <div class="es-item-left">
                    <Input v-model="addData.name" class="customInput w-100" placeholder="评估名称"/>
                </div>
            </div>
            <router-link :to="{path:currentPath.path,query:{questionType:currentPath.query.questionType,selType:'评估范围'}}">
                <div class="es-item">
                    <div class="es-item-left">
                        评估范围
                    </div>
                    <div class="es-item-right">
                        <span v-if="addData.scope.length>0">{{addData.scope.length}}个年级</span>
                        <Icon type="ios-arrow-forward"></Icon>
                    </div>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    截止时间
                </div>
                <div class="es-item-right">
                    <DatePicker type="datetime" v-model="addData.deadline" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <div class="es-item" @click="goPlan">
                <div class="es-item-left">
                    评估方案
                </div>
                <div class="es-item-right">
                    请选择
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="submit">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.selType == '评估范围'">
            <div class="es-item" v-for="grade in gradeList" :key="grade.id" @click="selGrade(grade)">
                <div class="es-item-left">
                    <Checkbox v-model="grade.gradeFlag"></Checkbox>{{grade.gradeName}}
                </div>
                <div class="es-item-right">

                </div>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="selScope">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.selType == '评估方案'">

        </div>
    </div>
</template>

<script>
export default {
    async created(){
        await axios.get('/api/gradeByClassName',{params:{classId:this.currentPath.params.className}})
                    .then(res=>{
                        console.log(res.data)
                        this.gradeList = res.data
                        for(let i=0;i<this.gradeList.length;i++){
                            this.$set(this.gradeList[i],'gradeFlag',false)
                        }
                    })
                    .catch(err=>{
                        console.log(err)
                    })
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    data(){
        return{
            addData:{
                name:'',
                scope:[],
                deadline:'',
                plan:[]
            },
            gradeList:[],
        }
    },
    methods:{
        goPlan(){
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType,selType:'评估方案'}})
        },
        selGrade(grade){
            grade.gradeFlag = ! grade.gradeFlag
            console.log(grade)
        },
        selScope(){
            this.addData.scope = []
            for(let i=0;i<this.gradeList.length;i++){
                if(this.gradeList[i].gradeFlag == true){
                    this.addData.scope.push(this.gradeList[i])
                }
            }
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType}})
        },
        async submit(){
            console.log(this.addData)
            if(this.addData.name == ''){
                return this.error('评估名称不能为空')
            }
            if(this.addData.scope.length == 0){
                return this.error('请选择评估范围')
            }
            if(this.addData.deadline == ''){
                return this.error('截止时间不能为空')
            }
            if(this.addData.plan.length == 0){
                return this.error('请选择评估方案')
            }
            this.isLoading = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:23})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.swr();
            }
            this.isLoading = false;
        }
    }
}
</script>

<style>

</style>