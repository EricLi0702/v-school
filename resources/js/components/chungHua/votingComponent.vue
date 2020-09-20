<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="`${currentPath.path}?questionType=投票&addQuestion=应用模板`">
                <div class="category-title template">
                    <Icon type="ios-list-box-outline" />
                    <span>可用模板4，草稿0</span>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    投票人是否可见结果
                </div>
                <div class="es-item-right">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="visible($event)">
                        <a href="javascript:void(0)">
                            {{vResult}}
                            <Icon type="ios-arrow-forward" />
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="投票后可见">投票后可见</DropdownItem>
                            <DropdownItem name="投票前后均可见">投票前后均可见</DropdownItem>
                            <DropdownItem name="投票前后均不可见">投票前后均不可见</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
            </div>
            <router-link :to="`${currentPath.path}?questionType=投票&addQuestion=调查范围`">
                <div class="es-item">
                    <div class="es-item-left">
                        调查范围
                    </div>
                    <div class="es-item-right">
                        <span>必填</span>
                        <Icon type="ios-arrow-forward" /> 
                    </div>
                </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    截止时间
                </div>
                <div class="es-item-right">
                    <DatePicker type="datetime" v-model="deadline" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    匿名问卷
                </div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" v-model="anonyVote" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    最高分
                </div>
                <div class="es-item-right">
                    <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="chooseMaxVote($event)">
                        <a href="javascript:void(0)">
                            {{maxVote}}
                            <Icon type="ios-arrow-forward" />
                        </a>
                        <DropdownMenu slot="list">
                            <DropdownItem name="1">1</DropdownItem>
                            <DropdownItem name="2">2</DropdownItem>
                            <DropdownItem name="3">3</DropdownItem>
                            <DropdownItem name="4">4</DropdownItem>
                            <DropdownItem name="5">5</DropdownItem>
                            <DropdownItem name="6">6</DropdownItem>
                            <DropdownItem name="7">7</DropdownItem>
                            <DropdownItem name="8">8</DropdownItem>
                            <DropdownItem name="9">9</DropdownItem>
                            <DropdownItem name="10">10</DropdownItem>
                        </DropdownMenu>
                    </Dropdown>
                </div>
            </div>
            <contentComponent></contentComponent>
            <contentComponent></contentComponent>
            <contentComponent></contentComponent>
        </div>
        <div v-else>
            <div v-if="currentPath.query.template ==undefined">
                <div v-if="currentPath.query.addQuestion == '应用模板'">
                    <div class="apps-template">
                        <div class="template-item" v-if="templateData.length">
                            <img src="" alt="" class="picture">
                            <p class="text"></p>
                        </div>
                        <router-link :to="`${currentPath.path}?questionType=投票&addQuestion=应用模板&template=add`">
                            <div class="template-item-add">
                                <Icon type="ios-add" size="120" color="#DEDEDE"/>
                            </div>
                        </router-link>
                    </div>
                </div>
                <div v-else-if="currentPath.query.addQuestion == '调查范围'">
                    <Menu>
                        <Submenu name="1">
                            <template slot="title">
                                <Checkbox>
                                    内容管理
                                </Checkbox>
                            </template>
                            <MenuItem name="1-1"><Checkbox>文章管理</Checkbox></MenuItem>
                        </Submenu>
                    </Menu>
                </div>
            </div>
            <div v-else>
                
            </div>
        </div>
    </div>
</template>

<script>
import contentComponent from './contentComponent'
export default {
    components:{
        contentComponent,
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    data(){
        return{
            vResult:'投票后可见',
            maxVote:1,
            deadline:'',
            anonyVote:true,
            templateData:[],
        }
    },
    methods:{
        visible($event){
            this.vResult = $event;
        },
        chooseMaxVote($event){
            this.maxVote = $event;
        }
    }
}
</script>