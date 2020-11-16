<template>
    <div>
        <div class="category-title">{{selMinutes}}</div>
        <div v-for="(time,i) in thirtyMinutes" :key="i">
            <div class="es-item">
                <div class="es-item-left" >
                    <input type="checkbox" :id="`${i}_chk`" :name="`${i}_chk`" :value="time" @change="selTime(time,`${i}_chk`)">
                    <label :for="`${i}_chk`"> {{time}}</label>
                </div>
                <div class="es-item-right">空闲30</div>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="addTime" :disabled="isLoading" :loading="isLoading">提交</Button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            thirtyMinutes:[
                '08:00~08:30',
                '08:30~09:00',
                '09:00~09:30',
                '09:30~10:00',
                '11:00~11:30',
                '11:30~12:00',
                '12:00~12:30',
                '12:30~13:00',
                '13:00~13:30',
                '13:30~14:00',
                '14:00~14:30',
                '14:30~15:00',
                '15:00~15:30',
                '15:30~16:00',
                '16:00~16:30',
                '16:30~17:00',
                '17:00~17:30',
                '17:30~18:00',
                '18:00~18:30',
                '18:30~19:00',
                '19:00~19:30',
                '19:30~20:00',
            ],
            selMinutes:[],
            isLoading:false,
        }
    },
    methods:{
        selTime(time,id){
            let ocheck = ($(`#${id}`).prop("checked") == true ? '1' : '0');
           if(ocheck == 1){
               this.selMinutes.push(time)
           }else{
               let index = this.selMinutes.indexOf(time)
               this.selMinutes.splice(index,1)
           }
        },
        addTime(){
            this.$emit('addTime',this.selMinutes.toString())
            this.$router.push(`${this.$route.path}?questionType=场所预约`)
        }
    }
}
</script>
<style scoped>
    .ivu-checkbox-wrapper{
        width:100%!important;
        display:flex!important;
    }
    .ivu-checkbox{
        margin-top: 15px!important;
    }
    label{
        padding-left:10px!important;
        margin-bottom:unset!important
    }
</style>