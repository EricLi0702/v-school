<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                <span>角色</span>
            </div>
            <div class="es-item-right">
                <RadioGroup v-model="addData.userRole">
                    <Radio label="3">老师</Radio>
                    <Radio label="4">家长</Radio>
                    <Radio label="5">学生</Radio>
                </RadioGroup>
            </div>
        </div>
        <div class="es-item" v-if="addData.userRole == '4'">
            <div class="es-item-left">
                家长身份
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="visible($event)">
                    <a href="javascript:void(0)">
                         {{addData.parentRole}}
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem name="爸爸">爸爸</DropdownItem>
                        <DropdownItem name="妈妈">妈妈</DropdownItem>
                        <DropdownItem name="爷爷">爷爷</DropdownItem>
                        <DropdownItem name="奶奶">奶奶</DropdownItem>
                        <DropdownItem name="姥爷">姥爷</DropdownItem>
                        <DropdownItem name="姥姥">姥姥</DropdownItem>
                        <DropdownItem name="家长">家长</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                手机号
            </div>
            <div class="es-item-right">
                <Input v-model="addData.phoneNumber" class="rightToLeft" maxlength="11" placeholder="选填" style="width: 200px" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生昵称
            </div>
            <div class="es-item-right">
                <Input v-model="addData.nickName" class="rightToLeft" placeholder="必填" style="width: 300px" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生生日
            </div>
            <div class="es-item-right">
                <DatePicker type="date" v-model="addData.birthday" placeholder="选填" ></DatePicker>
                
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生性别
            </div>
            <div class="es-item-right">
                <RadioGroup v-model="addData.userGender">
                    <Radio label="女"></Radio>
                    <Radio label="男"></Radio>
                </RadioGroup>
            </div>
        </div>
        <div class="text-color has-click pd" style="line-height: 30px;" v-if="addData.useRole == '4'"> 还有孩子在同一个班级? </div>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                入群申请
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div class="category-title"></div>
        <div class="es-item">
            <div class="es-item-left">
                从文件导入
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="success" size="large" @click="submit">提交</Button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            addData:{
                userRole:'4',
                parentRole:"家长",
                phoneNumber:'',
                nickName:'',
                birthday:'',
                userGender:"男",
            },
            lessonList:[],
            lessonId:''
        }
    },
    created(){
        axios.get('/api/allLesson').then(res=>{
            this.lessonList = res.data
        }),
        this.lessonId = this.$store.state.gradeInfo.id
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },
    methods:{
        visible($event){
            this.addData.parentRole = $event;
        },
        async submit(){
            let gradeName = this.currentPath.query.className
            const res = await this.callApi('post','/api/member',{data:this.addData,lessonId:this.lessonId})
            this.$store.commit('setClassView',false);
            this.$router.push({path:this.currentPath.path})
        }
    }

}
</script>