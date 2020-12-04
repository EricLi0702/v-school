<template>
    <div>
        <Button class="btnclass" :loading="isLoading" :disabled="isLoading" @click="getAccessToken"><Icon type="md-add" /> Get Access Token </Button>
        <Button class="btnclass" :loading="isLoading" :disabled="isLoading" @click="getUserDeviceList"><Icon type="md-add" /> getUserDeviceList </Button>
        <Button class="btnclass" :loading="isLoading" :disabled="isLoading" @click="geUsertDeviceLocationList"><Icon type="md-add" /> getUserDeviceLocationList </Button>
        <Button class="btnclass" :loading="isLoading" :disabled="isLoading" @click="getDeviceLocationList"><Icon type="md-add" /> getDeviceLocationList </Button>
        <Button class="btnclass" :loading="isLoading" :disabled="isLoading" @click="getDeviceTrackList"><Icon type="md-add" /> getDeviceTrackList </Button>
        <Button class="btnclass" :loading="isLoading" :disabled="isLoading" @click="createDeviceFence"><Icon type="md-add" /> createDeviceFence </Button>
    </div>
</template>

<script>
export default {
    created(){
        
    },
    mounted(){

    },
    data(){
        return{
            isLoading:false,
            accessToken:'',
            refreshToken:'',
            openApiUrl:'https://cors-anywhere.herokuapp.com/http://open.aichezaixian.com/route/rest',
            time:'',
            format:'json',
            sign_method:'md5',
            user_id:'辽宁国荣科技',
            user_pwd_md5:'',
            expires_in:7200,
            v:'1.0',
            appKey:'8FB345B8693CCD0078950C62F0A8C431',
            account:'',
            userDeviceList:[],
            userDeviceLocationList:[],
            deviceLocationList:[],
        }
    },
    methods:{
        generateSign(methodType){
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')
            paramPut.timestamp = this.time
            paramPut.v = this.v
            paramPut.app_key = this.appKey
            paramPut.method = methodType
            paramPut.format = this.format
            paramPut.sign_method = this.sign_method
            paramPut.user_id = this.user_id
            paramPut.user_pwd_md5 = this.user_pwd_md5
            paramPut.expires_in = this.expires_in
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            return upper
        },
        async getAccessToken(){
            let method = 'jimi.oauth.token.get'
            let sign = this.generateSign(method)
            this.isLoading = true
            await axios.get(this.openApiUrl,{
                params:{
                sign:sign,
                timestamp:this.time,
                v:this.v,
                app_key:this.appKey,
                method:method,
                format:this.format,
                sign_method:this.sign_method,
                user_id:this.user_id,
                user_pwd_md5:this.user_pwd_md5,
                expires_in:this.expires_in
            }}).then(res=>{
                this.accessToken = res.data.result.accessToken
                this.refreshToken = res.data.result.refreshToken
                this.account = res.data.result.account
                this.isLoading = false
            }).catch(err=>{
                this.isLoading = false
            })
        },
        async createPlatformAccount(){
        },
        async getUserDeviceList(){
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.user.device.list'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.target = this.account
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            // let sign = this.generateSign(method)
            this.isLoading = true
            await axios.get(this.openApiUrl,{
                params:{
                sign:upper,
                timestamp:this.time,
                v:this.v,
                app_key:this.appKey,
                method:"jimi.user.device.list",
                format:this.format,
                sign_method:this.sign_method,
                access_token:this.accessToken,
                target:this.account
            }}).then(res=>{
                this.userDeviceList = res.data.result
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        async geUsertDeviceLocationList(){
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.user.device.location.list'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.target = this.account
            paramPut.map_type='BAIDU'
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            // let sign = this.generateSign(method)
            this.isLoading = true
            await axios.get(this.openApiUrl,{
                params:{
                sign:upper,
                timestamp:this.time,
                v:this.v,
                app_key:this.appKey,
                method:"jimi.user.device.location.list",
                format:this.format,
                sign_method:this.sign_method,
                access_token:this.accessToken,
                target:this.account,
                map_type:'BAIDU'
            }}).then(res=>{
                // this.userDeviceList = res.data.result
                this.userDeviceLocationList = res.data.result
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        async getDeviceLocationList(){
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.device.location.get'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imeis = '868120246600230'
            paramPut.map_type='BAIDU'
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            // let sign = this.generateSign(method)
            this.isLoading = true
            await axios.get(this.openApiUrl,{
                params:{
                sign:upper,
                timestamp:this.time,
                v:this.v,
                app_key:this.appKey,
                method:"jimi.device.location.get",
                format:this.format,
                sign_method:this.sign_method,
                access_token:this.accessToken,
                imeis:'868120246600230',
                map_type:'BAIDU'
            }}).then(res=>{
                console.log('111',res)
                // this.userDeviceList = res.data.result
                this.deviceLocationList = res.data.result
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        async getDeviceTrackList(){
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')
            let begin_time = '2020-12-01 15:00:00'
            let end_time = '2020-12-01 17:00:00'
            paramPut.method = 'jimi.device.track.list'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = '868120246600230'
            paramPut.map_type='BAIDU'
            paramPut.begin_time = begin_time
            paramPut.end_time = end_time
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            // let sign = this.generateSign(method)
            this.isLoading = true
            await axios.get(this.openApiUrl,{
                params:{
                sign:upper,
                timestamp:this.time,
                v:this.v,
                app_key:this.appKey,
                method:"jimi.device.track.list",
                format:this.format,
                sign_method:this.sign_method,
                access_token:this.accessToken,
                imei:'868120246600230',
                begin_time:begin_time,
                end_time:end_time,
                map_type:'BAIDU',
            }}).then(res=>{
                console.log('111',res)
                // this.userDeviceList = res.data.result
                this.deviceLocationList = res.data.result
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        async createDeviceFence(){
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.device.fence.create'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = '868120246600230'
            paramPut.fence_name = 'test'
            paramPut.alarm_type = 'in'
            paramPut.report_mode = '1'
            paramPut.alarm_switch = 'ON'
            paramPut.lng = '113.91674845964586'
            paramPut.lat = '22.577144898887813'
            paramPut.radius = '20'
            paramPut.zoom_level = '17'
            paramPut.map_type = 'BAIDU'
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            paramPut.sign = upper
            await axios.post(this.openApiUrl,paramPut)
                .then(res=>{
                    console.log(res)
                })
                .catch(err=>{
                    console.log(err)
                })
        }
    }
}
</script>

<style>

</style>