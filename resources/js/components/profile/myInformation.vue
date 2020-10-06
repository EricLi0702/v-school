<template>
    <div class="p-3">
        <div v-if="currentPath.query.details == undefined">
            <div class="es-item">
                <Upload
                    ref="uploads"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="handleAvatar"
                    :on-error="handleError"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :show-upload-list="false"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="api/fileUpload/image">
                    <div class="es-item-left">
                        头像
                    </div>
                </Upload>
                <div class="es-item-right">
                    <img :src="userInfo.userAvatar" alt="" class="avatar" v-if="userInfo.userAvatar">
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            <div class="es-item">
                <Upload
                    ref="uploads"
                    :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                    :on-success="handleFaceImg"
                    :on-error="handleError"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :show-upload-list="false"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="api/fileUpload/image">
                        <div class="es-item-left">
                            人脸ID
                        </div>
                </Upload>
                <div class="es-item-right">
                    <img :src="userInfo.faceIdImg" alt="" class="avatar" v-if="userInfo.faceIdImg">
                    <Icon type="ios-arrow-forward" />
                </div>
            </div>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=昵称`">
                <div class="es-item">
                    <div class="es-item-left">昵称</div>
                    <div class="es-item-right">
                        <span v-if="userInfo.name">{{userInfo.name}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=手机号`">
                <div class="es-item">
                    <div class="es-item-left">手机号</div>
                    <div class="es-item-right">
                        <span v-if="userInfo.phoneNumber">{{userInfo.phoneNumber}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=修改密码`">
                <div class="es-item">
                    <div class="es-item-left">修改密码</div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div class="category-title"></div>
            <div class="es-item">
                <div class="es-item-left">按组织架构折叠</div>
                <div class="es-item-right">
                    <i-switch true-color="#13ce66" />
                </div>
            </div>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=群组排序`">
                <div class="es-item">
                    <div class="es-item-left">群组排序</div>
                    <div class="es-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
        </div>
        <div v-else-if="currentPath.query.details == '昵称'">
            <div class="profile-body">
                <div class="es-item">
                    <Input v-model="userInfo.name" class="customInput w-100" placeholder="必填"/>
                </div>
            </div>
            <div class="profile-btn-operate">
                <Button type="primary" @click="updateUserName" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.details == '手机号'">
            <div class="profile-body">
                <div class="category-title">原号码为 : {{userInfo.phoneNumber}}</div>
                <div class="es-item">
                    <Input v-model="newPhoneNumber" class="customInput w-100" placeholder="必填"/>
                </div>
                <div class="es-item">
                    <div class="es-item-left">
                        <Input v-model="verifyCode" class="customInput w-100" placeholder="必填"/>
                    </div>
                    <div class="es-item-right">
                        <div class="text-color">获取验证码</div>
                    </div>
                </div>
            </div>
            <div class="profile-btn-operate">
                <Button type="primary" @click="updatePhoneNumber" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.details == '修改密码'">
            <div class="profile-body">
                <div class="es-item">
                    <Input v-model="userInfo.password" type="password" class="customInput w-100" placeholder="请输入旧密码"/>
                </div>
                <div class="es-item">
                    <Input v-model="confirmPass" type="password" class="customInput w-100" placeholder="请输入新密码"/>
                </div>
                <div class="es-item">
                    <Input v-model="newPassword" class="customInput w-100" placeholder="请再次输入新密码"/>
                </div>
                <div class="category-title"></div>
                密码长度8-32位，且必须包含大小写字母/数字/符号任意三者组合，如：Ab123567、Aa@1234+
            </div>
            <div class="profile-btn-operate">
                <Button type="primary" @click="updatePassword" :disabled="isLoading" :loading="isLoading">提交</Button>
            </div>
        </div>
        <div v-else-if="currentPath.query.details == '群组排序'">

        </div>
    </div>
</template>

<script>
export default {
    computed:{
        currentPath(){
            return this.$route
        }
    },
    data(){
        return{
            userInfo:{
                userAvatar:'',
                faceIdImg:'',
                name:'',
                phoneNumber:'',
                password:'',
                showFlag:false,
                groupSort:null
            },
            newPhoneNumber:'',
            verifyCode:'',
            confirmPass:'',
            newPassword:'',
            token:'',
            isLoading:false,
            userId:'',
        }
    },
    created(){
        this.token = window.Laravel.csrfToken
        this.userId = this.$store.state.user.id
        console.log('########',this.$store.state.user)
        this.userInfo.userAvatar = this.$store.state.user.userAvatar
        this.userInfo.name = this.$store.state.user.name
        this.userInfo.phoneNumber = this.$store.state.user.phoneNumber
    },
    methods:{
        async handleAvatar (res, file) {
            res = `/uploads/image/${res}`
            this.userInfo.userAvatar = res;
            const avatar = await this.callApi('put','api/profile',{userAvatar:res,userId:this.userId})
            console.log(avatar)
        },
        async handleFaceImg (res, file) {
            res = `/uploads/image/${res}`
            this.userInfo.faceIdImg = res;
            const avatar = await this.callApi('put','/api/profile',{faceImg:res,userId:this.userId})
            console.log(avatar)
        },
        handleError (res, file) {
            this.$Notice.warning({
                title:'The file format is incorrect',
                desc:`${file.errors.file.length ? file.errors.file[0] : 'Something went wrong!'}`
            })
        },
        handleFormatError (file) {
            this.$Notice.warning({
                title: 'The file format is incorrect',
                desc: 'File format of ' + file.name + ' is incorrect, please select another file type.'
            });
        },
        handleMaxSize (file) {
            this.$Notice.warning({
                title: 'Exceeding file size limit',
                desc: 'File  ' + file.name + ' is too large, no more than 2M.'
            });
        },
        async updateUserName(){
            if(this.userInfo.name.trim() == ''){
                return this.error('')
            }
            this.isLoading = true
            const res = await this.callApi('put','/api/profile',{userName:this.userInfo.name,userId:this.userId})
            console.log(res)
            if(res.status == 200){
                this.success('ok')
            }else{
                this.swr()
            }
            this.isLoading = false
        },
        async updatePasswrod(){
            if(this.userInfo.password != this.confirmPass){
                return this.error('')
            }
            const res = await this.callApi('put','/api/profile',{password:this.newPassword,userId:this.userId})
            console.log(res)
        },
        async updatePhoneNumber(){
            const res = await this.callApi('put','api/profile',{phoneNumber:this.newPhoneNumber,userId:this.userId})
        }

    }
}
</script>