<template>
<div>
    <div v-if="currentPath.query.addQuestion == undefined">
        <div class="es-item">
            <div class="es-item-left">
                在
            </div>
            <div class="es-item-right">
                <TimePicker format="HH:mm" confirm type="timerange" placement="bottom-end" placeholder="Select time" style="width: 168px"></TimePicker>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                出
            </div>
            <div class="es-item-right">
                <TimePicker format="HH:mm" confirm type="timerange" placement="bottom-end" placeholder="Select time" style="width: 168px"></TimePicker>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                <span class="pr-3">出勤状态</span> 
                <Select v-model="curState" style="width:200px">
                    <Option v-for="item in addData.attStateList" :value="item.value" :key="item.value">{{ item.label }}</Option>
                </Select>
                <Icon type="ios-add" @click="showAdd = !showAdd" size="25" />
            </div>
            <div class="es-item-right" v-if="showAdd">
                <Input v-model="newState"/>
                <Button type="primary" @click="addState">提交</Button>
            </div>
        </div>
        <router-link :to="{path:currentPath.path,query:{applicationType:currentPath.query.applicationType,questionType:currentPath.query.questionType,addQuestion:'overAll'}}">
            <div class="es-item">
                <div class="es-item-left">
                    总体统计
                </div>
                <div class="es-item-right">
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
        </router-link>
        <router-link :to="{path:currentPath.path,query:{applicationType:currentPath.query.applicationType,questionType:currentPath.query.questionType,addQuestion:'detail'}}">
            <div class="es-item">
                <div class="es-item-left">
                    详情
                </div>
                <div class="es-item-right">
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
        </router-link>
        <router-link :to="{path:currentPath.path,query:{applicationType:currentPath.query.applicationType,questionType:currentPath.query.questionType,addQuestion:'schedule'}}">
            <div class="es-item">
                <div class="es-item-left">
                    时间表
                </div>
                <div class="es-item-right">
                    <Icon type="ios-arrow-forward"></Icon>
                </div>
            </div>
        </router-link>
        <div class="es-model-operate">
            <Button type="primary" @click="addState">提交</Button>
        </div>
    </div>
    <div v-else-if="currentPath.query.addQuestion == 'overAll'">
        <statistics></statistics>
    </div>
    <div v-else-if="currentPath.query.addQuestion == 'detail'">
        <detail></detail>
    </div>
    <div v-else-if="currentPath.query.addQuestion == 'schedule'">
        <schedule></schedule>
    </div>
</div>
</template>

<script>
import statistics from './statistics'
import detail from './detail'
import schedule from './schedule'
export default {
    components:{
        statistics,
        detail,
        schedule,
    },
    data(){
        return{
            addData:{
                cIn:'',
                cOut:'',
                attStateList:[
                {
                    value:"absent",
                    label:"缺席"
                },
                {
                    value:"late",
                    label:"晚了"
                },
                {
                    value:"early",
                    label:"早"
                }
            ],
            },
            
            curState:'',
            showAdd:false,
            newState:'',
        }
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    async created(){
        // const res = await this.CallApi('get')                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
    },
    methods:{
        addState(){
            let element = {}
            // this.$set(element,'value',this.newstate)
            // this.$set(element,'label',this.newState)
            element.value = this.newState
            element.label = this.newState
            this.addData.attStateList.push(element);
            this.newState = ''
        }
    }
}
</script>

<style>

</style>