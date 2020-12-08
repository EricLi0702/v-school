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
        <!-- <div class="es-item">
            <div class="es-item-left">
                入群申请
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward" />
            </div>
        </div> -->
        <div class="category-title"></div>
        <div class="es-item" @click="userImport">
            <div class="es-item-left">
                从文件导入
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
        <div class="es-model-operate">
            <Button type="success" size="large" @click="submit" :loading="isAdding" :disabled="isAdding">提交</Button>
        </div>
        <Modal
            v-model="uploadModal"
            class="uploadModal"
            title="导入习题"
            @on-ok="uploadFile"
            :styles="{top:'140px',left:'64px'}">
                <Upload
                    ref="otherUploads"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    type="drag"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['xls','xlsx']"
                    :max-size="524288"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    :before-upload="handleFileUpload"
                    action="/api/fileUpload/userImport">
                    <div style="padding: 20px 0">
                        <Icon type="ios-cloud-upload" size="52" style="color: #3399ff"></Icon>
                        <p>将文件拖到此处，或 <span class="text-color">点击上传</span></p>
                    </div>
                </Upload>
                <!-- <div class="es-item-tooltip">
                    <div>导入说明</div> 
                    <div>1、必须按正确的格式将数据填入模板 <a href="/download/doc/praxisTemplate.doc" class="text-color" download>（ 下载模板 ）</a></div>
                    <div>2、文件格式必须为xls、xlsx、doc。</div>
                </div> -->
        </Modal>
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
            lessonId:'',
            isAdding:false,
            token:window.Laravel.csrfToken,
            uploadModal:false,
            sendFile:null,
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
            if(this.addData.phoneNumber == '' || this.addData.nickName == '' || this.addData.birthday == ''){
                return this.error('')
            }
            this.isAdding = true
            let gradeName = this.currentPath.query.className
            const res = await this.callApi('post','/api/member',{data:this.addData,lessonId:this.lessonId})
            if(res.status == 200){

            }
            this.isAdding = false
            this.$store.commit('setClassView',false);
            this.$router.push({path:this.currentPath.path})
        },
        handleSuccess (res, file) {
            if(res == 1){
                this.success('操作成功')
                this.uploadModal = false
            }
        },
        handleError (res, file) {
            // this.$Notice.warning({
            //     title:'文件格式不正确',
            //     desc:`${file.errors.file.length ? file.errors.file[0] : '出了些问题！'}`
            // })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: '文件格式不正确',
                desc: '文件格式  错误，请选择其他文件类型。'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: '超出文件大小限制',
                desc: '文件  太大，不超过512M。'
            });
        },
        handleFileUpload(file){
            this.sendFile = file;
            return false;
        },
        userImport(){
            this.uploadModal = true
        },
        uploadFile(){
            if(this.sendFile){
                let formdata = new FormData();
                formdata.append('file',this.sendFile);
                // formdata.append('from',this.currentUser.id);
                // formdata.append('to',this.chatto);
                axios
                .post(`/api/fileUpload/userImport`, formdata ,{
                    headers: {
                    'Content-Type': 'multipart/form-data'
                    }
                })
                .then((res) => {
                    if(res.errors){
                    this.$Notice.warning({
                        title: 'Something went wrong',
                        desc: res.errors
                    });
                    }
                });
            }
        }
    }

}
</script>