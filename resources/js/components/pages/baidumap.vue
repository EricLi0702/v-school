<template>
    <div class="container-fluid p-0">
        <div class="row m-0" v-if="!$isMobile()">
            <div class="col-2 p-2 border-right">
                <div v-if="userDeviceList.length == 0" class="row justify-content-center pt-3 m-0" >
                    <img src="/img/icon/loadingIcon.gif" style="width: 30px;" alt="">
                </div>
                <div v-else class=" w-100 mb-2 p-2 border has-clicked" v-for="device in userDeviceList" :key="device.imei" :class="{'selDevice':device.active}" @click="selDevice(device)">
                    <div>
                        <span>{{device.deviceName}}</span>
                    </div>
                    <div>
                        <span>{{device.imei}}</span>
                    </div>
                </div>
            </div>
            <div class="col-2 p-2 border-right">
                <div v-if="allPolygonPath.length == 0" class="row justify-content-center pt-3 m-0" >
                    <img src="/img/icon/loadingIcon.gif" style="width: 30px;" alt="">
                </div>
                <div v-else class="w-100 mb-2 p-2 border has-clicked" v-for="fence in allPolygonPath" :key="fence.fenceName" @click="selFence(fence)" :class="{'bg-primary text-white':fence.active}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">{{fence.fenceName}}</span>
                        <Icon type="md-trash" @click="deleteFence(fence)" class="float-right"/>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">告警类型</span>
                        <span class="float-right">{{fence.fenceType}}</span>
                    </div>
                </div>
            </div>
            <div  class="p-3 col-8" id="baidumapComponent">
                <div class="display-flex">
                    <button class="addbtn" @click="addNewPolygon" v-if="imeiStr != ''">{{ isAdding ? '结束' : '加' }}</button>
                    <!-- <button class="addbtn" @click="instruction" >list</button> -->
                    <button class="addbtn" @click="sosAdd" v-if="imeiStr != ''">家长电话号码设置</button>
                    <!-- <button class="addbtn" @click="sosDelete">sosDelete</button> -->
                    <!-- <button class="addbtn" @click="instructionResult" v-if="imeiStr != ''" >instructionResult</button> -->
                    <Input search placeholder="输入一些东西..." v-model="keyword" style="width:300px"/>          
                </div>
                <baidu-map class="map" :center="{lng:centerLng,lat:centerLat}" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint" @rightclick="drawNewpolygon">
                    <div v-for="(polygonPathData,i) in allPolygonPath" :key="i">
                        <bm-polygon :path="polygonPathData.location" fill-color="red" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2" @click="selFence(polygonPathData)" :editing="false" @lineupdate="updatePolygonPath">
                        </bm-polygon>
                    </div>
                    <bm-polygon :path="polygonPath" fill-color="red" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2"  :editing="true" @lineupdate="updatePolygonPath"/>
                    
                    <bm-marker :position="{lng: userlng, lat: userlat}" :dragging="true" animation="BMAP_ANIMATION_BOUNCE">
                        <!-- <bm-label content="Tiananmen" :labelStyle="{color: 'red', fontSize : '24px'}" :offset="{width: -35, height: 30}"/> -->
                    </bm-marker>
                    <bm-control>
                        
                    </bm-control>
                    <bm-local-search :keyword="keyword" :auto-viewport="true" :forceLocal="true" :panel="false" :selectFirstResult="true" location="沈阳"></bm-local-search>
                    <bm-geolocation anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :showAddressBar="true" :autoLocation="true"></bm-geolocation>
                </baidu-map>
            </div>
        </div>
        <div class="row m-0" v-else>
            <div class="col-12 p-2 border-bottom">
                <div v-if="userDeviceList.length == 0" class="row justify-content-center pt-3 m-0" >
                    <div class="text-center">
                        <p>电子学生证清单</p>
                        <img src="/img/icon/loadingIcon.gif" style="width: 30px;" alt="">
                    </div>
                </div>
                <div class=" w-100 mb-2 p-2 border" v-for="device in userDeviceList" :key="device.imei" :class="{'bg-primary text-white':device.active}">
                    <div @click="selDevice(device)">
                        <span>{{device.deviceName}}</span>
                    </div>
                    <div>
                        <span>{{device.imei}}</span>
                        <!-- <span @click="getDeviceTrackList(device.imei)" class="">轨迹回放</span>
                        <span @click="realTracking(device.imei)" class="">实时跟踪</span> -->
                    </div>
                </div>
            </div>
            <div class="col-12 p-2 border-bottom">
                <div v-if="allPolygonPath.length == 0" class="row justify-content-center pt-3 m-0" >
                    <div class="text-center">
                        <p>电围栏清单</p>
                        <img src="/img/icon/loadingIcon.gif" style="width: 30px;" alt="">
                    </div>
                </div>
                <div class="w-100 mb-2 p-2 border" v-for="fence in allPolygonPath" :key="fence.fenceName" @click="selFence(fence)" :class="{'bg-primary text-white':fence.active}">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">{{fence.fenceName}}</span>
                        <Icon type="md-trash" @click="deleteFence(fence)" class="float-right"/>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="float-left">告警类型</span>
                        <span class="float-right">{{fence.fenceType}}</span>
                    </div>
                </div>
            </div>
            <div  class="p-3 col-12" id="baidumapComponent">
                <div class="display-flex">
                    <Button type="success" class="" @click="addNewPolygon" v-if="imeiStr != ''">{{ isAdding ? '结束' : '加' }}</Button>
                    <Input class="my-2" search placeholder="输入一些东西..." v-model="keyword"/>          
                </div>
                <baidu-map class="map" :center="{lng:centerLng,lat:centerLat}" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint" @rightclick="drawNewpolygon">
                    <div v-for="(polygonPathData,i) in allPolygonPath" :key="i">
                        <bm-polygon :path="polygonPathData.location" fill-color="red" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2" @click="selPolygon(polygonPathData,i)" :editing="true" @lineupdate="updatePolygonPath"/>
                    </div>
                    <bm-polygon :path="polygonPath" fill-color="red" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2"  :editing="true" @lineupdate="updatePolygonPath"/>
                    
                    <bm-marker :position="{lng: userlng, lat: userlat}" :dragging="true" animation="BMAP_ANIMATION_BOUNCE">
                        <!-- <bm-label content="Tiananmen" :labelStyle="{color: 'red', fontSize : '24px'}" :offset="{width: -35, height: 30}"/> -->
                    </bm-marker>
                    <bm-control>
                        
                    </bm-control>
                    <bm-local-search :keyword="keyword" :auto-viewport="true" :forceLocal="true" :panel="false" :selectFirstResult="true" location="沈阳"></bm-local-search>
                    <bm-geolocation anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :showAddressBar="true" :autoLocation="true"></bm-geolocation>
                </baidu-map>
            </div>
        </div>
        <Modal
            v-model="fenceModal"
            title="新增围栏"
            class="fence-modal"
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
                    <Input v-model="fenceData.fenceName" maxlength="25" show-word-limit placeholder="" />
                </div>

                <div class="col-3 text-right mt-1">围栏类型</div>
                <div class="col-9 mt-1">
                    <RadioGroup v-model="fenceData.fenceType">
                        <Radio label="水库"></Radio>
                        <Radio label="网吧"></Radio>
                        <Radio label="其他"></Radio>
                        <Radio label="考勤"></Radio>
                    </RadioGroup>
                </div>
                <div class="offset-3 col-9 mt-1">
                    <Button type="primary" :loading="isSaving" :disabled="isSaving" @click="createDeviceFence">提交</Button>
                    <Button type="default" @click="cancel">取消</Button>
                </div>
            </div>
        </Modal>

        <Modal
            v-model="phoneSetting"
            title="家长电话号码设置"
            class="fence-modal"
            footer-hide
            @on-cancel="cancel">
            <div class="row m-0 p-0">
                <div class="col-3 text-right">
                    号码1
                </div>
                <div class="col-9">
                    <Input v-model="phoneData.number1" maxlength="25" show-word-limit placeholder="" />
                </div>
                
                <div class="col-3 text-right mt-1">号码2</div>
                <div class="col-9 mt-1">
                    <Input v-model="phoneData.number2" maxlength="25" show-word-limit placeholder="" />
                </div>

                <div class="col-3 text-right mt-1">号码3</div>
                <div class="col-9 mt-1">
                    <Input v-model="phoneData.number3" maxlength="25" show-word-limit placeholder="" />
                </div>
                <div class="offset-3 col-9 mt-1">
                    <Button type="primary" :loading="isSaving" :disabled="isSaving" @click="createSosAdd">提交</Button>
                    <Button type="default" @click="cancel">取消</Button>
                </div>
            </div>
        </Modal>
        
    </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
    data () {
        return {
            alarm:'',
            fenceData:{
                fenceName:'',
                fenceType:'水库'
            },
            allPolygonPath:[],
            polygonPath: [],
            isSaving:false,
            isAdding:false,
            isChecking:false,
            isEditing:false,
            isDeleting:false,
            isSelected:false,
            centerLng:123.474976,
            centerLat:41.695735,
            userlng:123.474976,
            userlat:41.695735,
            keyword:'',
            fenceCheck:'',
            selectedIdx:null,
            isNew:true,
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
            imeiStr:'',
            userDeviceList:[],
            fenceModal:false,
            fenceCheckFlag:null,
            realTrackingFlag:false,
            baseUrl:window.Laravel.base_url,
            phoneSetting:false,
            phoneData:{
                number1:"",
                number2:"",
                number3:""
            },
            inst_id:null,
        }
    },
    async created(){
        this.accessToken = this.getAccessToken;
        console.log('accessToken',this.accessToken)
        if(this.accessToken == undefined){
            this.getAccessTokenFunc();
        }else{
            this.getUserDeviceList()
        }
        this.alarm = new Audio(`${this.baseUrl}/img/alarm.mp3`);
    },
    watch:{
        currentPath:{
            handler(val){
                if(val.query.mapView == undefined){
                    console.log('beforeDestory')
                    clearInterval(this.fenceCheckFlag);
                }
            },
            deep:true
        }
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
        ]),
        currentPath(){
            return this.$route
        }
    },
    methods: {
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
                console.log('accessTokenRes',res)
                this.accessToken = res.data.result.accessToken
                this.refreshToken = res.data.result.refreshToken
                this.$store.commit('setAccessToken',this.accessToken)
                this.$store.commit('setRefreshToken',this.refreshToken)
                // this.getUserDeviceList()
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err.response)
                this.isLoading = false
            })
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
                this.getUserDeviceList()
                this.isLoading = false
            })
        },
        async getDeviceLocationList(){
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
            paramPut.imeis = this.imeiStr
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
                imeis:this.imeiStr,
                map_type:'BAIDU'
            }}).then(res=>{
                this.deviceLocationList = res.data.result
                this.userlng = res.data.result[0].lng
                this.userlat = res.data.result[0].lat
                this.centerLng = res.data.result[0].lng
                this.centerLat = res.data.result[0].lat
                this.getDeviceFence()
                this.fetchHole()
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.getAccessTokenFunc()
                this.isLoading = false
            })
        },
        realTracking(device){
            // if(device.realTrackingFlag == undefined){
            //     this.$set(device,'realTrackingFlag',true)
            // }else{
            //     device.realTrackingFlag = ! device.realTrackingFlag
            // }
            this.getDeviceLocationList()
            let self = this
            clearInterval(this.fenceCheckFlag)
            this.fenceCheckFlag = setInterval(function(){self.getDeviceLocationList()}, 20000);
            // if(device.realTrackingFlag == true){
                
            // }else{
                
            // }
        },
        async getDeviceFence(){
            await axios.get('/api/fence',{
                params:{
                    imei:this.imeiStr
                }
            }).then(res=>{
                if(res.status == 200 && res.data.length){
                    // this.allPolygonPath = JSON.parse(res.data[0].fence);
                    // this.isNew = false;
                    for(let i=0;i<res.data.length;i++){
                        res.data[i].location = JSON.parse(res.data[i].location)
                        // this.$set(res.data[i],'active',true)
                    }
                    this.allPolygonPath = res.data
                }
            })
        },
        selDevice(device){
            for(let i=0;i<this.userDeviceList.length;i++){
                delete this.userDeviceList[i].active
            }
            this.$set(device,'active',true)
            this.imeiStr = device.imei
            this.realTracking(device)
            // this.getDeviceLocationList(this.imeiStr)
        },
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
            this.addPolygonPoint(lng,lat)
        },
        drawNewpolygon(e){
            this.fenceModal = true
            this.isAdding = false
        },
        cancel(){
            this.fenceModal = false
            this.phoneSetting = false
        },
        async createDeviceFence(){
            let payload = {}
            payload.fenceName = this.fenceData.fenceName
            payload.fenceType = this.fenceData.fenceType
            payload.imei = this.imeiStr
            payload.location = this.polygonPath
            this.isSaving = true
            const res = await this.callApi('post','/api/fence',payload)
            this.isSaving = false
            if(res.status == 201){
                this.success('操作成功。')
                this.fenceModal = false
                this.polygonPath = []
                res.data.location = JSON.parse(res.data.location)
                this.allPolygonPath.push(res.data)
            }
        },
        fetchHole(){
            var BMap = require('bmaplib').BMap;
            var BMapLib = require('bmaplib').BMapLib;
            var pts = []
            if(this.allPolygonPath.length == 0){
                return this.error('电子围栏不存在。')
            }
            for(let i =0; i<this.allPolygonPath.length;i++){
                for(let j=0;j<this.allPolygonPath[i].location.length;j++){
                    var pt = new BMap.Point(this.allPolygonPath[i].location[j].lng, this.allPolygonPath[i].location[j].lat);
                    pts.push(pt)
                }
            }
            var ply = new BMap.Polygon(pts);
            var pt =new BMap.Point(this.userlng,this.userlat );
            var result = BMapLib.GeoUtils.isPointInPolygon(pt, ply);
            if(result == false){
                this.error('学生走出电子篱笆。')
                Echo.join('fence')
                    .whisper('outFence', this.imeiStr);
                this.alarm.play();
            }else{
                this.success('学生在电子围栏。')
            }
        },
        selFence(item){
            for(let i=0;i<this.allPolygonPath.length;i++){
                delete this.allPolygonPath[i].active
            }
            this.$set(item,'active',true)
            let averLat = 0
            let averLng = 0
            for(let i=0;i<item.location.length;i++){
                averLat += item.location[i].lat
                averLng += item.location[i].lng
            }
            this.centerLat = averLat/item.location.length
            this.centerLng = averLng/item.location.length
        },
        async deleteFence(fence){
            if(this.isDeleting == true){
                return
            }
            this.isDeleting = true
            
            const res = await this.callApi('delete','/api/fence',{fenceId:fence.id})
            this.isDeleting = false
            if(res.status == 200){
                this.success('操作成功。')
                let index = this.allPolygonPath.indexOf(fence)
                if(index > -1){
                    this.allPolygonPath.splice(index,1)
                }
            }
        },
        selPolygon(fence,i){
            for(let i=0;i<this.allPolygonPath.length;i++){
                delete this.allPolygonPath[i].editing
            }
            this.$set(fence,'editing',true)
        },
        instruction(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.instruction.list'
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
            paramPut.sign = upper
            axios.get(this.openApiUrl,{params:paramPut})
                .then(res=>{
                }).catch(err=>{
                    console.log('error',err)
                })
        },
        sosAdd(){
            this.phoneSetting = ! this.phoneSetting
            
        },
        createSosAdd(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            this.isSaving = true
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.instruction.send'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = this.imeiStr
            paramPut.inst_param_json = JSON.stringify({inst_id:"148",inst_template:"SOS,A,{0},{1},{2}#",params:[this.phoneData.number1,this.phoneData.number2,this.phoneData.number3],is_cover:true})
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
            const qs = require('query-string');
            const config = {
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }
            axios.post(this.openApiUrl,qs.stringify(paramPut),config)
                .then(res=>{
                    this.isSaving =false
                    this.phoneSetting = false
                    this.phoneData.number1 = ''
                    this.phoneData.number2 = ''
                    this.phoneData.number3 = ''
                }).catch(err=>{
                    this.isSaving = false
                    console.log('error',err)
                })
        },
        sosDelete(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.instruction.send'
            paramPut.timestamp = this.time
            paramPut.app_key = this.appKey
            paramPut.sign_method = this.sign_method
            paramPut.v = this.v
            paramPut.format = this.format
            paramPut.access_token = this.accessToken
            paramPut.imei = this.imeiStr
            paramPut.inst_param_json = JSON.stringify({inst_id:"82",inst_template:"SOS,D,{0},{1},{2}#",params:["15640052113","",""],is_cover:true})
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
            const qs = require('query-string');
            const config = {
                headers:{
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            }
            axios.post(this.openApiUrl,qs.stringify(paramPut),config)
                .then(res=>{
                }).catch(err=>{
                    console.log('error',err)
                })
        },
        instructionResult(){
            if(this.accessToken == undefined){
                this.getAccessTokenFunc();
            }
            var md5 = require('md5');
            var moment= require('moment') 
            let paramPut = {}
            this.time = moment().format(("YYYY-MM-DD HH:mm:SS"));
            this.user_pwd_md5 = md5('VVuFiyVd6uaGfCj')

            paramPut.method = 'jimi.open.instruction.result'
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
            paramPut.sign = upper
            axios.get(this.openApiUrl,{params:paramPut})
                .then(res=>{
                }).catch(err=>{
                    console.log('error',err)
                })
        }
    },
    beforeDestroy: function(){
        console.log('beforeDestory')
        clearInterval(this.fenceCheck);
    },

}
</script>
<style>
.map {
  width: 100%;
  height: 78vh;
}
</style>