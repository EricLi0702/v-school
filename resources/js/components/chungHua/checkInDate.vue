<template>
    <div>
        <div class="es-item">
            <Checkbox
                :value="checkAll"
                @click.prevent.native="handleCheckAll">每天</Checkbox>
        </div>
        <CheckboxGroup v-model="checkAllGroup" @on-change="checkAllGroupChange">
            <div class="es-item">
                <Checkbox label="每周一">每周一</Checkbox>
            </div>
            <div class="es-item">
                <Checkbox label="每周二">每周二</Checkbox>
            </div>
            <div class="es-item">
                <Checkbox label="每周三">每周三</Checkbox>
            </div>
            <div class="es-item">
                <Checkbox label="每周四">每周四</Checkbox>
            </div>
            <div class="es-item">
                <Checkbox label="每周五">每周五</Checkbox>
            </div>
            <div class="es-item">
                <Checkbox label="每周六">每周六</Checkbox>
            </div>
            <div class="es-item">
                <Checkbox label="每周日">每周日</Checkbox>
            </div>
        </CheckboxGroup>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                打卡周期
            </div>
            <div class="es-item-right">
                <Input v-model="addData.clockCycle" class="customInput rightToLeft"/>
                <span>天</span>
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="primary" @click="submit">提交</Button>
            <!-- <Button type="default" @click="draft" :disabled="isDrafting" :loading="isDrafting">存草稿</Button> -->
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            checkAllGroup:['每周一','每周二','每周三','每周四','每周五','每周六','每周日'],
            checkAll:true,
            addData:{
                clockCycle:21,
                selDate:['每周一','每周二','每周三','每周四','每周五','每周六','每周日']
            }
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    methods:{
        handleCheckAll () {
            this.checkAll = !this.checkAll;
            
            if (this.checkAll) {
                this.checkAllGroup = ['每周一','每周二','每周三','每周四','每周五','每周六','每周日'];
                
            } else {
                this.checkAllGroup = [];

            }
            this.addData.selDate = this.checkAllGroup
        },
        checkAllGroupChange (data) {
            if (data.length === 7) {
                this.checkAll = true;
            }
            else {
                this.checkAll = false;
            }
            this.addData.selDate = data
        },
        submit(){
            if(this.addData.clockCycle<9 || this.addData.clockCycle > 70){
                return this.error('周期天数不能超出天数范围（7~90）')
            }
            this.$emit('checkInTime',this.addData)
            this.$router.push(`${this.currentPath.path}?applicationType=养成打卡&questionType=养成打卡`)
        },
    }
}
</script>