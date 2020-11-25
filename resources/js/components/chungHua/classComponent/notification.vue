<template>
    <div>
        <div>
            <div class="es-item">
                <div class="es-item-left">

                </div>
            </div>
            <div class="es-item" @click="selSignName">
                <div class="es-item-left">

                </div>
                <div class="es-item-right">

                </div>
            </div>
            <contentComponent @contentData="selContent"></contentComponent>
            <div class="es-model-operate">
                <Button type="primary" @click="submit" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                title:'',
                signName:'',
                description:null
            },
            isLoading:false,
        }
    },
    computed:{
        currentPath(){
            return this.$rotue
        }
    },
    methods:{
        selContent(val){
            this.addData.description = val
        },
        selSignName(){
            this.$router.push({path:})
        }
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
        }
    }
}
</script>

<style>

</style>