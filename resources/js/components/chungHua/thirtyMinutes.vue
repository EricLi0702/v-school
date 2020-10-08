<template>
    <div>
        <div class="category-title"></div>
        <div v-for="(time,i) in thirtyMinutes" :key="i">
            <Checkbox @click.prevent.native="selTime(time)">
                <div class="es-item">
                    <div class="es-item-left">
                        {{time}}
                    </div>
                    <div class="es-item-right">
                        空闲30
                    </div>
                </div>
            </Checkbox>
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
            ]
        }
    },
    methods:{
        selTime(time){
            console.log(time)
        },
    }
}
</script>
<style scoped>
    .ivu-checkbox-wrapper{
        width:100%!important;
        display:flex!important;
    }
    .ivu-checkbox{
        margin-top: 13px!important;
    }
</style>