<template>
    <div>
        <div v-if="currentPath.query.actionName == '邀请成员'">
            <inviteMember></inviteMember>
        </div>
        <div v-else>
           <h1>{{currentPath.query.actionName}}</h1> 
        </div>
    </div>    
</template>

<script>
import inviteMember from './inviteMember'
export default {
    components:{
        inviteMember,
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