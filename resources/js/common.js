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
        }
    }

}