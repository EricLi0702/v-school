<template>
    <div>
        <img v-if="rounded == false" :src="imgUrl" alt="" style="width:100px; height: 100px;">
        <img v-else-if="rounded == true" :src="imgUrl" alt="" style="width:100px; height: 100px; border-radius: 50%;">
    </div>    
</template>
<script>
    export default {
        props: {
            imgUrl: String,
            rounded : Boolean
        },
    };
</script>