<template>
    <div>
        <div v-if="currentPath.query.profile == undefined || currentPath.query.profile == '我的信息'">
            <myInformation></myInformation>
        </div>
        <div v-else-if="currentPath.query.profile == '当前状态'">
            <currentState></currentState>
        </div>
        <div v-else-if="currentPath.query.profile == '错题本'">
            <wrongBook></wrongBook>
        </div>
        <div v-else-if="currentPath.query.profile == '录播'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.profile == '回收站'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.profile == '运动'">
            <movement></movement>
        </div>
        <div v-else-if="currentPath.query.profile == '订单'">
            <other></other>
        </div>
        <div v-else-if="currentPath.query.profile == '教师发展档案'">
            <teacherDev></teacherDev>
        </div>
        <div v-else-if="currentPath.query.profile == '帮助中心'">
            <helperCenter></helperCenter>
        </div>
        <div v-else>
            <notConnect></notConnect>
        </div>
    </div>
</template>

<script>
import myInformation from './myInformation'
import currentState from './currentState'
import wrongBook from './wrongBook'
import notConnect from '../pages/notConnect'
import movement from './movement'
import other from './other'
import teacherDev from './teacherDev'
import helperCenter from './helperCenter'
export default {
    components:{
        myInformation,
        currentState,
        wrongBook,
        notConnect,
        movement,
        other,
        teacherDev,
        helperCenter
    },
    computed:{
        currentPath(){
            return this.$route
        }
    }
}
</script>