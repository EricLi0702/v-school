<template>
    <div class="container-fluid p-0 m-0">
        <div class="es-item">
            <div class="es-itme-left">
                <DatePicker type="daterange" placement="bottom-end" v-model="selDate" placeholder="Select date" style="width: 200px"></DatePicker>
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward"></Icon>
            </div>
        </div>
        <div class="es-item">
            <div class="es-itme-left">
                <Input v-model="searchName" />
            </div>
            <div class="es-item-right">
                <Button type="primary" @click="search" :loading="isLoading" :disabled="isLoading">提交</Button>
            </div>
        </div>
        <!-- <div class="bs-example container-fluid" data-example-id="striped-table">
            <table  id="test" class="table table-striped table-bordered table-hover">
                    <tr>
                        <td colspan="2">Personal</td>
                        <td rowspan="2" class="top">Attendance Date</td>
                        <td rowspan="2" class="top">Week</td>
                        <td rowspan="2" class="top">Punch time</td>
                        <td rowspan="2" class="top">Punch count</td>
                        <td rowspan="2" class="top">Late Count</td>
                        <td rowspan="2" class="top">Late Minutes</td>
                        <td colspan="3">Time Detail</td>
                        <td colspan="2">Abnormal Status</td>
                    </tr>
                    <tr>
                        <td scope="row">ID</td>
                        <td>Name</td>
                        <td>Attend Time</td>
                        <td>Off Work Time</td>
                        <td>Working Time</td>
                        <td>Absent</td>
                        <td>Early leave</td>
                    </tr>
            </table>
        </div> -->
        <Table :columns="attendance" :data="attendanceDate" border show-summary :span-method="handleDate"  height="570"></Table>           		
    </div>
</template>

<script>
export default {
    data(){
        return{
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
            isLoading:false,
            searchName:''
        }
    },
    methods:{
        changeDate(val){
            console.log('date',val)
            this.selDate = val
            this.$emit('onChange', val)
        },
        async search(){
            if(this.selDate == null || this.selDate[0] == ''){
                return this.error('select date range')
            }
            if(this.searchName == ''){
                return this.error('name');
            }
            // debugger
            this.isLoading = true
            console.log(this.selDate)
            await axios.get('/api/attendanceDetail',{params:{date:this.selDate,name:this.searchName}})
                    .then(res=>{
                        console.log(res)
                        this.attendanceDate = []
                        for(let i=0;i<res.data.length;i++){
                            let element = {}
                            let resData = res.data[i]
                            console.log(resData.workday)
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
                        console.log(err)
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