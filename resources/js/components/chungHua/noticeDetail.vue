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
    </div>
</template>

<script>
export default {
    props:['propsData','viewType'],
    watch:{
        propsData:{
            handler(val){
                if(val){
                    // val.view
                    console.log(val)
                }
            },
            deep:true
        }
    }
}
</script>
<style>

</style>