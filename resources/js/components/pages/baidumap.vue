<template>
<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 p-2 border-right">
                <div class=" w-100 mb-2 p-2 border" v-for="device in userDeviceList" :key="device.imei" :class="{'bg-primary text-white':device.active}">
                    <div @click="selDevice(device)">
                        <!-- <Checkbox v-model="device.active"></Checkbox> -->
                        <span>{{device.deviceName}}</span>
                    </div>
                    <div>
                        <span @click="getDeviceTrackList(device.imei)" class="">轨迹回放</span>
                        <span @click="realTracking(device.imei)" class="" :class="{'text-red':trackFlag}">实时跟踪</span>
                    </div>
                </div>
            </div>
            <div class="col-2 p-2 border-right">
                <div class="w-100 mb-2 p-2 border" v-for="fence in allPolygonPath" :key="fence.fenceName" @click="selFence(fence)">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">{{fence.fenceName}}</span>
                        <Icon type="md-trash" @click="deleteFence(fence)" class="float-right"/>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">告警类型</span>
                        <span class="float-right">{{fence.alarmType}}</span>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div id="baidumapComponent">
                    <div class="display-flex">
                        <button class="addbtn" @click="addNewPolygon">{{ isAdding ? 'End' : 'Add' }}</button>
                        <Input search placeholder="Enter something..." v-model="keyword" style="width:300px"/>          
                    </div>
                    <baidu-map class="map" :center="{lng:userlng, lat:userlat}" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint" @rightclick="drawNewpolygon">
                        <!-- <div >
                            <bm-polygon :path="polygonPathData" v-for="(polygonPathData,i) in allPolygonPath" :key="i" stroke-color="blue" fill-color="red" :fill-opacity="0.8" :stroke-opacity="0.5" :stroke-weight="2" @click="selPolygon(polygonPathData,i)" :editing="false"/>
                        </div>
                        <bm-polygon :path="polygonPath" stroke-color="blue" fill-color="red" :fill-opacity="0.8" :stroke-opacity="0.5" :stroke-weight="2"  :editing="false"/> -->
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
                
                <div class="col-3 text-right mt-1">告警类型</div>
                <div class="col-9 mt-1">
                    <RadioGroup v-model="fenceData.alarm_type">
                        <Radio label="in"></Radio>
                        <Radio label="out"></Radio>
                        <Radio label="in,out"></Radio>
                    </RadioGroup>
                </div>
                
                <div class="col-3 text-right mt-1">	报警上报方式</div>
                <div class="col-9 mt-1">
                    <RadioGroup v-model="fenceData.report_mode">
                        <Radio label="0">仅GPRS</Radio>
                        <Radio label="1">SMS+GPRS</Radio>
                    </RadioGroup>
                </div>
                
                <div class="col-3 text-right mt-1">围栏报警开关</div>
                <div class="col-9 mt-1">
                    <RadioGroup v-model="fenceData.alarm_switch">
                        <Radio label="ON">开启</Radio>
                        <Radio label="OFF">关闭</Radio>
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
                    <!-- <Input v-model="circlePath.center.radius"/> -->
                    <!-- <InputNumber :max="9999" :min="1" v-model="circlePath.center.radius"></InputNumber> -->
                    {{parseInt(circlePath.radius)}}
                </div>
                
                <div class="col-3 text-right mt-1">缩放级别</div>
                <div class="col-9 mt-1">
                    <!-- <Input v-model="fenceData.zoom_level"/> -->
                    <InputNumber :max="19" :min="3" v-model="fenceData.zoom_level"></InputNumber>
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
                alarm_type:'in',
                report_mode:'0',
                alarm_switch:'ON',
                zoom_level:3
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
        }
    },
    async created(){
        // this.getAccessTokenFunc();
        this.accessToken = this.getAccessToken;
        if(this.accessToken == undefined){
            this.getAccessTokenFunc();
        }else{
            this.getUserDeviceList()
        }
        console.log('accessToken',this.getAccessToken)
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
            // if (!this.isAdding || this.polygonPath.length == 0) {
            //     return
            // }
            this.fenceModal = true
            // this.allPolygonPath.push(this.polygonPath)
            this.isAdding = false
            // this.polygonPath = []
        },
        // async storePolygon(){
        //     if(this.isAdding){
        //         return this.info("保存前请按结束按钮。")
                
        //     }
        //     if(this.allPolygonPath.length == 0 && this.polygonPath.length == 0){
        //         return this.error('请添加多边形。')
        //     }
        //     this.isSaving = true
        //     await axios.get('/api/fence',{
        //         params:{
        //             userId:this.$store.state.user.id
        //         }
        //     }).then(res=>{
        //         if(res.status == 200){
        //             if(res.data.length > 0){
        //                 this.isNew = false;
        //             }else{
        //                 this.isNew = true;
        //             }
        //         }
        //     })
        //     let res
        //     if(this.isNew){
        //         res = await this.callApi('post','/api/fence',{
        //             fence:this.allPolygonPath,
        //             userId:this.$store.state.user.id
        //         });
        //     }else{
        //         res = await this.callApi('put','/api/fence',{
        //             fence:this.allPolygonPath,
        //             userId:this.$store.state.user.id
        //         });
        //     }
            
        //     if(res.status == 201){
        //         this.success('已保存')
        //     }else{
        //         if(res.status == 200){
        //             this.success('已保存');
        //         }
        //         else if(res.status === 422){
        //             for(let i in res.data.errors){
        //                 this.error(res.data.errors[i][0])
        //             }
        //         }else{
        //             this.swr()
        //         }
        //     }
        //     this.isSaving = false
        // },
        // checkFence(){
        //     console.log(this.allPolygonPath)
        //     this.isChecking = !this.isChecking;
        //     const self = this
            
        //     if(this.isChecking){
        //         this.fenceCheck = setInterval(function(){self.fetchHole()},20000);
        //     }else{
        //         clearInterval(this.fenceCheck);
        //     }
            
        // },
        // fetchHole(){
        //     this.getDeviceLocationList();
        //     var BMap = require('bmaplib').BMap;
        //     var BMapLib = require('bmaplib').BMapLib;
        //     var pts = []
        //     for(let j=0;j<this.allPolygonPath.length;j++){
        //         this.polygonPath = this.allPolygonPath[j]
        //         for(let i =0; i<this.polygonPath.length;i++){
        //             var pt = new BMap.Point(this.polygonPath[i].lng, this.polygonPath[i].lat);
        //             pts.push(pt)
        //         }
        //     }
        //     var ply = new BMap.Polygon(pts);
        //     // this.userlat += 0.0001
        //     // this.userlng += 0.0001
        //     console.log('userlat,userlng',this.userlat,this.userlng)
        //     var pt =new BMap.Point(this.userlng,this.userlat );
        //     var result = BMapLib.GeoUtils.isPointInPolygon(pt, ply);
        //     if(result == false){
        //         this.error('学生走出电子篱笆。')
        //     }else{
        //         this.success('学生在电子围栏。')
        //     }
        // },
        selPolygon(item,index){
            this.isSelected = true;
            this.polygonPath = item;
            this.selectedIdx = index;
        },
        // async deletePolygon(){
        //     if(this.selectedIdx == null){
        //         return this.info("请添加多边形。")
        //     }
        //     this.isDeleting = true;
        //     this.allPolygonPath.splice(this.selectedIdx,1)
        //     let res
        //     if(this.allPolygonPath.length == 0){
        //         res = await this.callApi('delete','/api/fence',{userId:this.$store.state.user.id});
               
        //     }else{
        //         res = await this.callApi('put','/api/fence',{userId:this.$store.state.user.id,fence:this.allPolygonPath})
        //     }
        //     if(res.status == 200){
        //         this.success('成功删除')
        //     }else{
        //         if(res.status == 422){
        //             for(let i in res.data.errors){
        //                 this.error(res.data.errors[i][0])
        //             }
        //         }else{
        //             this.swr()
        //         }
        //     }
            
            
        //     this.polygonPath = []
        //     this.selectedIdx = null;
        //     this.isDeleting = false;
        // },
        //fence api 
        selDevice(device){
            for(let i=0;i<this.userDeviceList.length;i++){
                delete this.userDeviceList[i].active
            }
            // if(device.active == undefined){
            //     this.$set(device,'active',true)
            // }else{
            //     device.active = ! device.active
            // }
            this.$set(device,'active',true)
            console.log(device)
            if(device.active == true){
                // if(this.imeiStr == ''){
                //     this.imeiStr = device.imei
                // }else{
                //     this.imeiStr += ',' + device.imei
                // }
                this.imeiStr = device.imei
                
            }else{
                // let devices = this.imeiStr.split(',')
                // console.log(devices)
                // devices.pop(device.imei)
                // this.imeiStr = ''
                // for(let i =0;i<devices.length;i++){
                //     if(this.imeiStr == ''){
                //         this.imeiStr = devices[i]
                //     }else{
                //         this.imeiStr += ',' + devices[i]
                //     }
                // }
                this.imeiStr = ''
            }
            console.log(this.imeiStr)
            this.getDeviceLocationList(this.imeiStr)
            this.getDeviceFence()            
        },
        realTracking(device){
            this.trackFlag = ! this.trackFlag
            if(this.trackFlag == true){
                console.log('realTracking',device)
                let self = this
                this.fenceCheck = setInterval(function(){self.getDeviceLocationList(device)}, 20000);
            }else{
                console.log('clearTrack')
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
            console.log('getAccessToken')
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
                console.log('accessToken',res)
                this.accessToken = res.data.result.accessToken
                this.refreshToken = res.data.result.refreshToken
                this.$store.commit('setAccessToken',this.accessToken)
                this.$store.commit('setRefreshToken',this.refreshToken)
                this.getUserDeviceList()
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
            console.log(this.accessToken)
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
                console.log('111',res)
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
            console.log('createplatformaccount')
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
                console.log('111',res)
                this.userDeviceList = res.data.result
                // for(let i=0;i<this.userDeviceList.length;i++){
                //     this.$set(this.userDeviceList[i],'active',false)
                // }
                console.log('userDeviceList',this.userDeviceList)
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
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
                console.log('userDeviceLocationList',res)
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
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.device.fence.create'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = this.imeiStr
            paramPut.fence_name = this.fenceData.fence_name
            paramPut.alarm_type = this.fenceData.alarm_type
            paramPut.report_mode = this.fenceData.report_mode
            paramPut.alarm_switch = this.fenceData.alarm_switch
            paramPut.lng = this.circlePath.center.lng
            paramPut.lat = this.circlePath.center.lat
            paramPut.radius = parseInt(this.circlePath.radius)
            paramPut.zoom_level = this.fenceData.zoom_level
            paramPut.map_type = 'baidu'
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
            console.log(paramPut)
            const qs = require('query-string');
            const config = {
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }
            await axios.post(this.openApiUrl,qs.stringify({
                sign:upper,
                timestamp:this.time,
                v:this.v,
                app_key:this.appKey,
                method:"jimi.open.device.fence.create",
                format:this.format,
                sign_method:this.sign_method,
                access_token:this.accessToken,
                imei:this.imeiStr,
                fence_name:this.fenceData.fence_name,
                alarm_type:this.fenceData.alarm_type,
                report_mode:this.fenceData.report_mode,
                alarm_switch:this.fenceData.alarm_switch,
                lng:this.circlePath.center.lng,
                lat:this.circlePath.center.lat,
                radius:parseInt(this.circlePath.radius),
                zoom_level:this.fenceData.zoom_level,
                map_type:"baidu"
            }),config)
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

            paramPut.method = 'jimi.open.device.fence.get'
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
            // console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            const config = {
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }
            await axios.post(this.openApiUrl,qs.stringify({
                sign:upper,
                method:'jimi.open.device.fence.get',
                timestamp:this.time,
                app_key:this.appKey,
                sign_method:this.sign_method,
                v:this.v,
                format:this.format,
                access_token:this.accessToken,
                imei:this.imeiStr
            }),config)
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

            paramPut.method = 'jimi.open.device.fence.delete'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = this.imeiStr
            paramPut.instruct_no = fence.instructNo
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
                    console.log(res)
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