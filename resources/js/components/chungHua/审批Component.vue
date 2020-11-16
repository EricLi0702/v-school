<template>
    <div>
        <div class="es-item" @click="modalMenuClick('全体老师')">
            <div class="es-item-left">全体老师</div>
            <div class="es-item-right"><i class="ivu-icon ivu-icon-ios-arrow-forward"></i></div>
        </div>
        <div class="es-item" @click="modalMenuClick('宿舍管理员')">
            <div class="es-item-left">宿舍管理员</div>
            <div class="es-item-right"><i class="ivu-icon ivu-icon-ios-arrow-forward"></i></div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{

        }
    },
    methods:{
        modalMenuClick(item){
        }
    }
}
</script>