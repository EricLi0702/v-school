<template>
    <div class="container-fluid">
        <div v-if="albumData.length" class="row m-0 p-0">
            <div  v-for="(album, i) in albumData" :key="i"  class="col-12 col-sm-12 col-md-4 col-lg-3 col-xl-3 hv-13 p-1 m-0 album-items-list shine-hover column" v-viewer>
                <div class="h-100">
                    <figure class="h-100">
                        <img :src="album" class="h-100 w-100" @click="showImage" alt="">
                    </figure>
                </div>
            </div>
        </div>
        <div v-else-if="noImage" class="row">
            <notConnect></notConnect>
        </div>
        <div v-else class="row justify-content-center pt-3" >
            <img src="/img/icon/loadingIcon.gif" style="width: 30px; height:30px;" alt="">
        </div>
    </div>
</template>

<script>
import notConnect from '../../pages/notConnect'
//image viewer
import 'viewerjs/dist/viewer.css'
import Viewer from 'v-viewer'

export default {
    components:{
        notConnect,
    },
    data(){
        return{
            albumData: [],
            noImage : false,
        }
    },
    mounted(){
        this.getAlbum();
    },
    computed:{
        currentPath(){
            return this.$route
        }
    },

    methods:{
        async getAlbum(){
            let classId = this.currentPath.params.className;
            await axios.get('/api/grade/album', {params: { id: classId }})
            .then(res=>{
                if(res.data.albumData.length > 0){
                    this.albumData = res.data.albumData;
                }
                else{
                    this.noImage = true;
                }
            }) 
            .catch(err=>{
                console.log(err.response);
            })
        },

        showImage(){
            const viewer = this.$el.getElementsByClassName('album-items-list').$viewer
            viewer.show()
        },
    }
}
</script>