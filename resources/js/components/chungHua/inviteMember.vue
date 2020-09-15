<template>
    <div>
        <div class="es-item">
            <div class="es-item-left">
                <span>角色</span>
            </div>
            <div class="es-item-right">
                <RadioGroup v-model="userRole">
                    <Radio label="老师"></Radio>
                    <Radio label="家长"></Radio>
                    <Radio label="学生"></Radio>
                </RadioGroup>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                家长身份
            </div>
            <div class="es-item-right">
                <Dropdown style="margin-left: 20px" placement="bottom-end"  trigger="click">
                    <a href="javascript:void(0)">
                        家长
                        <Icon type="ios-arrow-forward" />
                    </a>
                    <DropdownMenu slot="list">
                        <DropdownItem>爸爸</DropdownItem>
                        <DropdownItem>妈妈</DropdownItem>
                        <DropdownItem>爷爷</DropdownItem>
                        <DropdownItem>奶奶</DropdownItem>
                        <DropdownItem>姥爷</DropdownItem>
                        <DropdownItem>姥姥</DropdownItem>
                        <DropdownItem>家长</DropdownItem>
                    </DropdownMenu>
                </Dropdown>
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                手机号
            </div>
            <div class="es-item-right">
                <Input v-model="value16" class="rightToLeft" maxlength="11" placeholder="选填" style="width: 200px" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生昵称
            </div>
            <div class="es-item-right">
                <Input v-model="value16" class="rightToLeft" placeholder="必填" style="width: 300px" />
            </div>
        </div>
        <div class="es-item">
            <div class="es-item-left">
                学生生日
            </div>
            <div class="es-item-right">
                <Col span="24">
                    <DatePicker type="date" placeholder="Select date" ></DatePicker>
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
        <div class="text-color has-click pd" style="line-height: 30px;"> 还有孩子在同一个班级? </div>
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
        <div class="es-item">
            <div class="es-item-left">

            </div>
            <div class="es-item-right"></div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return{
            userRole:"家长",
            userGender:"男",
        }
    }
}
</script>