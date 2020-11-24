<template>
    <div>
        <div class="es-item" @click="navigateToSetDefault">
            <div class="es-item-left">
                Default
            </div>
            <div class="es-item-right">
                <Icon type="ios-arrow-forward" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    
    methods:{
        navigateToSetDefault(){
            console.log(`${this.$route.path}?questionType=${this.currentPath.query.questionType}`);
            // this.$router.push({path:`${this.$route.path}?questionType=${this.currentPath.query.questionType}`});
        }
    }
}
</script>