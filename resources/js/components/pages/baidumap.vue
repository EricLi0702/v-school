<template>
    <div class="p-3" id="baidumapComponent">
        <div class="display-flex">
            <button class="addbtn" @click="addNewPolygon">{{ isAdding ? 'End' : 'Add' }}</button>
            <Input search placeholder="Enter something..." v-model="keyword" style="width:300px"/>          
        </div>
        <baidu-map class="map" center="沈阳" :zoom="1" :scroll-wheel-zoom="true" @click="addPoint" @rightclick="drawNewpolygon">
            <div >
                <bm-polygon :path="polygonPathData" v-for="(polygonPathData,i) in allPolygonPath" :key="i" stroke-color="blue" fill-color="red" :fill-opacity="0.8" :stroke-opacity="0.5" :stroke-weight="2" @click="selPolygon(polygonPathData,i)" :editing="false"/>
            </div>
            <bm-polygon :path="polygonPath" stroke-color="blue" fill-color="red" :fill-opacity="0.8" :stroke-opacity="0.5" :stroke-weight="2"  :editing="false"/>
            
            <!-- <bm-marker :position="{lng: userlng, lat: userlat}">
                <bm-label content="Tiananmen" :labelStyle="{color: 'red', fontSize : '24px'}" :offset="{width: -35, height: 30}"/>
            </bm-marker> -->
            <bm-control>
                
            </bm-control>
            <bm-local-search :keyword="keyword" :auto-viewport="true" :forceLocal="true" :panel="false" :selectFirstResult="true" location="沈阳"></bm-local-search>
            <bm-geolocation anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :showAddressBar="true" :autoLocation="true"></bm-geolocation>
        </baidu-map>
    </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'
export default {
    data () {
        return {
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
            account:'',
            userDeviceList:[],
            userDeviceLocationList:[],
            deviceLocationList:[],
            createLng:'',
            createLat:''

        }
    },
    async created(){
        
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
            if(this.createLng == ''){
                this.createLng = lng
                this.createLat = lat
            }else{
                this.createLat += ',' + lat
                this.createLng += ',' + lng 
            }
            console.log(this.createLng)
            console.log(this.createLat)
            this.addPolygonPoint(lng,lat)
        },
        drawNewpolygon(e){
            if (!this.isAdding || this.polygonPath.length == 0) {
                return
            }
            this.allPolygonPath.push(this.polygonPath)
            this.isAdding = false
            this.polygonPath = []
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
            console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            console.log(upper)
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
                console.log('111',res)
                this.accessToken = res.data.result.accessToken
                this.refreshToken = res.data.result.refreshToken
                this.$store.commit('setAccessToken',this.accessToken)
                this.$store.commit('setRefreshToken',this.refreshToken)
                this.account = res.data.result.account
                this.getUserDeviceList()
                this.isLoading = false
            }).catch(err=>{
                console.log('error',err)
                this.isLoading = false
            })
            console.log(this.account)
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
            console.log(appSecret + str + appSecret)
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
            console.log(appSecret + str + appSecret)
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
                target:this.account
            }}).then(res=>{
                console.log('111',res)
                this.userDeviceList = res.data.result
                console.log('userDeviceList',this.userDeviceList)
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
            console.log(appSecret + str + appSecret)
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
                console.log('111',res)
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
            console.log(appSecret + str + appSecret)
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
                imeis:'868120246600230',
                map_type:'BAIDU'
            }}).then(res=>{
                console.log('111',res)
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
            console.log(appSecret + str + appSecret)
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
                console.log('111',res)
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
            paramPut.fence_name = 'hospital'
            paramPut.alarm_type = 'in'
            paramPut.report_mode = '1'
            paramPut.alarm_switch = 'ON'
            paramPut.lng = this.createLng
            paramPut.lat = this.createLat
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
            console.log(appSecret + str + appSecret)
            let md5Secret = md5 (appSecret + str + appSecret)
            let upper = md5Secret.toUpperCase()
            paramPut.sign = upper
            console.log(paramPut)
            const res = await this.callApi('post',this.openApiUrl,{
                    sign:upper,
                    timestamp:this.time,
                    v:this.v,
                    app_key:this.appKey,
                    method:"jimi.open.device.fence.create",
                    format:this.format,
                    sign_method:this.sign_method,
                    access_token:this.accessToken,
                    imei:'868120246600230',
                    fence_name:"hospital3",
                    alarm_type:"in",
                    report_mode:"1",
                    alarm_switch:"ON",
                    lng:this.createLng,
                    lat:this.createLat,
                    radius:"20",
                    zoom_level:"17",
                    map_type:"BAIDU"
                })
            console.log(res)
            // await axios({
            //     method:'post',
            //     url:this.openApiUrl,
            //     data:{
            //         sign:upper,
            //         timestamp:this.time,
            //         v:this.v,
            //         app_key:this.appKey,
            //         method:"jimi.open.device.fence.create",
            //         format:this.format,
            //         sign_method:this.sign_method,
            //         access_token:this.accessToken,
            //         imei:'868120246600230',
            //         fence_name:"hospital3",
            //         alarm_type:"in",
            //         report_mode:"1",
            //         alarm_switch:"ON",
            //         lng:this.createLng,
            //         lat:this.createLat,
            //         radius:"20",
            //         zoom_level:"17",
            //         map_type:"BAIDU"
            //     }
            // }).then(res=>{
            //     console.log(res)
            // })
            // .catch(err=>{
            //     console.log(err)
            // })
            // await axios.post(this.openApiUrl,{data:{
            //     sign:upper,
            //     timestamp:this.time,
            //     v:this.v,
            //     app_key:this.appKey,
            //     method:"jimi.open.device.fence.create",
            //     format:this.format,
            //     sign_method:this.sign_method,
            //     access_token:this.accessToken,
            //     imei:'868120246600230',
            //     fence_name:"hospital",
            //     alarm_type:"in",
            //     report_mode:"1",
            //     alarm_switch:"ON",
            //     lng:this.createLng,
            //     lat:this.createLat,
            //     radius:"20",
            //     zoom_level:"17",
            //     map_type:"BAIDU"
            // }})
            //     .then(res=>{
            //         console.log(res)
            //     })
            //     .catch(err=>{
            //         console.log(res)
            //     })
        },
        
    },

}
</script>
<style>
.map {
  width: 100%;
  height: 78vh;
}
</style>