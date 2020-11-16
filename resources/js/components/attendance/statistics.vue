<template>
    <div class="container-fluid p-0 m-0">
        <Tabs value="date">
            <TabPane label="天统计" name="date">
                <div class="item-1">
                    <div class="es-item">
                        <div class="es-item-left">
                            dateRange
                        </div>
                        <div class="es-item-right">
                            <DatePicker type="daterange" placement="bottom-end" placeholder="Select date" @on-change="changeDate"></DatePicker>
                            <Button type="primary" @click="fetchData" :loading="isLoading" :disabled="isLoading">提交</Button>
                        </div>
                    </div>
                    <!-- <div class="mt-2 attendance-table-date" data-example-id="striped-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Should Work Hours</th>
                                    <th>Actual Work Hours</th>
                                    <th>Late</th>
                                    <th>Early Leave</th>
                                    <th>Absent</th>
                                    <th>Sickness</th>
                                    <th>Business Trip</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="index in 100" :key="index">
                                    <td>1</td>
                                    <td>2</td>
                                    <td>3</td>
                                    <td>4</td>
                                    <td>5</td>
                                    <td>6</td>
                                    <td>7</td>
                                    <td>8</td>
                                    <td>9</td>
                                </tr>
                            </tbody>
                        </table>
                    </div> -->
                    <Table :columns="attendance" :data="attendanceDate" border show-summary :span-method="handleDate"  height="570"></Table>
                </div>
            </TabPane>
            <TabPane label="月统计" name="month">
                <div class="item-2">
                    <div class="es-item">
                        <div class="es-item-left">
                            month
                        </div>
                        <div class="es-item-right">
                            <DatePicker type="month" placeholder="Select month" @on-change="changeMonth"></DatePicker>
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
                            year
                        </div>
                        <div class="es-item-right">
                            <DatePicker type="year" placeholder="Select year" @on-change="changeYear"></DatePicker>
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
                    title:'Date',
                    key:'date',
                    align: 'center',
                    width: 200,
                    fixed: 'left',
                    sortable: true,
                },
                {
                    title:'Name',
                    key:'name',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'Should Work Hours',
                    key:'scheduleTimes',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'Actual Work Hours',
                    key:'workedTimes',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'Late',
                    key:'lateCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'Early Leave',
                    key:'earlyLeaveCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'Absent',
                    key:'absentCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'Sickness',
                    key:'sickCnt',
                    align: 'center',
                    width: 100,
                    sortable: true,
                },
                {
                    title:'Business Trip',
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