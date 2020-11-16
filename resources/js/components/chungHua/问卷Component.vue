<template>
    <div>
        <div class="es-item" v-for="(questionnaireMenu,modalMenuIndex) in questionnaireLists" :key="modalMenuIndex" @click="modalMenuClick(questionnaireMenu)">
            <div class="es-item-left">{{questionnaireMenu.name}}</div>
            <div class="es-item-right"><i class="ivu-icon ivu-icon-ios-arrow-forward"></i></div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            questionnaireLists:[],
        }
    },
    mounted(){
        axios.get('/api/questionnaireLists').then(res=>{
            this.questionnaireLists = res.data;
        })
    },
    methods:{
        modalMenuClick(item){
        }
    }
}
</script>