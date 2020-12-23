<template>
    <div>
        <div class="document-detail" v-if="currentPath.query.answerUsers == undefined">
            <div class="vx-item is-click"  @click="viewResult">
                <div class="vx-item-left">
                    <span>已答</span>
                </div>
                <div class="vx-item-right">
                    <span>{{propsData.answers.length}}</span>
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            <div class="dd-title">
                {{propsData.addData.title}}
            </div>
            <div class="dd-content" v-html="propsData.addData.content">

            </div>
            <div class="dd-inscription">
                <!-- {{propsData}} -->
                <span>{{propsData.addData.viewList[propsData.addData.viewList.length]}}</span>
                <br>
                <span>{{TimeView(propsData.created_at)}}</span>
            </div>
            <div class="es-model-operate">
                <Button type="primary" @click="sign" :disabled="activeSign">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.answerUsers == 'true'">
            <div class="vx-item" v-for="user in propsData.answers" :key="user.id">
                <div class="vx-item-left">
                    {{user.user.name}}
                </div>
                <div class="vx-item-right">
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
        </div>
    </div> 
</template>

<script>
export default {
    props:['propsData','viewType'],
    data(){
        return{
            isLoading:false,
            activeSign:false
        }
    },
    watch:{
        propsData:{
            handler(val){
                if(val){
                    // val.view
                }
            },
            deep:true
        }
    },
    mounted(){
        if(this.propsData.addData.scopeFlag == false){
            this.activeSign = true
        }else{
            if(this.propsData.answerUserList != null){
                this.activeSign = true
            }
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    methods:{
        async sign(){
            this.$set(this.propsData.addData,'sign',true)
            this.activeSign = true
            const res = await this.callApi('post','/api/answerBulletin',{answerData:this.propsData.addData,userId:this.$store.state.user.id,bulletinId:this.propsData.id})
            // this.isLoading = false
            if(res.status == 200){
                this.success('操作成功')
                this.$emit('answer',res.data[0])
                this.$store.commit('setShowAnswerDetail',false);
                // this.activeSign = true
            }else{
                this.swr()
            }
            
        },
        viewResult(){
            this.$router.push({path:this.currentPath.path,query:{answerUsers:true}})
        }
    }
}
</script>
<style>

</style>