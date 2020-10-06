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
        },
        TimeView(str){
            // let date =  new Date(str)
            let date = new Date(str);
            let mnth = ("0" + (date.getMonth() + 1)).slice(-2);
            let day = ("0" + date.getDate()).slice(-2);
            let hour = ("0" + date.getHours()).slice(-2);
            let min = ("0" + date.getMinutes()).slice(-2);
            let time = date.getFullYear() + '-' + mnth + '-' + day + ' ' + hour + ':' + min;
            return time
            // date = date.getTime()
            // return date
        },

        TimeViewHMS(str){
            // let date =  new Date(str)
            let date = new Date(str);
            let hour = ("0" + date.getHours()).slice(-2);
            let min = ("0" + date.getMinutes()).slice(-2);
            let sec = ("0" + date.getSeconds()).slice(-2);
            let time = hour + ':' + min + ':' + sec;
            return time
            // date = date.getTime()
            // return date
        },

        TimeViewDHM(str){
            let date = new Date(str);
            let mnth = ("0" + (date.getMonth() + 1)).slice(-2);
            let day = ("0" + date.getDate()).slice(-2);
            let hour = ("0" + date.getHours()).slice(-2);
            let min = ("0" + date.getMinutes()).slice(-2);
            let time = mnth + '-' + day + ' ' + hour + ':' + min;
            return time
        }
    },
            
    computed:{
        ...mapGetters({
            'userPermission':'getUserPermission'
        }),

        isReadPermitted(){
            // //console.log('commonjs user permission',this.userPermission);
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