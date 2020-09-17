<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                <span>角色</span>
            </div>
            <div class="es-item-right">
                <RadioGroup v-model="character">
                    <Radio label="老师"></Radio>
                    <Radio label="家长"></Radio>
                    <Radio label="学生"></Radio>
                </RadioGroup>
            </div>
        </div>
        <div class="es-item" v-if="parentShow">
            <div class="es-item-left">
                家长身份
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end" trigger="click" @on-click="visible($event)">
                    <a href="javascript:void(0)">
                         {{parentRole}}
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
                <Input v-model="phoneNumber" class="rightToLeft" maxlength="11" placeholder="选填" style="width: 200px" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生昵称
            </div>
            <div class="es-item-right">
                <Input v-model="nickName" class="rightToLeft" placeholder="必填" style="width: 300px" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生生日
            </div>
            <div class="es-item-right">
                <Col span="24">
                    <DatePicker type="date" v-model="birthday" placeholder="选填" ></DatePicker>
                </Col>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生性别
            </div>
            <div class="es-item-right">
                <RadioGroup v-model="userGender">
                    <Radio label="女"></Radio>
                    <Radio label="男"></Radio>
                </RadioGroup>
            </div>
        </div>
        <div class="text-color has-click pd" style="line-height: 30px;" v-if="parentShow"> 还有孩子在同一个班级? </div>
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
        <div class="category-title">从其他群组邀请</div>
        <div class="es-item" v-for="(lesson,i) in lessonList" :key="i">
            <div class="es-item-left">
                {{lesson.lessonName}}
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="default" size="large" @click="submit">Submit</Button>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            character:"家长",
            parentRole:"家长",
            parentShow:true,
            phoneNumber:'',
            nickName:'',
            birthday:'',
            userGender:"男",
            lessonList:[],
        }
    },
    created(){
        axios.get('/api/allLesson').then(res=>{
            this.lessonList = res.data
        })
    },
    watch:{
        character(value){
            if(value == "家长"){
                this.parentShow = true;
            }else{
                this.parentShow = false;
            }
        }
    },
    methods:{
        visible($event){
            this.parentRole = $event;
        },
        submit(){

        }
    }

}
</script>