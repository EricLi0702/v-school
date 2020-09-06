<template>
    <div class="d-flex _box_shadow sub-container" style="height:94vh;background:url('img/login.jpg');background-repeat: round;">
        <div class="header">
                <div class="_2menu _box_shadow">
                    <div class="container d-flex">
                        
                    </div>
                </div>
            </div>
        <div class="container login_center">
            <div class="" style="background:none; color:white;padding-top:350px;float:left;">
                <span>ssssssssssssssssssssssssssss</span>
            </div>
            <div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20 w-30" style="background:hsla(0,0%,100%,.9);">
                <Tabs value="name2">
                    <TabPane label="扫码登录" name="name1">
                        标签一的内容
                    </TabPane>
                    <TabPane label="账户登录" name="name2">
                        <div class="mb-2">
                            <Input type="text" style=" height: 45px;" v-model="data.phoneNumber" placeholder="Phone Number">
                                <Icon type="ios-person-outline" slot="prepend" style="font-size:30px"></Icon>
                            </Input>
                            <Input type="password" style=" height: 45px;" v-model="data.password" placeholder="******">
                                <Icon type="ios-lock-outline" slot="prepend" style="font-size:30px"></Icon>
                            </Input>
                        </div>
                        <div class="mb-2">
                            <Checkbox v-model="policy"></Checkbox>
                            <span>已阅读并同意<a href="#">《用户服务协议》和《隐私》</a></span>
                        </div>
                        <div class="login_footer mb-2">
                            <Button type="primary" long @click="login" :disabled="isLogging" :loading="isLogging">{{isLogging ? '登录...' : '登录'}}</Button>
                        </div>
                        <div style="width:100%;height:25px">
                            <span class="float-right">忘记密码?</span>
                        </div>
                        <div class="thirdparty-title" style="">
                            <span>—————</span>
                            <span>使用第三方账号登录</span>
                            <span>—————</span>
                        </div>
                        <div class="thirdparty-box">
                            <a class="box-one">
                            <img src="img/login-wechat.png" alt="">
                            <span>企业微信</span></a>
                        </div>
                    </TabPane>
                </Tabs>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    data(){
        return {
            data : {
                phoneNumber : '', 
                password: ''
            }, 
            isLogging: false,
            policy:true, 
        }
    }, 

    methods : {
        async login(){
            if(this.data.phoneNumber.trim()=='') return this.error('PhoneNumber is required')
            if(this.data.password.trim()=='') return this.error('Password is required')
            if(this.data.password.length < 6) return this.error('Incorrect login details')
            this.isLogging = true
            const res = await this.callApi('post', 'api/login', this.data)
            if(res.status===200){
                console.log(res)
                this.success(res.data.msg)
                window.location = '/'
            }else{
                if(res.status===401){
                    this.info(res.data.msg)
                }else if(res.status==422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }
                else{
                    this.swr()
                }
            }
            this.isLogging = false
        }
    }
}
</script>






<style scoped>
    .login_center{
        margin:0 auto;    
    }
    ._1adminOverveiw_table_recent_slug{
        float: left;
        margin-top: 300px;
    }
    ._1adminOverveiw_table_recent {
        /* margin: 0 auto; */
        float:right;
        margin-top: 250px;
    }
    .login_footer{
        text-align: center;
    }
    .login_header{
        text-align: center;
        margin-bottom: 25px;
    }
    .thirdparty-title{
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        color: #999;
    }

    .thirdparty-box{
        display: flex;
        justify-content: space-around;
    }
    .box-one{
        display: flex;
        align-items: center;
    }
    .thirdparty-box .box-one img{
        display: inline-block;
        width: 22px;
        height: 22px;
        margin-right: 4px;
    }
    .thirdparty-box .box-one span{
        height: 22px;
        color: #999;
        font-size: 14px;
    }
</style>