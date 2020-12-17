<template>
    <div>
        <div class="category-title">
            当前状态
        </div>
        
        <div class="vx-item">
            <div class="vx-item-left" v-if="currentState != null">
                <Tag v-if="currentState == '在办公室'" color="primary">{{currentState}}</Tag>
                <Tag v-else-if="currentState == '上课中'" color="success">{{currentState}}</Tag>
                <Tag v-else-if="currentState == '会议中'" color="warning">{{currentState}}</Tag>
                <Tag v-else-if="currentState == '待客中'" color="magenta">{{currentState}}</Tag>
                <Tag v-else-if="currentState == '忙碌中'" color="error">{{currentState}}</Tag>
                <Tag v-else-if="currentState == '外出中'" color="red">{{currentState}}</Tag>
            </div>
            <div class="vx-item-right" v-if="scheduleTime != null">
                <Tag color="geekblue">{{scheduleTime}}</Tag>
            </div>
        </div>
        <div class="category-title">

        </div>
        <div class="vx-item is-click" @click="toggleStatus(1)">
            <div class="vx-item-left">
                在办公室
            </div>
            <div class="vx-item-right">
                <Dropdown style="margin-left: 20px" :visible="isVisibleStatus1" placement="bottom-end" trigger="custom" @on-click="chooseType('在办公室',$event)">
                    <a href="javascript:void(0)">
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="0.5">0.5小时</DropdownItem>
                        <DropdownItem name="1.0">1.0小时</DropdownItem>
                        <DropdownItem name="1.5">1.5小时</DropdownItem>
                        <DropdownItem name="2.0">2.0小时</DropdownItem>
                        <DropdownItem name="2.5">2.5小时</DropdownItem>
                        <DropdownItem name="3.0">3.0小时</DropdownItem>
                        <!-- <DropdownItem name="自定义">自定义</DropdownItem> -->
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="vx-item is-click" @click="toggleStatus(2)">
            <div class="vx-item-left">
                上课中
            </div>
            <div class="vx-item-right">
                <Dropdown style="margin-left: 20px" :visible="isVisibleStatus2" placement="bottom-end" trigger="custom" @on-click="chooseType('上课中',$event)">
                    <a href="javascript:void(0)">
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="0.5">0.5小时</DropdownItem>
                        <DropdownItem name="1.0">1.0小时</DropdownItem>
                        <DropdownItem name="1.5">1.5小时</DropdownItem>
                        <DropdownItem name="2.0">2.0小时</DropdownItem>
                        <DropdownItem name="2.5">2.5小时</DropdownItem>
                        <DropdownItem name="3.0">3.0小时</DropdownItem>
                        <!-- <DropdownItem name="自定义">自定义</DropdownItem> -->
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="vx-item is-click" @click="toggleStatus(3)">
            <div class="vx-item-left">
                会议中
            </div>
            <div class="vx-item-right">
                <Dropdown style="margin-left: 20px" :visible="isVisibleStatus3" placement="bottom-end" trigger="custom" @on-click="chooseType('会议中',$event)">
                    <a href="javascript:void(0)">
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="0.5">0.5小时</DropdownItem>
                        <DropdownItem name="1.0">1.0小时</DropdownItem>
                        <DropdownItem name="1.5">1.5小时</DropdownItem>
                        <DropdownItem name="2.0">2.0小时</DropdownItem>
                        <DropdownItem name="2.5">2.5小时</DropdownItem>
                        <DropdownItem name="3.0">3.0小时</DropdownItem>
                        <!-- <DropdownItem name="自定义">自定义</DropdownItem> -->
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="vx-item is-click" @click="toggleStatus(4)">
            <div class="vx-item-left">
                待客中
            </div>
            <div class="vx-item-right">
                <Dropdown style="margin-left: 20px" :visible="isVisibleStatus4" placement="bottom-end" trigger="custom" @on-click="chooseType('待客中',$event)">
                    <a href="javascript:void(0)">
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="0.5">0.5小时</DropdownItem>
                        <DropdownItem name="1.0">1.0小时</DropdownItem>
                        <DropdownItem name="1.5">1.5小时</DropdownItem>
                        <DropdownItem name="2.0">2.0小时</DropdownItem>
                        <DropdownItem name="2.5">2.5小时</DropdownItem>
                        <DropdownItem name="3.0">3.0小时</DropdownItem>
                        <!-- <DropdownItem name="自定义">自定义</DropdownItem> -->
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="vx-item is-click" @click="toggleStatus(5)">
            <div class="vx-item-left">
                忙碌中
            </div>
            <div class="vx-item-right">
                <Dropdown style="margin-left: 20px" :visible="isVisibleStatus5" placement="bottom-end" trigger="custom" @on-click="chooseType('忙碌中',$event)">
                    <a href="javascript:void(0)">
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="0.5">0.5小时</DropdownItem>
                        <DropdownItem name="1.0">1.0小时</DropdownItem>
                        <DropdownItem name="1.5">1.5小时</DropdownItem>
                        <DropdownItem name="2.0">2.0小时</DropdownItem>
                        <DropdownItem name="2.5">2.5小时</DropdownItem>
                        <DropdownItem name="3.0">3.0小时</DropdownItem>
                        <!-- <DropdownItem name="自定义">自定义</DropdownItem> -->
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="vx-item is-click" @click="toggleStatus(6)">
            <div class="vx-item-left">
                外出中
            </div>
            <div class="vx-item-right">
                <Dropdown style="margin-left: 20px" :visible="isVisibleStatus6" placement="bottom-end" trigger="custom" @on-click="chooseType('外出中',$event)">
                    <a href="javascript:void(0)">
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="0.5">0.5小时</DropdownItem>
                        <DropdownItem name="1.0">1.0小时</DropdownItem>
                        <DropdownItem name="1.5">1.5小时</DropdownItem>
                        <DropdownItem name="2.0">2.0小时</DropdownItem>
                        <DropdownItem name="2.5">2.5小时</DropdownItem>
                        <DropdownItem name="3.0">3.0小时</DropdownItem>
                        <!-- <DropdownItem name="自定义">自定义</DropdownItem> -->
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <!-- <div v-for="index1 in count1" :key="index1">
            <stateComponent
                :index="index1"
                :contentType="'stateContent'"
                @contentData="stateContentData"
            ></stateComponent>
        </div> -->
        <div class="category-title"></div>
        <!-- <div class="vx-item is-click" @click="addState">
            <div class="vx-item-left">
                <Icon type="md-add" />新增
            </div>
        </div> -->
    </div>
</template>

<script>
export default {
    data(){
        return{
            currentState:null,
            scheduleTime:null,
            addModal:false,
            addStateData:null,
            count1:0,
            isVisibleStatus1:false,
            isVisibleStatus2:false,
            isVisibleStatus3:false,
            isVisibleStatus4:false,
            isVisibleStatus5:false,
            isVisibleStatus6:false,
        }
    },
    async created(){
        const con = await this.callApi('get','/api/users/status');
        if(con.status == 200){
            console.log("^^^^^^^^^^^", con.data);
            if(con.data.isChanged == 2 || con.data.isChanged == 1){
                this.currentState = "在办公室";
                this.scheduleTime = "";
            }
            else if(con.data.isChanged == 0){
                switch (con.data.status) {
                    case 1:
                        this.currentState = "在办公室";
                        break;
                    case 2:
                        this.currentState = "上课中";
                        break;
                    case 3:
                        this.currentState = "会议中";
                        break;
                    case 4:
                        this.currentState = "待客中";
                        break;
                    case 5:
                        this.currentState = "忙碌中";
                        break;
                    case 6:
                        this.currentState = "外出中";
                        break;
                }
                this.scheduleTime = `${this.TimeViewDHM(con.data.statusFrom)} ~ ${this.TimeViewDHM(con.data.statusTo)}`;
            }
        }
        else{
            this.swr();
        }
    },
    methods:{
        async chooseType(state,$event){
            let duringMinute = parseInt(parseFloat($event)*60);
            let currentTime = new Date()
            let afterTime = new Date()
            afterTime.setMinutes(afterTime.getMinutes() + duringMinute) 
            this.currentState = state;
            this.scheduleTime = `${this.TimeViewDHM(currentTime)} ~ ${this.TimeViewDHM(afterTime)}`;

            let payload = {
                status : null,
                statusFrom : this.TimeView(currentTime),
                statusTo : this.TimeView(afterTime)
            }
            switch (state) {
                case '在办公室':
                    payload.status = 1;
                    break;
                case '上课中':
                    payload.status = 2;
                    break;
                case '会议中':
                    payload.status = 3;
                    break;
                case '待客中':
                    payload.status = 4;
                    break;
                case '忙碌中':
                    payload.status = 5;
                    break;
                case '外出中':
                    payload.status = 6;
                    break;
            
            }

            const res = await this.callApi('put', '/api/profile/status', payload);
            if(res.status == 200){
                this.success('修改成功');
            }
            else{
                this.swr();
            }
        },
        addState(){
            
        },

        toggleStatus(index){
            switch (index) {
                case 1:
                    this.isVisibleStatus1 = !this.isVisibleStatus1;
                    this.isVisibleStatus2 = false;
                    this.isVisibleStatus3 = false;
                    this.isVisibleStatus4 = false;
                    this.isVisibleStatus5 = false;
                    this.isVisibleStatus6 = false;
                    break;
                case 2:
                    this.isVisibleStatus2 = !this.isVisibleStatus2;
                    this.isVisibleStatus1 = false;
                    this.isVisibleStatus3 = false;
                    this.isVisibleStatus4 = false;
                    this.isVisibleStatus5 = false;
                    this.isVisibleStatus6 = false;
                    break;
                case 3:
                    this.isVisibleStatus3 = !this.isVisibleStatus3;
                    this.isVisibleStatus1 = false;
                    this.isVisibleStatus2 = false;
                    this.isVisibleStatus4 = false;
                    this.isVisibleStatus5 = false;
                    this.isVisibleStatus6 = false;
                    break;
                case 4:
                    this.isVisibleStatus4 = !this.isVisibleStatus4;
                    this.isVisibleStatus1 = false;
                    this.isVisibleStatus2 = false;
                    this.isVisibleStatus3 = false;
                    this.isVisibleStatus5 = false;
                    this.isVisibleStatus6 = false;
                    break;
                case 5:
                    this.isVisibleStatus5 = !this.isVisibleStatus5;
                    this.isVisibleStatus1 = false;
                    this.isVisibleStatus3 = false;
                    this.isVisibleStatus4 = false;
                    this.isVisibleStatus2 = false;
                    this.isVisibleStatus6 = false;
                    break;
                case 6:
                    this.isVisibleStatus6 = !this.isVisibleStatus6;
                    this.isVisibleStatus2 = false;
                    this.isVisibleStatus3 = false;
                    this.isVisibleStatus4 = false;
                    this.isVisibleStatus5 = false;
                    this.isVisibleStatus1 = false;
                    break;
            }
        }
    }
}
</script>