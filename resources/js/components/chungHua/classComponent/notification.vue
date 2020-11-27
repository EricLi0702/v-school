<template>
    <div>
        <div v-if="currentPath.query.selType == undefined">
            <div class="es-item">
                <div class="es-item-left">
                    <Input v-model="addData.title" class="customInput w-100" placeholder="标题"/>
                </div>
            </div>
            <div class="es-item" @click="goSignName">
                <div class="es-item-left">
                    落款名称
                </div>
                <div class="es-item-right">
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
            <contentComponent @contentData="selContent"></contentComponent>
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.selType == 'signName'">
            <div class="es-item" @click="selSignName($store.state.user.name)">
                {{$store.state.user.name}}
            </div>
            <div class="category-title"></div>
            <div class="es-item" v-for="signName in signNameList" :key="signName.id" @click="selSignName(signName.name)">
                {{signName.name}}
            </div>
            <div class="es-item" @click="showAddDiv = !showAddDiv">
                div.es-item-left
                <Icon type="ios-add"></Icon>自定义落款
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    <Input v-model="addName" class="customInput w-100" placeholder="评估名称"/>
                </div>
                <div class="es-item-right">
                    <Button type="primary" @click="addSignName">提交</Button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import contentComponent from '../contentComponent'
export default {
    components:{
        contentComponent,
    },
    data(){
        return{
            addData:{
                title:'',
                signName:this.$store.state.user.name,
                description:null
            },
            isLoading:false,
            signNameList:[],
            addName:'',
            showAddDiv:false,
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    async created(){
        console.log('+++')
        const res = await this.callApi('get','/api/signName')
        if(res.status == 200){
            this.signNameList = res.data
            console.log(res.data)
        }
    },
    methods:{
        selContent(val){
            this.addData.description = val
        },
        goSignName(){
            console.log(this.currentPath)
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType,selType:"signName"}})
        },
        selSignName(signName){
            console.log(signName)
            this.addData.signName = signName
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType}})
        },
        async submit(){
            console.log(this.addData)
            this.isLoading = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:24})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                this.$router.push({path:this.$route.path,query:{addData:res.data}})

            }else{
                this.swr();
            }
            this.isLoading = false;
        },
        async addSignName(){
            if(this.addName.trim() == ''){
                return this.error('内容不能为空')
            }
            this.isLoading = true
            const res = this.callApi('post','/api/signName',this.addName)
            console.log(res)
            if(res.status == 201){
                this.addName = ''
                this.success('操作成功')
                this.signNameList.push(res.data)

            }
            this.isLoading = false
        }
    }
}
</script>

<style>

</style>