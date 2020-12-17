<template>
    <div class="document-detail">
        <div class="dd-title">
            {{propsData.addData.title}}
        </div>
        <div class="dd-content" v-html="propsData.addData.content">

        </div>
        <div class="dd-inscription">
            <!-- {{propsData}} -->
            <span>{{propsData.addData.viewList[propsData.addData.viewList.length-1]}}</span>
            <br>
            <span>{{TimeView(propsData.created_at)}}</span>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="sign" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div> 
</template>

<script>
export default {
    props:['propsData','viewType'],
    data(){
        return{
            isLoading:false,
        }
    },
    watch:{
        propsData:{
            handler(val){
                if(val){
                    // val.view
                    console.log('-------',val)
                }
            },
            deep:true
        }
    },
    methods:{
        sign(){
            
        }
    }
}
</script>
<style>

</style>