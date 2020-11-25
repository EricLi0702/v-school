<template>
    <div>
        {{viewData}}
    </div>
</template>

<script>
export default {
    props:["viewData"],
    created(){
        console.log(this.viewData)
    }
}
</script>

<style>

</style>