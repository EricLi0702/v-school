<template>
    <div>
        <div>
            <div class="es-item">
                <Input v-model="addData.title" class="customInput w-100" placeholder="考试名称"/>
            </div>
            <div class="es-item">
                <Input v-model="addData.title" class="customInput w-100" placeholder="考试时间"/>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    向学生家长显示排名
                </div>
                <div class="es-item-right">
                    <Switch v-model="switch1" @on-change="change" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    向学生家长显示平均分
                </div>
                <div class="es-item-right">
                    <Switch v-model="switch1" @on-change="change" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    向学生家长显示分数
                </div>
                <div class="es-item-right">
                    <Switch v-model="switch1" @on-change="change" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    向学生家长显示分数
                </div>
                <div class="es-item-right">
                    <Switch v-model="switch1" @on-change="change" />
                </div>
            </div>
        </div>

        <div>

        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                examName:'',
                examTime:'',
                rankingShow:false,
                averageScore:false,
                allScore:false,
                ladderSetting:[]
            }
        }
    }
}
</script>

<style>

</style>