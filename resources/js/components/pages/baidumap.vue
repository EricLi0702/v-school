<template>
  <baidu-map class="map" :center="{lng: 123.474976, lat: 41.695735}" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint">
    <bm-polygon :path="polygonPath" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2" :editing="true" @lineupdate="updatePolygonPath"/>
    <bm-marker :position="{lng: userlng, lat: userlat}" :dragging="true" animation="BMAP_ANIMATION_BOUNCE">
      <!-- <bm-label content="Tiananmen" :labelStyle="{color: 'red', fontSize : '24px'}" :offset="{width: -35, height: 30}"/> -->
    </bm-marker>
    <bm-control>
        <button class="addbtn" @click="storePolygon">{{ isAdding ? 'Adding' : 'Add' }}</button>
        <button class="addbtn" @click="checkFence">{{ isAdding ? 'Checking' : 'Check' }}</button>
    </bm-control>
    <!-- <bm-navigation anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-navigation>
    <bm-map-type :map-types="['BMAP_NORMAL_MAP', 'BMAP_HYBRID_MAP']" anchor="BMAP_ANCHOR_TOP_LEFT"></bm-map-type>
    <bm-overview-map anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :isOpen="true"></bm-overview-map>
    <bm-geolocation anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :showAddressBar="true" :autoLocation="true"></bm-geolocation>
    <bm-ground
      :bounds="{ne: {lng: 110, lat: 40}, sw:{lng: 0, lat: 0}}" imageURL="http://dafrok.github.io/vue-baidu-map/favicon.png">
    </bm-ground> -->
  </baidu-map>
</template>

<script>
export default {
    data () {
        return {
        polygonPath: [],
        isAdding:false,
        userlng:123.474976,
        userlat:41.695735,
        }
    },
    created(){
        console.log('userID',this.$store.state.user.id)
        axios.get('/api/fence',{
            params:{
                userId:this.$store.state.user.id
            }
        }).then(res=>{
            if(res.status == 200){
                this.polygonPath = JSON.parse(res.data[0].fence);
                console.log('test',this.polygonPath)
            }
        })
        
    },
    mounted(){
        
    },
    methods: {
        updatePolygonPath (e) {
        this.polygonPath = e.target.getPath()
        },
        addPolygonPoint (lng,lat) {
        this.polygonPath.push({lng: lng, lat: lat})
        console.log(this.polygonPath)
        },
        addPoint(e){
            console.log(e)
            const {lng,lat} = e.Ag;
            this.addPolygonPoint(lng,lat)
        },

        async storePolygon(){
            if(this.polygonPath.length == 0){
                return this.error('Please add polygon')
            }
            this.isAdding = true
            console.log(this.polygonPath);

            const res = await this.callApi('post','/api/fence',{
                fence:this.polygonPath,
                userId:this.$store.state.user.id
            });
            console.log(res)
            if(res.status == 201){
                this.success('saved')
            }else{
                if(res.status === 422){
                    for(let i in res.data.errors){
                        this.error(res.data.errors[i][0])
                    }
                }else{
                    this.swr()
                }
            }
            this.isAdding = false
        },
        checkFence(){
            console.log('test')
            const self = this
            setInterval(function(){self.fetchHole()},2000);
        },
        fetchHole(){
            console.log(this.userlng)
            console.log(this.userlat)
            var BMap = require('bmaplib').BMap;
            var BMapLib = require('bmaplib').BMapLib;
            var pts = []
            for(let i =0; i<this.polygonPath.length;i++){
                var pt = new BMap.Point(this.polygonPath[i].lng, this.polygonPath[i].lat);
                pts.push(pt)
            }
            console.log('@@@@@@@',pts);
            var ply = new BMap.Polygon(pts);
            this.userlat += 0.0001
            this.userlng += 0.0001
            var pt =new BMap.Point(this.userlng,this.userlat );
            var result = BMapLib.GeoUtils.isPointInPolygon(pt, ply);
            console.log('mounted test',result);
            if(result == false){
                alert('student is out of electronic fence')
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