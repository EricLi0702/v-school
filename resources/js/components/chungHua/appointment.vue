<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="`${currentPath.path}?questionType=场所预约&addQuestion=选择使用人`">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>使用人</span>
                    </div>
                    <div class="es-item-right">
                        <span>{{addData.userName}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>        
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    <span>类型</span>
                </div>
                <div class="es-item-right">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType($event)">
                        <a href="javascript:void(0)">
                            {{addData.type}}
                            <Icon type="ios-arrow-forward" />
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="办公室">办公室</DropdownItem>
                            <DropdownItem name="课室">课室</DropdownItem>
                            <DropdownItem name="公共区域">公共区域</DropdownItem>
                            <DropdownItem name="食堂">食堂</DropdownItem>
                            <DropdownItem name="会议室">会议室</DropdownItem>
                            <DropdownItem name="校门">校门</DropdownItem>
                            <DropdownItem name="其他">其他</DropdownItem>
                            <DropdownItem name="宿舍">宿舍</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    日期
                </div>
                <div class="es-item-right">
                    <DatePicker type="datetime" v-model="addData.deadline" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    时段
                </div>
                <div class="es-item-right">
                    <span>
                    必填 
                    </span>
                    <Icon type="ios-arrow-forward" />

                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    场所
                </div>
                <div class="es-item-right">
                    <span>
                    必填 
                    </span>
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            <textarea name="" id="" v-model="addData.description" class="text-content" cols="30" rows="10" placeholder="用途说明"></textarea>
        </div>
        <div v-else-if="currentPath.query.addQuestion='选择使用人'">
            <contactComponent></contactComponent>
        </div>
    </div>
</template>

<script>
import contactComponent from './contactComponent'
export default {
    components:{
        contactComponent,
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    data(){
        return{
            addData:{
                userName:'test',
                type:'办公室',
                deadline:'',
                timePeriod:'',
                place:'',
                description:''
            }
        }
    },
    methods:{
        chooseType($event){
            this.addData.type=$event;
        }
    }
}
</script>