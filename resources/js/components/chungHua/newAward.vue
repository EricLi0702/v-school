<template>
    <div>
        <div v-if="currentPath.query.addQuestion == undefined">
            <router-link :to="`${currentPath.path}?questionType=表彰&addQuestion=表彰类型`">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>表彰类型</span>
                    </div>
                    <div class="es-item-right">
                        <span v-if="addData.recognitionType">{{addData.recognitionType}}位成员</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=表彰&addQuestion=表彰对象`">
            <div class="es-item">
                <div class="es-item-left">
                    <span>表彰对象</span>
                </div>
                <div class="es-item-right">
                    <span v-if="addData.recognitionObject.length">{{addData.recognitionObject}}</span>
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            </router-link>
            <div class="es-item">
                <div class="es-item-left">
                    <span>表彰称号</span>
                </div>
                <div class="es-item-right">
                    <Input v-model="addData.awardName" class="rightToLeft" maxlength="8" placeholder="选填(最多8个字)" style="width: 200px" />
                </div>
            </div>
            <div class="es-item">
                <div class="es-item-left">
                    颁发日期
                </div>
                <div class="es-item-right">
                    <DatePicker type="date" v-model="addData.date" placeholder="选填" ></DatePicker>
                </div>
            </div>
            <div class="es-item">
                <textarea name="" id="" v-model="addData.description" class="text-content" cols="30" rows="10" placeholder="表彰内容（限40字）"></textarea>
            </div>
            <router-link :to="`${currentPath.path}?questionType=表彰&addQuestion=颁奖词`">
                <div class="es-item">
                    <div class="es-item-lef">

                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=表彰&addQuestion=模板`">
                <div class="es-item">
                    <div class="es-item-left">
                        <span>模板</span>
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '表彰类型'">
            <router-link :to="`${currentPath.path}?questionType=表彰`">
                <div class="es-item" @click="chooseType('班级')">
                    <div class="es-item-left">
                        <span>班级</span>
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=表彰`">
                <div class="es-item" @click="chooseType('校级')">
                    <div class="es-item-left">
                        <span>校级</span>
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=表彰`">
                <div class="es-item" @click="chooseType('区级')">
                    <div class="es-item-left">
                        <span>区级</span>
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=表彰`">
                <div class="es-item" @click="chooseType('市级')">
                    <div class="es-item-left">
                        <span>市级</span>
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=表彰`">
                <div class="es-item" @click="chooseType('省级')">
                    <div class="es-item-left">
                        <span>省级</span>
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?questionType=表彰`">
                <div class="es-item" @click="chooseType('国家级')">
                    <div class="es-item-left">
                        <span>国家级</span>
                    </div>
                </div>
            </router-link>
        </div>
        <div v-else-if="currentPath.query.addQuestion == '表彰对象'">
            <div v-if="currentPath.query.template == undefined">
                <div v-for="(lesson,i) in lessonList" :key="i">
                    <router-link :to="`${currentPath.path}?questionType=表彰&addQuestion=表彰对象&template=${lesson.lessonName}`">
                        <div class="es-item">
                            <div class="es-item-left">
                                <span>{{lesson.lessonName}}</span>
                            </div>
                            <div class="es-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                    </router-link>
                </div>
            </div>
            <div v-else>
                <div class="es-item">
                    <div class="es-item-left w-100">
                        <Input prefix="ios-search" placeholder="搜索"/>
                    </div>
                </div>
                <div v-if="checkAllGroup.length">
                    <div class="category-title">
                        <span>已选</span>
                    </div>
                    <div class="es-item">
                        <div class="user-block" v-for="(selected,i) in checkAllGroup" :key="i">
                            <img :src="selected.userAvatar" alt="" v-if="selected.userAvatar">
                            <Avatar icon="ios-person" v-else/>
                            <span>{{selected}}</span>
                        </div>
                    </div>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Checkbox
                        :indeterminate="indeterminate"
                        :value="checkAll"
                        @click.prevent.native="handleCheckAll">全选</Checkbox>
                    </div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
                <div class="category-title">
                    <span>师</span>
                </div>
                <CheckboxGroup v-model="checkAllGroup" @on-change="checkAllGroupChange">
                    <div v-for="(member,i) in lessonMember" :key="i">
                        
                            <div class="es-item">
                                <div class="es-item-left">
                                    <Checkbox :label="member.name">
                                        <img :src="member.userAvatar" alt="" v-if="member.userAvatar" class="avatar">
                                        <Avatar icon="ios-person" v-else/>
                                    </Checkbox>
                                    <div>
                                        <div class="title">{{member.name}}</div>
                                        <div class="main">{{member.phoneNumber}}</div>
                                    </div>
                                </div>    
                                <div class="es-item-right">
                                    <Icon type="ios-arrow-forward" />
                                </div>
                            </div>    
                        
                    </div>
                </CheckboxGroup>
            </div>
            
        </div>
        <div v-else-if="currentPath.query.addQuestion == '颁奖词'">
            
        </div>
        <div v-else-if="currentPath.query.addQuestion == '模板'">
            
        </div>
    </div>
    
</template>

<script>
export default {
    data(){
        return{
            addData:{
                recognitionType:'',
                recognitionObject:[],
                awardName:'',
                date:'',
                description:'',
                lessonList:[],
            },
            indeterminate:false,
            checkAll: false,
            checkAllGroup: [],
            selectedGroup:[],
            lessonMember:[],
        }
    },
    async created(){
        const res = await this.callApi('get','/api/allLesson');
        if(res.status == 200){
            console.log('123123123',res);
            this.lessonList = res.data;
        }
    },
    computed:{
        currentPath(){
            return this.$route;
        }
    },
    watch:{
        async currentPath(value){
            if(value.query.template){
                await axios.get('/api/lessonMember',{
                    params:{
                        lessonName:value.query.template
                    }
                }).then(res=>{
                    this.lessonMember = res.data;
                    console.log(value.query.template,res)
                })  
            }
        }
    },
    methods:{
        chooseType(type){
            console.log(type);
            this.addData.recognitionType = type;
        },
        handleCheckAll () {
            if (this.indeterminate) {
                this.checkAll = false;
            } else {
                this.checkAll = !this.checkAll;
            }
            this.indeterminate = false;

            if (this.checkAll) {
                // this.checkAllGroup = this.lessonMember;
                for(let i in this.lessonMember){
                    this.checkAllGroup.push(this.lessonMember[i].name)
                }
            } else {
                this.checkAllGroup = [];
            }
        },
        checkAllGroupChange (data) {
            console.log(this.checkAllGroup)
            if (data.length === this.lessonMember.length) {
                this.indeterminate = false;
                this.checkAll = true;
            } else if (data.length > 0) {
                this.indeterminate = true;
                this.checkAll = false;
            } else {
                this.indeterminate = false;
                this.checkAll = false;
            }
        }
    }
}
</script>