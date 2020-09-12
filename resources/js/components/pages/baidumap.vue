<template>
  <baidu-map class="map" center="沉阳" :zoom="15" :scroll-wheel-zoom="true" @click="addPoint">
    <bm-polygon :path="polygonPath" stroke-color="blue" :stroke-opacity="0.5" :stroke-weight="2" :editing="true" @lineupdate="updatePolygonPath"/>
    <bm-navigation anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-navigation>
    <bm-map-type :map-types="['BMAP_NORMAL_MAP', 'BMAP_HYBRID_MAP']" anchor="BMAP_ANCHOR_TOP_LEFT"></bm-map-type>
    <bm-overview-map anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :isOpen="true"></bm-overview-map>
    <bm-geolocation anchor="BMAP_ANCHOR_BOTTOM_RIGHT" :showAddressBar="true" :autoLocation="true"></bm-geolocation>
  </baidu-map>
</template>

<script>
export default {
  data () {
    return {
      polygonPath: [
        {lng: 116.412732, lat: 39.911707},
        {lng: 116.39455, lat: 39.910932},
        {lng: 116.403461, lat: 39.921336}
      ],
      boundPath:[],
    }
  },
  methods: {
    updatePolygonPath (e) {
      this.polygonPath = e.target.getPath()
    //   console.log(this.polygonPath)
    },
    addPolygonPoint () {
      this.polygonPath.push({lng: 116.404, lat: 39.915})
    },
    addPoint(e){
        // const {lng,lat} = e.target;
        console.log(e.target.dc)
        // this.boundPath.push({lng:lng,lat:lat});
        // console.log(this.boundPath)
    }
  }
}
</script>
<style>
.map {
  width: 100%;
  height: 80vh;
}
</style>