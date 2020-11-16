<template>
    <div>
        <div v-if="currentPath.query.gradeName == '群组申请'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.gradeName == '社团'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.gradeName == '课程'">
            <newClub></newClub>
        </div>
        <div v-else-if="currentPath.query.gradeName == '毕业班级'">
            <notConnect></notConnect>
        </div>
        <div v-else>
            <highGrade :grade="currentPath.query.gradeName"></highGrade>
        </div>
        <!-- <div v-else-if="currentPath.query.modalName == '高一年级'">
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
        </div> -->
    </div>
</template>

<script>
import highGrade from './年级Component'
import notConnect from '../pages/notConnect'
import newClub from './newClub'
import NewClub from './newClub.vue'
export default {
    props:["grade"],
    components:{
        highGrade,
        notConnect,
        newClub,
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
}
</script>