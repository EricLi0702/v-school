<template>
    <div>
        <contentComponent @contentData="emitData"></contentComponent>
        <div class="es-model-operate">
            <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
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
                imgUrl:[],
                otherUrl:[],
                videoUrl:[]
            },
            isLoading:false
        }
    },
    created(){

    },
    methods:{
        emitData(val){
            this.addData = val
        },
        async submit(){
            if(this.addData.title == ''){
                return this.error('标题不能为空')
            }
            this.isLoading = true
            let userId = this.$store.state.user.id;
            const res = await this.callApi('post','/api/questionnaire',{data:this.addData,userId:userId,contentType:25})
            if(res.status == 201){
                this.success('操作成功')
                this.$store.commit('setShowQuestionModal',false);
                this.$store.commit('setModalView',false)
                console.log(res.data)
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