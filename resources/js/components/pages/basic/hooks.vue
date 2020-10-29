<template>
    <div>
        <h1 ref="myref">
            钩子教程
        </h1>
        <h3>
            我的名字是 {{name}}
        </h3>
    </div>
</template>

<script>
export default {
    data(){
        return{
            name:'hooks'
        }
    },

    beforeCreate(){
        //console.log('$$$$$$$$$',this.name)
    },

    created(){
        //console.log('@@@@@@@@@@@@@',this.name)
        //console.log('##############',this.$refs.myref)
    },

    mounted(){
        //console.log('%%%%%%',this.$refs.myref)

    }
}
</script>