<template>
<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 p-2 border-right">
                <div class=" w-100 mb-2 p-2 border" v-for="device in userDeviceList" :key="device.imei" :class="{'bg-primary text-white':device.active}">
                    <div @click="selDevice(device)">
                        <span>{{device.deviceName}}</span>
                    </div>
                    <div>
                        <span @click="getDeviceTrackList(device.imei)" class="">轨迹回放</span>
                        <span @click="realTracking(device.imei)" class="" :class="{'text-red':trackFlag}">实时跟踪</span>
                    </div>
                </div>
            </div>
            <div class="col-2 p-2 border-right">
                <div class="w-100 mb-2 p-2 border" v-for="fence in allPolygonPath" :key="fence.fenceName" @click="selFence(fence)" :class="{'bg-primary text-white':fence.active}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">{{fence.fenceName}}</span>
                        <Icon type="md-trash" @click="deleteFence(fence)" class="float-right"/>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">告警类型</span>
                        <span class="float-right">{{fence.status}}</span>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div id="baidumapComponent">
                    <div class="display-flex">
                        <button class="addbtn" @click="addNewPolygon">{{ isAdding ? '发布...' : '发布' }}</button>
                        <Input search placeholder="Enter something..." v-model="keyword" style="width:300px"/>          
                    </div>
                    <baidu-map class="map" :center="{lng:userlng, lat:userlat}" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint" @rightclick="drawNewpolygon">
                        <bm-circle v-for="circle in allPolygonPath" :key="circle.fenceName" :center="{lng:circle.lng,lat:circle.lat}" :radius="circle.radius" fill-color="red" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2" :editing="false">
                            <bm-label :content="circle.fenceName" :labelStyle="{color: 'red', fontSize : '15px'}" :offset="{width: -35, height: 30}"/>
                        </bm-circle>
                        <bm-circle :center="circlePath.center" :radius="circlePath.radius" fill-color="red" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2" @lineupdate="updateCirclePath" :editing="true"></bm-circle>
                        <bm-marker :position="{lng: location.lng, lat: location.lat}" v-for="location in deviceLocationList" :key="location.imei" v-if="deviceLocationList.length>0">
                            <bm-label :content="location.deviceName" :labelStyle="{color: 'red', fontSize : '15px'}" :offset="{width: -35, height: 30}"/>
                        </bm-marker>
                        <bm-control>
                            
                        </bm-control>
                        <bm-local-search :keyword="keyword" :auto-viewport="true" :forceLocal="true" :panel="false" :selectFirstResult="true" location="沈阳"></bm-local-search>
                        <bm-geolocation anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :showAddressBar="true" :autoLocation="true"></bm-geolocation>
                    </baidu-map>
                </div>
            </div>
        </div>
        <Modal
            v-model="fenceModal"
            title="新增围栏"
            class="fence-modal"
            @on-ok="makeFence"
            footer-hide
            @on-cancel="cancel">
            <div class="row m-0 p-0">
                <div class="col-3 text-right">
                    设备imei号
                </div>
                <div class="col-9">
                    {{imeiStr}}
                </div>
                
                <div class="col-3 text-right mt-1">围栏名称</div>
                <div class="col-9 mt-1">
                    <Input v-model="fenceData.fence_name" maxlength="25" show-word-limit placeholder="" />
                </div>

                <div class="col-3 text-right mt-1">围栏形状</div>
                <div class="col-9 mt-1">
                    {{fenceData.fence_shape}}
                </div>
                
                <div class="col-3 text-right mt-1">告警类型</div>
                <div class="col-9 mt-1">
                    <RadioGroup v-model="fenceData.status">
                        <Radio label="in"></Radio>
                        <Radio label="out"></Radio>
                        <Radio label="all"></Radio>
                    </RadioGroup>
                </div>
                
                <div class="col-3 text-right mt-1">
                    经度
                </div>
                <div class="col-9 mt-1">
                    {{circlePath.center.lng}}
                </div>
                
                <div class="col-3 text-right mt-1">
                    纬度
                </div>
                <div class="col-9 mt-1">
                    {{circlePath.center.lat}}
                </div>
                
                <div class="col-3 text-right mt-1">
                    围栏半径
                </div>
                <div class="col-9 mt-1">
                    {{parseInt(circlePath.radius)}}
                </div>
                
                <div class="col-3 text-right mt-1">缩放级别</div>
                <div class="col-9 mt-1">
                    <InputNumber :max="19" :min="1" v-model="fenceData.scale"></InputNumber>
                </div>
                <div class="offset-3 col-9 mt-1">
                    <Button type="primary" :loading="isAdding" :disabled="isAdding" @click="createDeviceFence">提交</Button>
                    <Button type="default">取消</Button>
                </div>
            </div>
        </Modal>
    </div>
</div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
    data () {
        return {
            fenceData:{
                fence_name:'',
                fence_shape:'circle',
                status:'in',
                scale:3
            },
            allPolygonPath:[],
            polygonPath: [],
            isSaving:false,
            isAdding:false,
            isChecking:false,
            isEditing:false,
            isDeleting:false,
            isSelected:false,
            userlng:123.46148215426814,
            userlat:41.7806799050726,
            keyword:'',
            fenceCheck:'',
            selectedIdx:null,
            isNew:true,
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
            userDeviceList:[],
            userDeviceLocationList:[],
            deviceLocationList:[],
            createLng:'',
            createLat:'',
            imeiStr:'',
            trackFlag:false,
            fenceModal:false,
            circlePath: {
                center: {
                    lng: 0,
                    lat: 0
                },
                radius: 500
            },
            fiveMinutes: 300,
        }
    },
    async created(){
        // this.getAccessTokenFunc();
        this.accessToken = this.getAccessToken;
        console.log('accessToken',this.accessToken)
        if(this.accessToken == undefined){
            this.getAccessTokenFunc();
        }else{
            this.getUserDeviceList()
        }
        // this.getUserDeviceList()
    },
    mounted(){
        
    },
    computed:{
        axiosParams(){
            const params = new URLSearchParams();
            params.append('userId',this.$store.state.user.id)
            return params;
        },
        ...mapGetters([
            'getAccessToken','getRefreshToken'
        ])
    },
    methods: {
        updatePolygonPath (e) {
            this.polygonPath = e.target.getPath()
        },
        addPolygonPoint (lng,lat) {
            this.polygonPath.push({lng: lng, lat: lat})
        },
        addNewPolygon(){
            this.isAdding = !this.isAdding
            if(this.isAdding == false){
                this.allPolygonPath.push(this.polygonPath)
            }
        },
        addPoint(e){
            if(!this.isAdding){
                return;
            }
            const {lng,lat} = e.Ag;
            // if(this.createLng == ''){
            //     this.createLng = lng
            //     this.createLat = lat
            // }else{
            //     this.createLat += ',' + lat
            //     this.createLng += ',' + lng 
            // }
            // console.log(this.createLng)
            // console.log(this.createLat)
            // this.addPolygonPoint(lng,lat)
            this.circlePath.center.lng = lng
            this.circlePath.center.lat = lat
        },

        updateCirclePath (e) {
            if(this.isAdding == false){
                return
            }
            this.circlePath.center = e.target.getCenter()
            this.circlePath.radius = e.target.getRadius()
        },

        drawNewpolygon(e){
            this.fenceModal = true
            this.isAdding = false
            if(parseInt(this.fenceData.radius)<200){
                this.fenceData.radius = 200
            }
            if(parseInt(this.fenceData.radius)>999900){
                this.fenceData.radius = 999900
            }
        },
        selPolygon(item,index){
            this.isSelected = true;
            this.polygonPath = item;
            this.selectedIdx = index;
        },
        //fence api 
        selDevice(device){
            for(let i=0;i<this.userDeviceList.length;i++){
                delete this.userDeviceList[i].active
            }
            this.$set(device,'active',true)
            this.imeiStr = device.imei
            this.getDeviceLocationList(this.imeiStr)
            this.getDeviceFence()            
        },
        realTracking(device){
            this.getDeviceLocationList(device)
            this.trackFlag = ! this.trackFlag
            if(this.trackFlag == true){
                let self = this
                this.fenceCheck = setInterval(function(){self.getDeviceLocationList(device)}, 20000);
            }else{
                clearInterval(this.fenceCheck)
            }
        },
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
            // console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            // console.log(upper)
            return upper
        },
        
        async getAccessTokenFunc(){
            let method = 'jimi.oauth.token.get'
            let sign = this.generateSign(method)
            this.isLoading = true
            await axios.get(this.openApiUrl,{params:{
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
                this.$store.commit('setAccessToken',this.accessToken)
                this.$store.commit('setRefreshToken',this.refreshToken)
                this.getUserDeviceList()
                this.isLoading = false
            }).catch(err=>{
                this.error("当前有许多请求，服务器无法响应。");
                this.isSwr = true;
                this.isLoading = false
            })
        },

        startTimer() {
            var timer = this.fiveMinutes, minutes, seconds;
            setInterval(function () {
                minutes = parseInt(timer / 60, 10)
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                this.remainTime = minutes + ":" + seconds;

                if (--timer < 0) {
                    timer = this.fiveMinutes;
                }
            }, 1000);
        },
        
        async createTokenRefresh(){
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.oauth.token.refresh'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.refresh_token = this.refreshToken
            paramPut.expires_in = this.expires_in
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            // console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
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
                refresh_token:this.this.refreshToken,
                expires_in:this.expires_in
            }}).then(res=>{
                this.accessToken = res.data.result.accessToken
                this.refreshToken = res.data.result.refreshToken
                this.$store.commit('setAccessToken',this.accessToken)
                this.$store.commit('setRefreshToken',this.refreshToken)
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        async createPlatformAccount(){
        },
        async getUserDeviceList(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
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
            paramPut.target = this.user_id
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            // console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
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
                target:this.user_id
            }}).then(res=>{
                this.userDeviceList = res.data.result
                // for(let i=0;i<this.userDeviceList.length;i++){
                //     this.$set(this.userDeviceList[i],'active',false)
                // }
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err.response)
                this.getAccessTokenFunc()
                this.isLoading = false
            })
        },
        async geUsertDeviceLocationList(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
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
            paramPut.target = this.user_id
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
                target:this.user_id,
                map_type:'BAIDU'
            }}).then(res=>{
                this.userDeviceLocationList = res.data.result
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        async getDeviceLocationList(imeiStr){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
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
            paramPut.imeis = imeiStr
            paramPut.map_type='BAIDU'
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            // console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
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
                imeis:imeiStr,
                map_type:'BAIDU'
            }}).then(res=>{
                console.log('deviceLocationList',res)
                this.deviceLocationList = res.data.result
                this.userlng = res.data.result[0].lng
                this.userlat = res.data.result[0].lat
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        async getDeviceTrackList(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')
            let begin_time = '2020-12-01 15:00:00'
            let end_time = this.time
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
            // console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
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
                console.log('getDeviceTrackList',res)
                // this.deviceLocationList = res.data.result
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
        },
        makeFence(){
            // this.fenceModal = true
        },
        cancel(){

        },
        async createDeviceFence(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            if(this.fenceData.fence_name == ''){
                return this.error('围栏名称')
            }
            this.isAdding = true
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:ss"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.plat.fence.create'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = this.imeiStr
            paramPut.fence_name = this.fenceData.fence_name
            paramPut.fence_shape = 'circle'
            paramPut.status = this.fenceData.status
            paramPut.lng = this.circlePath.center.lng
            paramPut.lat = this.circlePath.center.lat
            paramPut.radius = parseInt(this.circlePath.radius)
            paramPut.scale = this.fenceData.scale
            paramPut.map_type = 'baidu'
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            paramPut.sign = upper
            console.log(paramPut)
            const qs = require('query-string');
            const config = {
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }
            await axios.post(this.openApiUrl,qs.stringify(paramPut),config)
                .then(res=>{
                    console.log(res)
                    if(res.data.code == 0){
                        this.success(res.data.message)
                    }else{
                        this.error(res.data.message)
                    }
                    this.isAdding = false
                    this.fenceModal = false
                    // this.allPolygonPath.push()
                })
                .catch(err=>{
                    console.log(res)
                })
        },
        async getDeviceFence(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            var md5 = require('md5');
            var moment= require('moment') 
            const qs = require('query-string');
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.plat.fence.query'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = this.imeiStr
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
            console.log(paramPut)
            await axios.get(this.openApiUrl,{params:paramPut})
            .then(res=>{
                console.log('getDeviceFence',res)
                this.allPolygonPath = res.data.result
            })
            .catch(err=>{
                console.log(err)
            })
        },
        async deleteFence(fence){
            console.log(fence)
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            var md5 = require('md5');
            var moment= require('moment') 
            const qs = require('query-string');
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.plat.fence.delete'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = this.imeiStr
            paramPut.fence_id = fence.fenceId
            let ordered = {}
            Object.keys(paramPut).sort().forEach(function (key){
                ordered[key] = paramPut[key]
            })
            let str = Object.keys(ordered).map(function(key){
                return "" + key + ordered[key]
            }).join("")
            let appSecret = "0aedd5165f824284b57c918595a8cac4";
            // console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            paramPut.sign = upper
            const config = {
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }
            axios.post(this.openApiUrl,qs.stringify(paramPut),config)
                .then(res=>{
                    console.log('delteFence',res)
                    this.success(res.data.message)
                    let index = this.allPolygonPath.indexOf(fence)
                    if(index > -1){
                        this.allPolygonPath.splice(index,1)
                    }
                    console.log("this.allPolygonPath", this.allPolygonPath);
                })
                .catch(err=>{
                    console.log(err)
                })

        },
        selFence(fence){
            console.log(fence)
            for(let i=0;i<this.allPolygonPath.length;i++){
                delete this.allPolygonPath[i].active
            }
            this.$set(fence,'active',true)
            this.userlat = fence.lat
            this.userlng = fence.lng
        }
    },
}
</script>
<style>
.map {
  width: 100%;
  height: 78vh;
}
</style>