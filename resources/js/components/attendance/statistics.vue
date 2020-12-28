<template>
    <div class="container-fluid p-0 m-0">
        <Tabs value="date">
            <TabPane label="天统计" name="date">
                <div class="item-1">
                    <div class="es-item">
                        <div class="es-item-left">
                            日期范围
                        </div>
                        <div class="es-item-right">
                            <DatePicker type="daterange" placement="bottom-end" placeholder="选择日期" @on-change="changeDate"></DatePicker>
                            <Button type="primary" @click="fetchData" :loading="isLoading" :disabled="isLoading">提交</Button>
                        </div>
                    </div>
                    <Table :columns="attendance" :data="attendanceDate" border show-summary :span-method="handleDate"  height="570"></Table>
                </div>
            </TabPane>
            <TabPane label="月统计" name="month">
                <div class="item-2">
                    <div class="es-item">
                        <div class="es-item-left">
                            月
                        </div>
                        <div class="es-item-right">
                            <DatePicker type="month" placeholder="选择一个月" @on-change="changeMonth"></DatePicker>
                            <Button type="primary" @click="fetchMonth" :loading="isLoading" :disabled="isLoading">提交</Button>
                        </div>
                    </div>
                    <Table :columns="attendance" :data="attendanceMonth" border show-summary :span-method="handleDate"  height="570"></Table>
                </div>
            </TabPane>
            <TabPane label="年统计" name="year">
                <div class="item-3">
                    <div class="es-item">
                        <div class="es-item-left">
                            年
                        </div>
                        <div class="es-item-right">
                            <DatePicker type="year" placeholder="选择年份" @on-change="changeYear"></DatePicker>
                            <Button type="primary" @click="fetchYear" :loading="isLoading" :disabled="isLoading">提交</Button>
                        </div>
                    </div>
                    
                    <Table :columns="attendance" :data="attendanceYear" border show-summary :span-method="handleDate"  height="570"></Table>
                </div>
            </TabPane>
        </Tabs>
    </div>
</template>

<script>
export default {
    data(){
        return{
            selYear:null,
            selMonth:null,
            selDate:null,
            attendance:[
                {
                    title:'日期',
                    key:'date',
                    align: 'center',
                    width: 200,
                    fixed: 'left',
                    sortable: true,
                },
                {
                    title:'名称',
                    key:'name',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'应该工作时间',
                    key:'scheduleTimes',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'实际工作时间',
                    key:'workedTimes',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'迟',
                    key:'lateCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'提早休假',
                    key:'earlyLeaveCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'缺席',
                    key:'absentCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'疾病',
                    key:'sickCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'商务旅行',
                    key:'tripCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
            ],
            attendanceDate:[
                // {
                //     date:'2020-10-01',
                //     name:'test1',
                //     scheduleTimes:40,
                //     workedTimes:35,
                //     lateCnt:1,
                //     earlyLeaveCnt:1,
                //     absentCnt:1,
                //     sickCnt:1,
                //     tripCnt:1
                // }
            ],
            attendanceMonth:[],
            attendanceYear:[],
            isLoading:false
        }
    },
    mounted(){
    },
    methods:{
        changeYear(val){
            this.selYear = val
            this.$emit('onChange', val)
        },
        changeMonth(val){
            this.selMonth = val
            this.$emit('onChange', val)
        },
        changeDate(val){
            this.selDate = val
            this.$emit('onChange', val)
        },
        async fetchData(){
            if(this.selDate == null){
                return this.error('select date range')
            }
            // debugger
            this.isLoading = true
            await axios.get('/api/attendance',{params:{date:this.selDate}})
                    .then(res=>{
                        this.attendanceDate = []
                        for(let i=0;i<res.data.length;i++){
                            let element = {}
                            let resData = res.data[i]
                            element.date = resData.workday
                            element.name = resData.user.name
                            element.scheduleTimes = resData.scheduleTimes
                            element.workedTimes = resData.workedTimes
                            element.lateCnt = resData.lateCnt
                            element.earlyLeaveCnt = resData.earlyLeaveCnt
                            element.absentCnt = resData.absentCnt
                            element.sickCnt = resData.sickCnt
                            element.tripCnt = resData.tripCnt
                            this.attendanceDate.push(element)
                        }
                    })
                    .catch(err=>{
                    })
            this.isLoading = false
        },
        async fetchMonth(){
            if(this.selMonth == null || this.selMonth.length == 0){
                return this.error('select month')
            }
            this.isLoading = true
            await axios.get('/api/attendanceMonth',{params:{date:this.selMonth}})
                    .then(res=>{
                        this.attendanceMonth = []
                        for(let i=0;i<res.data.length;i++){
                            let element = {}
                            let resData = res.data[i]
                            element.date = resData.workday
                            element.name = resData.user.name
                            element.scheduleTimes = resData.scheduleTimes
                            element.workedTimes = resData.workedTimes
                            element.lateCnt = resData.lateCnt
                            element.earlyLeaveCnt = resData.earlyLeaveCnt
                            element.absentCnt = resData.absentCnt
                            element.sickCnt = resData.sickCnt
                            element.tripCnt = resData.tripCnt
                            this.attendanceMonth.push(element)
                        }
                    })
                    .catch(err=>{
                    })
            this.isLoading = false
        },
        async fetchYear(){
            if(this.selYear == null || this.selYear.length == 0){
                return this.error('select month')
            }
            this.isLoading = true
            await axios.get('/api/attendanceYear',{params:{date:this.selYear}})
                    .then(res=>{
                        this.attendanceYear = []
                        for(let i=0;i<res.data.length;i++){
                            let element = {}
                            let resData = res.data[i]
                            element.date = resData.workday
                            element.name = resData.user.name
                            element.scheduleTimes = resData.scheduleTimes
                            element.workedTimes = resData.workedTimes
                            element.lateCnt = resData.lateCnt
                            element.earlyLeaveCnt = resData.earlyLeaveCnt
                            element.absentCnt = resData.absentCnt
                            element.sickCnt = resData.sickCnt
                            element.tripCnt = resData.tripCnt
                            this.attendanceYear.push(element)
                        }
                    })
                    .catch(err=>{
                    })
            this.isLoading = false
        },
        handleDate({ row, column, rowIndex, columnIndex }){
            
        },
    }
}
</script>

<style>

</style>