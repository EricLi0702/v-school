<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                <DatePicker type="daterange" :options="options2" placement="bottom-end" placeholder="Select date" @on-change="changeDate"></DatePicker>
                <TimePicker type="timerange" format="HH" :steps="[1]" placeholder="Select time" @on-change="changeTime"></TimePicker>
                <!-- <DatePicker :value="addData.selDate" type="daterange" placement="bottom-end" placeholder="Select date" @on-change="setHoliday"></DatePicker> -->
                <Select v-model="addData.userRole">
                    <Option v-for="role in roleList" :value="role.roleName" :key="role.id">{{ role.roleName }}</Option>
                </Select>
            </div>
            <div class="es-item-right">
                <Button type="primary" @click="schedule" :loading="isLoading" :disabled="isLoading">提交</Button>
            </div>
        </div>
        <!-- <Table  ref="selection" :columns="tableColums" border :data="tableData" @on-selection-change="handleOnSelectionChange"></Table> -->
        <Table  ref="selection" :columns="tableColums" border :data="tableData"></Table>
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
                    type:'selection',
                    width:50,
                    align:'center',
                    fixed: 'left',
                },
                {
                    title:"Date",
                    key:"date",
                    align: 'center',
                    width: 200,
                    fixed: 'left',
                },
                {
                    title:"Day",
                    key:'day',
                    width:100,
                    fixed:'left',
                    align:'center'
                },
                {
                    title:"1",
                    key:"one",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"2",
                    key:"two",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"3",
                    key:"three",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"4",
                    key:"four",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"5",
                    key:"five",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"6",
                    key:"six",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"7",
                    key:"seven",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"8",
                    key:"eight",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"9",
                    key:"nine",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"10",
                    key:"ten",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"11",
                    key:"eleven",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"12",
                    key:"twelve",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"13",
                    key:"thirteen",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"14",
                    key:"fourteen",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"15",
                    key:"fifteen",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"16",
                    key:"sixteen",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"17",
                    key:"seventeen",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"18",
                    key:"eighteen",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"19",
                    key:"nineteen",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"20",
                    key:"twenty",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"21",
                    key:"twentyOne",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"22",
                    key:"twentyTwo",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"23",
                    key:"twentyThree",
                    width:70,
                    align:'center',
                    className:''
                },
                {
                    title:"24",
                    key:"twentyFour",
                    width:70,
                    align:'center',
                    className:''
                },
            ],
            // tableColums:[
            //     {
            //         type:'selection',
            //         width:50,
            //         align:'center',
            //         fixed: 'left',
            //     },
            //     {
            //         title:"Date",
            //         key:"date",
            //         align: 'center',
            //         width: 200,
            //         fixed: 'left',
            //     },
            //     {
            //         title:"Day",
            //         key:'day',
            //         width:100,
            //         fixed:'left',
            //         align:'center'
            //     },
            //     {
            //         title:"1",
            //         key:"1",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"2",
            //         key:"2",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"3",
            //         key:"3",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"4",
            //         key:"4",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"5",
            //         key:"5",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"6",
            //         key:"6",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"7",
            //         key:"7",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"8",
            //         key:"8",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"9",
            //         key:"9",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"10",
            //         key:"10",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"11",
            //         key:"11",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"12",
            //         key:"12",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"13",
            //         key:"13",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"14",
            //         key:"14",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"15",
            //         key:"15",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"16",
            //         key:"16",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"17",
            //         key:"17",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"18",
            //         key:"18",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"19",
            //         key:"19",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"20",
            //         key:"20",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"21",
            //         key:"21",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"22",
            //         key:"22",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"23",
            //         key:"23",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            //     {
            //         title:"24",
            //         key:"24",
            //         width:70,
            //         align:'center',
            //         cellClassName:{}
            //     },
            // ],
            allDates:[],
            isLoading:false,
            tableData:[],
            weekDay:['Su','Mo','Tu','We','Th','Fr','Sa'],
            roleList:[],
            options2: {
                shortcuts: [
                    {
                        text: '1 week',
                        value () {
                            const end = new Date();
                            const start = new Date();
                            start.setTime(start.getTime() - 3600 * 1000 * 24 * 7);
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
                // let element = {1:'',2:'',3:'',4:'',5:'',6:'',7:'',8:'',9:'',10:'',11:'',12:'',13:'',14:'',15:'',16:'',17:'',18:'',19:'',20:'',21:'',22:'',23:'',24:''}
                let element = {}
                let date = this.allDates[i].getFullYear() + '-' + (this.allDates[i].getMonth()+1) + '-' + this.allDates[i].getDate()
                let day = this.weekDay[this.allDates[i].getDay()]
                element.date = date
                element.day = day
                element.cellClassName = {}
                // element.one = ''
                // element.two = ''
                // element.three = ''
                // element.four = ''
                // element.five = ''
                // element.six = ''
                // element.seven = ''
                // element.eight = ''
                // element.nine = ''
                // element.ten = ''
                // element.eleven = ''
                // element.twelve = ''
                // element.thirteen = ''
                // element.fourteen = ''
                // element.fifteen = ''
                // element.sixteen = ''
                // element.seventeen = ''
                // element.eighteen = ''
                // element.nineteen = ''
                // element.twenty = ''
                // element.twentyOne = ''
                // element.twentyTwo = ''
                // element.twentyThree = ''
                // element.twentyFour = ''
                // element.selection = true
                this.tableData.push(element)
            }
        },
        changeTime(val){
            this.addData.selTime = val;
            let start = parseInt(val[0].split("-")[0])
            let end = parseInt(val[1].split("-")[0])
            // for(let i=0;i<this.tableColums.length;i++){
            //     this.tableColums[i].className = ''
            // }
            for(let i=0;i<this.tableData;i++){
                this.tableData[i].cellClassName = {}
            }
            if(start != end){
                // debugger
                for(let i=0;i<this.tableColums.length;i++){
                    if(parseInt(this.tableColums[i].title) >= start && parseInt(this.tableColums[i].title)<=end){
                        this.tableColums[i].className = 'demo-table-info-column'
                    }
                }
                // for(let i=0;i<this.tableData.length;i++){
                //     for(let j=1;j<=24;j++){
                //         if(j>= start && j<=end){
                //             console.log(j)
                //             this.tableData[i].cellClassName[j]='demo-table-info-column'
                //         }
                //     }
                //     // this.tableData[i].cellClassName.date='demo-table-info-column'
                // }
                // this.handleSelectAll(true);
                // console.log(this.tableColums)
            }
        },
        handleOnSelectionChange(val){
            console.log(val)
            
        },
        handleSelectAll (status) {
            this.$refs.selection.selectAll(status);
        },
        getDatesBetweenDates(startDate,endDate){
            let dates =[]
            // debugger
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
        /* padding:2plx 0px!important; */
    }
</style>