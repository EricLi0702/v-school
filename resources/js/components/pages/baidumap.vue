<template>
    <div class="p-3" id="baidumapComponent">
        <div class="display-flex">
            <button class="addbtn" @click="storePolygon">{{ isSaving ? 'Saving' : 'Save' }}</button>
            <button class="addbtn" @click="addNewPolygon">{{ isAdding ? 'End' : 'Add' }}</button>
            <button class="addbtn" @click="checkFence">{{ isChecking ? 'Checking' : 'Check' }}</button>
            <button class="addbtn" @click="deletePolygon">{{ isDeleting ? 'Deleting' : 'Delete' }}</button>
            <Input search placeholder="Enter something..." v-model="keyword" style="width:300px"/>          
        </div>
        <baidu-map class="map" :center="{lng: 123.474976, lat: 41.695735}" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint" @rightclick="drawNewpolygon">
            <div v-for="(polygonPathData,i) in allPolygonPath" :key="i">
                <bm-polygon :path="polygonPathData" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2" @click="selPolygon(polygonPathData,i)" :editing="true" @lineupdate="updatePolygonPath"/>
            </div>
            <bm-polygon :path="polygonPath" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2"  :editing="true" @lineupdate="updatePolygonPath"/>
            
            <bm-marker :position="{lng: userlng, lat: userlat}" :dragging="true" animation="BMAP_ANIMATION_BOUNCE">
            <!-- <bm-label content="Tiananmen" :labelStyle="{color: 'red', fontSize : '24px'}" :offset="{width: -35, height: 30}"/> -->
            </bm-marker>
            <bm-control>
                
            </bm-control>
            <bm-local-search :keyword="keyword" :auto-viewport="true" :forceLocal="true" :panel="false" :selectFirstResult="true" location="沈阳"></bm-local-search>
            <bm-geolocation anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :showAddressBar="true" :autoLocation="true"></bm-geolocation>
            <!-- <bm-navigation anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-navigation>
            <bm-map-type :map-types="['BMAP_NORMAL_MAP', 'BMAP_HYBRID_MAP']" anchor="BMAP_ANCHOR_TOP_LEFT"></bm-map-type>
            <bm-overview-map anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :isOpen="true"></bm-overview-map>
            
            <bm-ground
            :bounds="{ne: {lng: 110, lat: 40}, sw:{lng: 0, lat: 0}}" imageURL="http://dafrok.github.io/vue-baidu-map/favicon.png">
            </bm-ground> -->
        </baidu-map>
    </div>
</template>

<script>
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
            userlng:123.474976,
            userlat:41.695735,
            keyword:'',
            fenceCheck:'',
            selectedIdx:null,
            isNew:true,
            
        }
    },
    async created(){
        await axios.get('/api/fence',{
            params:{
                userId:this.$store.state.user.id
            }
        }).then(res=>{
            if(res.status == 200 && res.data.length){
                console.log(res.data)
                this.allPolygonPath = JSON.parse(res.data[0].fence);
                this.isNew = false;
            }
        })
        console.log('test',this.allPolygonPath)
        
    },
    mounted(){
        
    },
    computed:{
        axiosParams(){
            const params = new URLSearchParams();
            params.append('userId',this.$store.state.user.id)
            return params;
        }
    },
    methods: {
        updatePolygonPath (e) {
            this.polygonPath = e.target.getPath()
        },
        addPolygonPoint (lng,lat) {
            this.polygonPath.push({lng: lng, lat: lat})
            console.log(this.polygonPath)
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
            console.log(e)
            const {lng,lat} = e.Ag;
            this.addPolygonPoint(lng,lat)
        },
        drawNewpolygon(e){
            if (!this.isAdding || this.polygonPath.length == 0) {
                return
            }
            this.allPolygonPath.push(this.polygonPath)
            this.polygonPath = []
            console.log(this.allPolygonPath)
        },
        async storePolygon(){
            if(this.isAdding){
                return this.info("Please press end button before saving")
                
            }
            if(this.allPolygonPath.length == 0 && this.polygonPath.length == 0){
                return this.error('Please add polygon')
            }
            this.isSaving = true
            console.log(this.allPolygonPath)
            await axios.get('/api/fence',{
                params:{
                    userId:this.$store.state.user.id
                }
            }).then(res=>{
                if(res.status == 200){
                    console.log('olddata',res.data)
                    if(res.data.length > 0){
                        this.isNew = false;
                    }else{
                        this.isNew = true;
                    }
                }
            })
            let res
            if(this.isNew){
                res = await this.callApi('post','/api/fence',{
                    fence:this.allPolygonPath,
                    userId:this.$store.state.user.id
                });
            }else{
                res = await this.callApi('put','/api/fence',{
                    fence:this.allPolygonPath,
                    userId:this.$store.state.user.id
                });
            }
            
            console.log(res)
            if(res.status == 201){
                this.success('saved')
            }else{
                if(res.status == 200){
                    this.success('saved');
                }
                else if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            this.isSaving = false
        },
        checkFence(){
            
            console.log('currentpolygon',this.polygonPath)
            if(this.polygonPath.length == 0){
                return this.info('please select fence')
            }
            this.isChecking = !this.isChecking;
            const self = this
            
            if(this.isChecking){
                this.fenceCheck = setInterval(function(){self.fetchHole()},2000);
            }else{
                clearInterval(this.fenceCheck);
            }
            
        },
        fetchHole(){
            var BMap = require('bmaplib').BMap;
            var BMapLib = require('bmaplib').BMapLib;
            var pts = []
            for(let i =0; i<this.polygonPath.length;i++){
                var pt = new BMap.Point(this.polygonPath[i].lng, this.polygonPath[i].lat);
                pts.push(pt)
            }
            var ply = new BMap.Polygon(pts);
            this.userlat += 0.0001
            this.userlng += 0.0001
            var pt =new BMap.Point(this.userlng,this.userlat );
            var result = BMapLib.GeoUtils.isPointInPolygon(pt, ply);
            if(result == false){
                console.log('student is out of electronic fence')
            }else{
                console.log('student is in electronic fence')
            }
        },
        selPolygon(item,index){
            this.isSelected = true;
            this.polygonPath = item;
            this.selectedIdx = index;
            console.log(this.selectedIdx)
        },
        async deletePolygon(){
            if(this.selectedIdx == null){
                return this.info("please select polygon")
            }
            this.isDeleting = true;
            this.allPolygonPath.splice(this.selectedIdx,1)
            let res
            if(this.allPolygonPath.length == 0){
                res = await this.callApi('delete','/api/fence',{userId:this.$store.state.user.id});
               
            }else{
                res = await this.callApi('put','/api/fence',{userId:this.$store.state.user.id,fence:this.allPolygonPath})
            }
            if(res.status == 200){
                this.success('Successfully deleted')
            }else{
                if(res.status == 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            
            console.log(this.allPolygonPath)
            
            this.polygonPath = []
            this.selectedIdx = null;
            this.isDeleting = false;
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