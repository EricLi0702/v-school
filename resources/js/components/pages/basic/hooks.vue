<template>
    <div>
        <h1 ref="myref">
            hooks tutorial
        </h1>
        <h3>
            my name is {{name}}
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
        console.log('$$$$$$$$$',this.name)
    },

    created(){
        console.log('@@@@@@@@@@@@@',this.name)
        console.log('##############',this.$refs.myref)
    },

    mounted(){
        console.log('%%%%%%',this.$refs.myref)

    }
}
</script>