<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                <DatePicker type="daterange" :value="addData.selDate" :options="options2" placement="bottom-end" placeholder="Select date" @on-change="changeDate"></DatePicker>
                <TimePicker type="timerange" :value="addData.selTime" format="HH" :steps="[1]" placeholder="Select time" @on-change="changeTime"></TimePicker>
                <!-- <DatePicker :value="addData.selDate" type="daterange" placement="bottom-end" placeholder="Select date" @on-change="setHoliday"></DatePicker> -->
                <Select v-model="addData.userRole">
                    <Option v-for="role in roleList" :value="role.roleName" :key="role.id">{{ role.roleName }}</Option>
                </Select>
            </div>
            <div class="es-item-right">
                <Button type="primary" @click="schedule" :loading="isLoading" :disabled="isLoading">提交</Button>
            </div>
        </div>
        <Table  ref="selection" :columns="tableColums" border :data="tableData" @on-selection-change="setHoliday" @on-select-all="handleSelectAll"></Table>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                selDate:'',
                selTime:'',
                userRole:'',
            },
            tableColums:[
                {
                    type: 'selection',
                    width: 60,
                    align: 'center',
                    fixed: 'left',
                },
                {
                    title:"Date",
                    key:"date",
                    align: 'center',
                    width: 150,
                    fixed: 'left',
                },
                {
                    title:"Day",
                    key:'day',
                    width:70,
                    fixed:'left',
                    align:'center'
                },
                {
                    title:"1",
                    key:"one",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"2",
                    key:"two",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"3",
                    key:"three",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"4",
                    key:"four",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"5",
                    key:"five",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"6",
                    key:"six",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"7",
                    key:"seven",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"8",
                    key:"eight",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"9",
                    key:"nine",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"10",
                    key:"ten",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"11",
                    key:"eleven",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"12",
                    key:"twelve",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"13",
                    key:"thirteen",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"14",
                    key:"fourteen",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"15",
                    key:"fifteen",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"16",
                    key:"sixteen",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"17",
                    key:"seventeen",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"18",
                    key:"eighteen",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"19",
                    key:"nineteen",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"20",
                    key:"twenty",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"21",
                    key:"twentyOne",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"22",
                    key:"twentyTwo",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"23",
                    key:"twentyThree",
                    width:70,
                    align:'center',
                    // className:''
                },
                {
                    title:"24",
                    key:"twentyFour",
                    width:70,
                    align:'center',
                    // className:''
                },
            ],
            allDates:[],
            isLoading:false,
            tableData:[],
            weekDay:['Su','Mo','Tu','We','Th','Fr','Sa'],
            timeList:['zero','one','two','three','four','five','six','seven',
                        'eight','nine','ten','eleven','twelve','thirteen',
                        'fourteen','fifteen','sixteen','seventeen','eighteen',
                        'nineteen','twenty','twentyOne','twentyTwo','twentyThree',
                        'twentyFour'
                    ],
            roleList:[],
            options2: {
                shortcuts: [
                    {
                        text: '1 week',
                        value () {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 6);
                            return [start, end];
                        }
                    },
                    {
                        text: '1 month',
                        value () {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 30);
                            return [start, end];
                        }
                    },
                    {
                        text: '3 months',
                        value () {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 90);
                            return [start, end];
                        }
                    }
                ]
            }
        }
    },
    async created(){
        const res = await this.callApi('get','/api/role')
        if(res.status == 200){
            this.roleList = res.data
        }
    },
    methods:{
        schedule(){
            console.log(this.addData)
        },
        changeDate(val){
            this.addData.selDate = val
            this.tableData = []
            this.allDates = this.getDatesBetweenDates(val[0],val[1])
            for(let i=0;i<this.allDates.length;i++){
                let element = {}
                let date = this.allDates[i].getFullYear() + '-' + (this.allDates[i].getMonth()+1) + '-' + this.allDates[i].getDate()
                let day = this.weekDay[this.allDates[i].getDay()]
                element.date = date
                element.day = day
                element.cellClassName = {
                    date:'',
                    one:'',
                    two:'',
                    three:'',
                    four:'',
                    five:'',
                    six:'',
                    seven:'',
                    eight:'',
                    nine:'',
                    ten:'',
                    eleven:'',
                    twelve:'',
                    thirteen:'',
                    fourteen:'',
                    fifteen:'',
                    sixteen:'',
                    seventeen:'',
                    eighteen:'',
                    nineteen:'',
                    twenty:'',
                    twentyOne:'',
                    twentyTwo:'',
                    twentyThree:'',
                    twentyFour:'',
                }
                element.one = ''
                element.two = ''
                element.three = ''
                element.four = ''
                element.five = ''
                element.six = ''
                element.seven = ''
                element.eight = ''
                element.nine = ''
                element.ten = ''
                element.eleven = ''
                element.twelve = ''
                element.thirteen = ''
                element.fourteen = ''
                element.fifteen = ''
                element.sixteen = ''
                element.seventeen = ''
                element.eighteen = ''
                element.nineteen = ''
                element.twenty = ''
                element.twentyOne = ''
                element.twentyTwo = ''
                element.twentyThree = ''
                element.twentyFour = ''
                // element._checked = true
                this.tableData.push(element)
            }
            this.addData.selTime = ''
        },
        changeTime(val){
            
            this.addData.selTime = val;
            let start = parseInt(val[0].split("-")[0])
            let end = parseInt(val[1].split("-")[0])
            for(let i=0;i<this.tableData.length;i++){
                this.tableData[i].cellClassName = {
                    date:'',
                    one:'',
                    two:'',
                    three:'',
                    four:'',
                    five:'',
                    six:'',
                    seven:'',
                    eight:'',
                    nine:'',
                    ten:'',
                    eleven:'',
                    twelve:'',
                    thirteen:'',
                    fourteen:'',
                    fifteen:'',
                    sixteen:'',
                    seventeen:'',
                    eighteen:'',
                    nineteen:'',
                    twenty:'',
                    twentyOne:'',
                    twentyTwo:'',
                    twentyThree:'',
                    twentyFour:'',
                }
            }
            if(!isNaN(start)){
                if(start != end){
                    for(let i=0;i<this.tableData.length;i++){
                        for(let j=1;j<=24;j++){
                            if(j>= start && j<=end){
                                let cellTime = this.timeList[j]
                                this.tableData[i].cellClassName[cellTime] = 'demo-table-info-column'
                            }
                        }
                        this.tableData[i].cellClassName.date='demo-table-info-column'
                        this.tableData[i].cellClassName.day='demo-table-info-column'
                    }
                    this.handleSelectAll(this.tableData)
                }
            }
        },
        handleSelectAll (val) {
            console.log('********')
            console.log(val)
            console.log('=========')
        },
        setHoliday(val){
            console.log('+++++')
            console.log(val)
            console.log('-----')
        },
        getDatesBetweenDates(startDate,endDate){
            let dates =[]
            const theDate = new Date(startDate)
            endDate = new Date(endDate)
            while(theDate <= endDate){
                dates = [...dates,new Date(theDate)]
                theDate.setDate(theDate.getDate()+1)
            }
            return dates
        }
    }
}
</script>

<style>
    .ivu-table td.demo-table-info-column{
        background-color: #2db7f5;
        color: #fff;
    }
</style>