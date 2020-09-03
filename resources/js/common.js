import {mapGetters,mapActions} from 'vuex';
export default{
    data(){
        return{

        }
    },
    methods:{
        async callApi(method,url,dataObj){
            try {
                return await axios({
                    method: method,
                    url: url,
                    data: dataObj
                })
            } catch (e) {
                return e.response
            }
        },
        info (nodesc,notitle="Hey") {
            this.$Notice.info({
                title: notitle,
                desc: nodesc
            });
        },
        success (nodesc,notitle="Great!") {
            this.$Notice.success({
                title: notitle,
                desc: nodesc
            });
        },
        warning (nodesc,notitle="Oops!") {
            this.$Notice.warning({
                title: notitle,
                desc: nodesc
            });
        },
        error (nodesc,notitle="Hey") {
            this.$Notice.error({
                title: notitle,
                desc: nodesc
            });
        },
        swr (nodesc="Something went wrong! Please try again.",notitle="Oops!") {
            this.$Notice.error({
                title: notitle,
                desc: nodesc
            });
        },
        checkUserPermission(key){
            if(!this.userPermission) return true;
            let isPermitted = false;
            for(let d of this.userPermission){
                if(this.$route.name == d.name){
                    if(d[key]){
                        isPermitted = true;
                    }
                    break;
                }
            }
            return isPermitted;
        }
    },
    computed:{
        ...mapGetters({
            'userPermission':'getUserPermission'
        }),

        isReadPermitted(){
            // console.log('commonjs user permission',this.userPermission);
            return this.checkUserPermission('read');
        },
        isWritePermitted(){
            return this.checkUserPermission('write');
        },
        isUpdatePermitted(){
            return this.checkUserPermission('update');
        },
        isDeletePermitted(){
            return this.checkUserPermission('delete');
        },
    }

}