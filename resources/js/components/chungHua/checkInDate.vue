<template>
    <div class="select-class-target">
        <div class="vx-item is-click" @click="toggleCheckAll">
            <Checkbox
                :value="checkAll"
                @click.prevent.native="handleCheckAll">每天</Checkbox>
        </div>
        <CheckboxGroup v-model="checkAllGroup" @on-change="checkAllGroupChange">
            <div class="vx-item is-click" @click="toggleCheckItem(1)">
                <Checkbox v-model="checkItem1" label="每周一">每周一</Checkbox>
            </div>
            <div class="vx-item is-click" @click="toggleCheckItem(2)">
                <Checkbox v-model="checkItem2" label="每周二">每周二</Checkbox>
            </div>
            <div class="vx-item is-click" @click="toggleCheckItem(3)">
                <Checkbox v-model="checkItem3" label="每周三">每周三</Checkbox>
            </div>
            <div class="vx-item is-click" @click="toggleCheckItem(4)">
                <Checkbox v-model="checkItem4" label="每周四">每周四</Checkbox>
            </div>
            <div class="vx-item is-click" @click="toggleCheckItem(5)">
                <Checkbox v-model="checkItem5" label="每周五">每周五</Checkbox>
            </div>
            <div class="vx-item is-click" @click="toggleCheckItem(6)">
                <Checkbox v-model="checkItem6" label="每周六">每周六</Checkbox>
            </div>
            <div class="vx-item is-click" @click="toggleCheckItem(7)">
                <Checkbox v-model="checkItem7" label="每周日">每周日</Checkbox>
            </div>
        </CheckboxGroup>
        <div class="category-title"></div>
        <div class="vx-item">
            <div class="vx-item-left">
                打卡周期
            </div>
            <div class="vx-item-right">
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
            },
            checkItem1:true,
            checkItem2:true,
            checkItem3:true,
            checkItem4:true,
            checkItem5:true,
            checkItem6:true,
            checkItem7:true,
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    methods:{
        toggleCheckAll(){
            this.handleCheckAll();
        },
        handleCheckAll () {
            this.checkAll = !this.checkAll;
            
            if (this.checkAll) {
                this.checkAllGroup = ['每周一','每周二','每周三','每周四','每周五','每周六','每周日'];
                this.checkItem1 = true;
                this.checkItem2 = true;
                this.checkItem3 = true;
                this.checkItem4 = true;
                this.checkItem5 = true;
                this.checkItem6 = true;
                this.checkItem7 = true;
                
            } else {
                this.checkAllGroup = [];
                this.checkItem1 = false;
                this.checkItem2 = false;
                this.checkItem3 = false;
                this.checkItem4 = false;
                this.checkItem5 = false;
                this.checkItem6 = false;
                this.checkItem7 = false;
            }
            this.addData.selDate = this.checkAllGroup
        },
        toggleCheckItem(index){
            switch(index) {
                case 1:
                    if(this.checkItem1 == true){
                        const index = this.checkAllGroup.indexOf('每周一');
                        if (index > -1) {
                            this.checkAllGroup.splice(index, 1);
                        }
                        this.checkItem1 = false;
                    }
                    else{
                        this.checkAllGroup.push('每周一');
                        this.checkItem1 = true;
                    }
                    break;
                case 2:
                    if(this.checkItem2 == true){
                        const index = this.checkAllGroup.indexOf('每周二');
                        if (index > -1) {
                            this.checkAllGroup.splice(index, 1);
                        }
                        this.checkItem2 = false;    
                    }
                    else{
                        this.checkAllGroup.push('每周二');
                        this.checkItem2 = true;    
                    }
                    break;
                case 3:
                    if(this.checkItem3 == true){
                        const index = this.checkAllGroup.indexOf('每周三');
                        if (index > -1) {
                            this.checkAllGroup.splice(index, 1);
                        }
                        this.checkItem3 = false;
                    }
                    else{
                        this.checkAllGroup.push('每周三');
                        this.checkItem3 = true;
                    }
                    break;
                case 4:
                    if(this.checkItem4 == true){
                        const index = this.checkAllGroup.indexOf('每周四');
                        if (index > -1) {
                            this.checkAllGroup.splice(index, 1);
                        }
                        this.checkItem4 = false;
                    }
                    else{
                        this.checkAllGroup.push('每周四');
                        this.checkItem4 = true;
                    }
                    break;
                case 5:
                    if(this.checkItem5 == true){
                        const index = this.checkAllGroup.indexOf('每周五');
                        if (index > -1) {
                            this.checkAllGroup.splice(index, 1);
                        }
                        this.checkItem5 = false;
                    }
                    else{
                        this.checkAllGroup.push('每周五');
                        this.checkItem5 = true;
                    }
                    break;
                case 6:
                    if(this.checkItem6 == true){
                        const index = this.checkAllGroup.indexOf('每周六');
                        if (index > -1) {
                            this.checkAllGroup.splice(index, 1);
                        }
                        this.checkItem6 = false;
                    }
                    else{
                        this.checkAllGroup.push('每周六');
                        this.checkItem6 = true;
                    }
                    break;
                case 7:
                    if(this.checkItem7 == true){
                        const index = this.checkAllGroup.indexOf('每周日');
                        if (index > -1) {
                            this.checkAllGroup.splice(index, 1);
                        }
                        this.checkItem7 = false;
                    }
                    else{
                        this.checkAllGroup.push('每周日');
                        this.checkItem7 = true;
                    }
                    break;
                }
            this.checkAllGroupChange(this.checkAllGroup);
        },
        checkAllGroupChange (data) {
            console.log("*********8", data);
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