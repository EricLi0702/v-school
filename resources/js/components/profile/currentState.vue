<template>
    <div>
        <div class="category-title">
            当前状态
        </div>
        
        <div class="es-item">
            <div class="es-item-left" v-if="currentState != null">
                {{currentState}}
            </div>
            <div class="es-item-right" v-if="scheduleTime != null">
                {{scheduleTime}}
            </div>
        </div>
        <div class="category-title">

        </div>
        <div class="es-item">
            <div class="es-item-left">
                在办公室
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType('在办公室',$event)">
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
        <div class="es-item">
            <div class="es-item-left">
                上课中
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType('上课中',$event)">
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
        <div class="es-item">
            <div class="es-item-left">
                会议中
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType('会议中',$event)">
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
        <div class="es-item">
            <div class="es-item-left">
                待客中
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType('待客中',$event)">
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
        <div class="es-item">
            <div class="es-item-left">
                忙碌中
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType('忙碌中',$event)">
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
        <div class="es-item">
            <div class="es-item-left">
                外出中
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseType('外出中',$event)">
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
        <div class="es-item" @click="addState">
            <div class="es-item-left">
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
            addState:null,
            count1:0,
        }
    },
    methods:{
        chooseType(state,$event){
            console.log(state,$event)
            let duringMinute = parseInt($event)*60;
            let currentTime = new Date()
            let afterTime = new Date()
            afterTime.setMinutes(afterTime.getMinutes() + duringMinute) 
            console.log('@@@@',this.TimeViewDHM(currentTime))
            console.log('@@@@',this.TimeViewDHM(afterTime))
            this.currentState = state;
            this.scheduleTime = `${this.TimeViewDHM(currentTime)}-${this.TimeViewDHM(afterTime)}`;
        },
        addState(){

        }
    }
}
</script>