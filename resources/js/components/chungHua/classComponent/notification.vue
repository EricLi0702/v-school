<template>
    <div>
        <div v-if="currentPath.query.selType == undefined">
            <div class="vx-item">
                <Input v-model="addData.title" class="customInput w-100 px-0" placeholder="标题"/>
            </div>
            <div class="vx-item is-click" @click="goSignName">
                <div class="vx-item-left">
                    落款名称
                </div>
                <div class="vx-item-right">
                    <span>{{addData.signName}}</span>
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
            <contentComponent @contentData="selContent"></contentComponent>
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.selType == 'signName'">
            <div class="vx-item is-click" @click="selSignName($store.state.user.name)">
                {{$store.state.user.name}}
            </div>
            <div class="category-title"></div>
            <div class="vx-item is-click" v-for="signName in signNameList" :key="signName.id" @click="selSignName(signName.name)">
                {{signName.name}}
            </div>
            <div class="vx-item is-click" @click="showAddDiv = !showAddDiv">
                <div class="vx-item-left">
                    <Icon type="ios-add"></Icon>自定义落款
                </div>
            </div>
            <div class="vx-item" v-if="showAddDiv">
                <div class="vx-item-left">
                    <Input v-model="addName" class="customInput w-100" placeholder="评估名称"/>
                </div>
                <div class="vx-item-right">
                    <Button type="primary" @click="addSignName" :loading="isLoading" :disabled="isLoading">提交</Button>
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
        const res = await this.callApi('get','/api/signName')
        if(res.status == 200){
            this.signNameList = res.data
        }
    },
    methods:{
        selContent(val){
            this.addData.description = val
        },
        goSignName(){
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType,selType:"signName"}})
        },
        selSignName(signName){
            this.addData.signName = signName
            this.$router.push({path:this.currentPath.path,query:{questionType:this.currentPath.query.questionType}})
        },
        async submit(){
            this.isLoading = true
            let userId = this.$store.state.user.id;
            let foamingPosition = ''
            if(this.currentPath.params.schoolName){
                foamingPosition = this.currentPath.params.schoolName
            }
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:24,foamingPosition:foamingPosition})
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
            const res = await this.callApi('post','/api/signName',{addName:this.addName})
            if(res.status == 201){
                this.addName = ''
                this.success('操作成功')
                this.signNameList.push(res.data)
                this.showAddDiv = false
            }
            this.isLoading = false
        }
    }
}
</script>

<style>

</style>