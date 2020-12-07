<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-2 p-2 border-right">
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
            <div class="col-2 p-2 border-right">
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
            <div  class="p-3 col-8" id="baidumapComponent">
                <div class="display-flex">
                    <button class="addbtn" @click="addNewPolygon">{{ isAdding ? 'End' : 'Add' }}</button>
                    <Input search placeholder="Enter something..." v-model="keyword" style="width:300px"/>          
                </div>
                <baidu-map class="map" :center="{lng:userlng,lat:userlat}" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint" @rightclick="drawNewpolygon">
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
                    <Button type="primary" :loading="isAdding" :disabled="isAdding" @click="createDeviceFence">提交</Button>
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
            console.log(paramPut)
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
                console.log('deviceLocationList',res)
                this.deviceLocationList = res.data.result
                this.userlng = res.data.result[0].lng
                this.userlat = res.data.result[0].lat
                this.getDeviceFence()
                this.fetchHole()
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.getAccessTokenFunc()
                this.isLoading = false
            })
        },
        realTracking(){
            this.getDeviceLocationList()
            let self = this
            setInterval(function(){self.getDeviceLocationList()}, 20000);
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
                console.log(res)
            })
        },
        selDevice(device){
            for(let i=0;i<this.userDeviceList.length;i++){
                delete this.userDeviceList[i].active
            }
            this.$set(device,'active',true)
            this.imeiStr = device.imei
            this.realTracking()
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
        },
        async createDeviceFence(){
            let payload = {}
            payload.fenceName = this.fenceData.fenceName
            payload.fenceType = this.fenceData.fenceType
            payload.imei = this.imeiStr
            payload.location = this.polygonPath
            console.log(payload)
            this.isSaving = true
            const res = await this.callApi('post','/api/fence',payload)
            console.log(res)
            if(res.status == 201){
                this.success('success')
                this.fenceModal = false
                res.data.location = JSON.parse(res.data.location)
                this.allPolygonPath.push(res.data)
            }
        },
        fetchHole(){
            console.log('checkFence',this.allPolygonPath)
            var BMap = require('bmaplib').BMap;
            var BMapLib = require('bmaplib').BMapLib;
            var pts = []
            if(this.allPolygonPath.length == 0){
                return
            }
            for(let i =0; i<this.allPolygonPath.length;i++){
                for(let j=0;j<this.allPolygonPath[i].location.length;j++){
                    var pt = new BMap.Point(this.allPolygonPath[i].location[j].lng, this.allPolygonPath[i].location[j].lat);
                    pts.push(pt)
                }
            }
            var ply = new BMap.Polygon(pts);
            // this.userlat += 0.0001
            // this.userlng += 0.0001
            var pt =new BMap.Point(this.userlng,this.userlat );
            var result = BMapLib.GeoUtils.isPointInPolygon(pt, ply);
            if(result == false){
                this.error('学生走出电子篱笆。')
            }else{
                this.success('学生在电子围栏。')
            }
        },
        selFence(item){
            for(let i=0;i<this.allPolygonPath.length;i++){
                delete this.allPolygonPath[i].active
            }
            console.log(item)
            this.$set(item,'active',true)
        },
        async deleteFence(fence){
            console.log(fence)
            const res = await this.callApi('delete','/api/fence',{fenceId:fence.id})
            console.log(res)
            if(res.status == 200){
                this.success('success')
                let index = this.allPolygonPath.indexOf(fence)
                if(index > -1){
                    this.allPolygonPath.splice(index,1)
                }
            }
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