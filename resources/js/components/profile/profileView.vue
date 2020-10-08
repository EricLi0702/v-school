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
    </div>
</template>

<script>
import myInformation from './myInformation'
import currentState from './currentState'
import wrongBook from './wrongBook'
export default {
    components:{
        myInformation,
        currentState,
        wrongBook,
    },
    computed:{
        currentPath(){
            return this.$route
        }
    }
}
</script>