<template>
    <div>
        <div v-if="currentPath.query.actionName == '邀请成员'">
            <inviteMember :gradeInfo="gradeInfo"></inviteMember>
        </div>
        <div v-else-if="currentPath.query.actionName == '小组'">
            <notConnect></notConnect>
        </div>
        <div v-else-if="currentPath.query.actionName == '学号'">

        </div>
        <div v-else-if="currentPath.query.actionName == '座位'">
            <seating></seating>
        </div>
        <div v-else>
           <h1>{{currentPath.query.actionName}}</h1> 
        </div>
    </div>    
</template>

<script>
import inviteMember from './inviteMember'
import notConnect from '../pages/notConnect'
import seating from './seating'
export default {
    props:["gradeInfo"],
    components:{
        inviteMember,
        notConnect,
        seating,
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    watch:{
        currentPath(value){
            console.log('actionview',value.query.actionName)
        }
    }
}
</script>