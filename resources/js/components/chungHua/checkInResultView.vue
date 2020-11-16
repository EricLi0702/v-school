<template>
    <div>
        <line-chart></line-chart>
        <div class="category-title"></div>
        <notConnect></notConnect>
    </div>
</template>

<script>
import LineChart from './lineChart'
import notConnect from '../pages/notConnect'
export default {
    props:['propsData'],
    components: {
        LineChart,
        notConnect
    },
    watch:{
        propsData:{
            handler(val){
            },
            deep:true
        }
    }
}
</script>