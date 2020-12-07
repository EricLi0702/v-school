<template>
    <div>
        <div class="category-title">
            当前状态
        </div>
        
        <div class="vx-item">
            <div class="vx-item-left" v-if="currentState != null">
                {{currentState}}
            </div>
            <div class="vx-item-right" v-if="scheduleTime != null">
                {{scheduleTime}}
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
                        <DropdownItem name="3.5">3.5小时</DropdownItem>
                        <DropdownItem name="4.0">4.0小时</DropdownItem>
                        <DropdownItem name="4.5">4.5小时</DropdownItem>
                        <DropdownItem name="5.0">5.0小时</DropdownItem>
                        <DropdownItem name="5.5">5.5小时</DropdownItem>
                        <DropdownItem name="6.0">6.0小时</DropdownItem>
                        <DropdownItem name="6.5">6.5小时</DropdownItem>
                        <DropdownItem name="自定义">自定义</DropdownItem>
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
                        <DropdownItem name="3.5">3.5小时</DropdownItem>
                        <DropdownItem name="4.0">4.0小时</DropdownItem>
                        <DropdownItem name="4.5">4.5小时</DropdownItem>
                        <DropdownItem name="5.0">5.0小时</DropdownItem>
                        <DropdownItem name="5.5">5.5小时</DropdownItem>
                        <DropdownItem name="6.0">6.0小时</DropdownItem>
                        <DropdownItem name="6.5">6.5小时</DropdownItem>
                        <DropdownItem name="自定义">自定义</DropdownItem>
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
                        <DropdownItem name="3.5">3.5小时</DropdownItem>
                        <DropdownItem name="4.0">4.0小时</DropdownItem>
                        <DropdownItem name="4.5">4.5小时</DropdownItem>
                        <DropdownItem name="5.0">5.0小时</DropdownItem>
                        <DropdownItem name="5.5">5.5小时</DropdownItem>
                        <DropdownItem name="6.0">6.0小时</DropdownItem>
                        <DropdownItem name="6.5">6.5小时</DropdownItem>
                        <DropdownItem name="自定义">自定义</DropdownItem>
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
                        <DropdownItem name="3.5">3.5小时</DropdownItem>
                        <DropdownItem name="4.0">4.0小时</DropdownItem>
                        <DropdownItem name="4.5">4.5小时</DropdownItem>
                        <DropdownItem name="5.0">5.0小时</DropdownItem>
                        <DropdownItem name="5.5">5.5小时</DropdownItem>
                        <DropdownItem name="6.0">6.0小时</DropdownItem>
                        <DropdownItem name="6.5">6.5小时</DropdownItem>
                        <DropdownItem name="自定义">自定义</DropdownItem>
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
                        <DropdownItem name="3.5">3.5小时</DropdownItem>
                        <DropdownItem name="4.0">4.0小时</DropdownItem>
                        <DropdownItem name="4.5">4.5小时</DropdownItem>
                        <DropdownItem name="5.0">5.0小时</DropdownItem>
                        <DropdownItem name="5.5">5.5小时</DropdownItem>
                        <DropdownItem name="6.0">6.0小时</DropdownItem>
                        <DropdownItem name="6.5">6.5小时</DropdownItem>
                        <DropdownItem name="自定义">自定义</DropdownItem>
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
                        <DropdownItem name="3.5">3.5小时</DropdownItem>
                        <DropdownItem name="4.0">4.0小时</DropdownItem>
                        <DropdownItem name="4.5">4.5小时</DropdownItem>
                        <DropdownItem name="5.0">5.0小时</DropdownItem>
                        <DropdownItem name="5.5">5.5小时</DropdownItem>
                        <DropdownItem name="6.0">6.0小时</DropdownItem>
                        <DropdownItem name="6.5">6.5小时</DropdownItem>
                        <DropdownItem name="自定义">自定义</DropdownItem>
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
        <div class="vx-item is-click" @click="addState">
            <div class="vx-item-left">
                <Icon type="md-add" />新增
            </div>
        </div>
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
    methods:{
        chooseType(state,$event){
            let duringMinute = parseInt($event)*60;
            let currentTime = new Date()
            let afterTime = new Date()
            afterTime.setMinutes(afterTime.getMinutes() + duringMinute) 
            this.currentState = state;
            this.scheduleTime = `${this.TimeViewDHM(currentTime)}-${this.TimeViewDHM(afterTime)}`;
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