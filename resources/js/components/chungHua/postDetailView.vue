<template>
    <div>
        <div v-if="propsData.contentType == 15">
            <testQuestion :propsData="propsData" :viewType="viewType"></testQuestion>
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
import homeVisitContent from '../../components/chungHua/homeVisitContent'
export default {
    props:[
        'propsData','viewType'
    ],
    components:{
        checkInResultView,
        aboutViewModal,
        homeWorkResultView,
        testQuestion,
        homeVisitContent
    }
}
</script>

<style>

</style>