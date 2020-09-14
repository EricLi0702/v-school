<template>
    <div>
        <div v-if="currentPath.query.modalName == '群组申请'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '社团'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '课程'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '毕业班级'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.modalName == '高一年级'">
            <highGrade :grade="currentPath.query.modalName"></highGrade>
        </div>
        <div v-else-if="currentPath.query.modalName == '高二年级'">
            <highGrade :grade="currentPath.query.modalName"></highGrade>
        </div>
        <div v-else-if="currentPath.query.modalName == '高三年级'">
            <highGrade :grade="currentPath.query.modalName"></highGrade>
        </div>
        <div v-else-if="currentPath.query.modalName == '八年级'">
           <highGrade :grade="currentPath.query.modalName"></highGrade>
        </div>
        <div v-else-if="currentPath.query.modalName == '九年级'">
            <highGrade :grade="currentPath.query.modalName"></highGrade>
        </div>
    </div>
</template>

<script>
import highGrade from './年级Component'
import notConnect from '../pages/notConnect'
export default {
    components:{
        highGrade,
        notConnect,
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
}
</script>