<template>
    <div>
        <img :src="imgUrl" alt="" style="width:100px; height: 100px;">
    </div>    
</template>
<script>
    export default {
        props: {
            imgUrl: String
        }
    };
</script>