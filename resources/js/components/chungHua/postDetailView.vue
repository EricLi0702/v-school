<template>
    <div>
        <div v-if="propsData.contentType == 15">
            <testQuestion :propsData="propsData"></testQuestion>
        </div>
        <div v-else-if="propsData.contentType == 18">
            <homeVisitContent :propsData="propsData"></homeVisitContent>
        </div>
        <div v-else-if="propsData.contentType == 19">
            <checkInResultView :propsData="propsData"></checkInResultView>
        </div>
        <div v-else-if="propsData.contentType == 20">
            <homeWorkResultView :propsData="propsData" :viewType="viewType"></homeWorkResultView>
        </div>
    </div>
</template>

<script>

import checkInResultView from '../../components/chungHua/checkInResultView'
import aboutViewModal from '../../components/chungHua/aboutViewModal'
import homeWorkResultView from '../../components/chungHua/homework/homeWorkResult'
import testQuestion from '../../components/chungHua/homework/testQuestion'
export default {
    props:[
        'propsData','viewType'
    ],
    components:{
        checkInResultView,
        aboutViewModal,
        homeWorkResultView,
        testQuestion,
    }
}
</script>

<style>

</style>