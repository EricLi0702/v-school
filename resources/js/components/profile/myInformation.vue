<template>
    <div>
        <div v-if="currentPath.query.details == undefined">
            <Upload
                ref="uploads"
                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                :on-success="handleAvatar"
                :on-error="handleError"
                :format="['jpg','jpeg','png']"
                :max-size="10240"
                :show-upload-list="false"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                action="/api/fileUpload/image"
                class="user-gravatar-upload">
                    <div class="vx-item vx-item-show is-click is-avatar">
                        <div class="vx-item-left">
                            头像
                        </div>
                        <div class="vx-item-right">
                            <avatar :size="40" :src="$store.state.user.userAvatar" :username="userInfo.name" class="pr-0"></avatar>
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
            </Upload>
            <Upload
                ref="uploads"
                :headers="{'x-csrf-token': token, 'X-Requested-Width' : 'XMLHttpRequest'}"
                :on-success="handleFaceImg"
                :on-error="handleError"
                :format="['jpg','jpeg','png']"
                :max-size="10240"
                :show-upload-list="false"
                :on-format-error="handleFormatError"
                :on-exceeded-size="handleMaxSize"
                action="/api/fileUpload/image"
                class="user-gravatar-upload">
                    <div class="vx-item vx-item-show is-click is-avatar">
                        <div class="vx-item-left">
                            人脸ID
                        </div>
                        <div class="vx-item-right">
                            <avatar :size="40" :username="userInfo.name" class="pr-0"></avatar>
                            <Icon type="ios-arrow-forward" />
                        </div>
                    </div>
            </Upload>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=昵称`">
                <div class="vx-item vx-item-show is-click">
                    <div class="vx-item-left">昵称</div>
                    <div class="vx-item-right">
                        <span v-if="userInfo.name">{{userInfo.name}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=手机号`">
                <div class="vx-item vx-item-show is-click">
                    <div class="vx-item-left">手机号</div>
                    <div class="vx-item-right">
                        <span v-if="userInfo.phoneNumber">{{userInfo.phoneNumber}}</span>
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=修改密码`">
                <div class="vx-item vx-item-show is-click">
                    <div class="vx-item-left">修改密码</div>
                    <div class="vx-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
            <div class="category-title"></div>
            <div class="vx-item vx-item-show is-click">
                <div class="vx-item-left">按组织架构折叠</div>
                <div class="vx-item-right">
                    <i-switch true-color="#13ce66" />
                </div>
            </div>
            <router-link :to="`${currentPath.path}?profile=我的信息&details=群组排序`">
                <div class="vx-item vx-item-show is-click">
                    <div class="vx-item-left">群组排序</div>
                    <div class="vx-item-right">
                        <Icon type="ios-arrow-forward" />
                    </div>
                </div>
            </router-link>
        </div>
        <div v-else-if="currentPath.query.details == '昵称'">
            <div class="profile-body">
                <div class="vx-item vx-item-show is-click">
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
                <div class="vx-item vx-item-show is-click">
                    <Input v-model="newPhoneNumber" class="customInput w-100" placeholder="必填"/>
                </div>
                <div class="vx-item vx-item-show is-click">
                    <div class="vx-item-left">
                        <Input v-model="verifyCode" class="customInput w-100" placeholder="必填"/>
                    </div>
                    <div class="vx-item-right">
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
                <div class="vx-item vx-item-show is-click">
                    <Input v-model="userInfo.password" type="password" class="customInput w-100" placeholder="请输入旧密码"/>
                </div>
                <div class="vx-item vx-item-show is-click">
                    <Input v-model="confirmPass" type="password" class="customInput w-100" placeholder="请输入新密码"/>
                </div>
                <div class="vx-item vx-item-show is-click">
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
import Avatar from 'vue-avatar'
export default {
    computed:{
        currentPath(){
            return this.$route
        }
    },
    components:{
        Avatar,
    },
    data(){
        return{
            userInfo:{
                userAvatar:'',
                faceImg:'',
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
        this.userInfo.userAvatar = this.$store.state.user.userAvatar
        this.userInfo.faceImg = this.$store.state.user.faceImg;
        this.userInfo.name = this.$store.state.user.name
        this.userInfo.phoneNumber = this.$store.state.user.phoneNumber
    },
    methods:{
        async handleAvatar (res, file) {
            res = `/uploads/image/${res}`
            this.userInfo.userAvatar = res;
            const avatar = await this.callApi('put','/api/profile',{userAvatar:res,userId:this.userId})
        },
        async handleFaceImg (res, file) {
            res = `/uploads/image/${res}`
            this.userInfo.faceImg = res;
            const avatar = await this.callApi('put','/api/profile',{faceImg:res,userId:this.userId})
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
            if(res.status == 200){
                this.success('操作成功')
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
        },
        async updatePhoneNumber(){
            const res = await this.callApi('put','/api/profile',{phoneNumber:this.newPhoneNumber,userId:this.userId})
        }

    }
}
</script>