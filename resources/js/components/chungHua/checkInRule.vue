<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                打卡信息成员可见
            </div>
            <div class="es-item-right">
                <i-switch true-color="#13ce66" v-model="addData.visibale" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                允许补打卡 
            </div>
            <div class="es-item-right w-50">
                <Input v-model="addData.allowCheckIn" class="customInput rightToLeft" placeholder="选填"/>
                <span>次</span>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                打卡提醒时间
            </div>
            <div class="es-item-right">
                <TimePicker format="HH:mm" confirm placeholder="选择时间" :value="addData.reminderTime" style="width: 112px" @on-open-change="handleClick" @on-change="handleChange" @on-clear="handleClear" @on-ok="handleOk"></TimePicker>
            </div>
        </div>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                打卡奖励 
            </div>
            <div class="es-item-right">
                选填
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                奖励条件
            </div>
            <div class="es-item-right w-50">
                <Input v-model="addData.awardConditions" class="customInput rightToLeft" placeholder="选填"/>
                <span>次</span>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="submit">提交</Button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                visibale:false,
                allowCheckIn:'',
                reminderTime:'',
                checkInRewards:'',
                awardConditions:''
            }
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods: {
        handleClick () {
            console.log('handleClick')
            let today = new Date();
            console.log('currentTime',`${today.getHours()}:${today.getMinutes()}`)
            this.addData.reminderTime = `${today.getHours()}:${today.getMinutes()}`
        },
        handleChange (time) {
            this.addData.reminderTime = time;
        },
        handleClear () {
            this.addData.reminderTime = ''
        },
        handleOk () {
            
        },
        submit(){
            this.$emit('checkInRule',this.addData);
            this.$router.push(`${this.currentPath.path}?applicationType=养成打卡&questionType=养成打卡`)
        }
    }
}
</script>