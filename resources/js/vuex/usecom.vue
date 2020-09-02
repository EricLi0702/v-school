<template>
    <div class="content">
        <div class="container-fluid">
            <h1>Use Com</h1>
            Current state of counter is : {{Counter}}
        </div>
        <div>
            <comA></comA>
        </div>
        <div>
            <comB></comB>
        </div>
        <div>
            <comC></comC>
        </div>
        <Button type="info" @click="stateCounter">counter</Button>
    </div>
</template>

<script>
import comA from './comA';
import comB from './comB';
import comC from './comC';
import {mapGetters,mapActions} from 'vuex';
export default {
    components:{
        comA,
        comB,
        comC
    },
    methods:{
        ...mapActions([
            'changeCounterAction'
        ])
    },
    computed : {
        ...mapGetters({
            'Counter' : 'getCounter'
        })
    },

    methods:{
        stateCounter(){
            // this.$store.commit('changeCounter',1);
            this.$store.dispatch('changeCounterAction',1);
        },
        runSomethingWhenCounterChainging(){
            console.log('I am running method when counter change ')
        }
    },

    watch:{
        Counter(value){
            console.log('counter is changing',value);
            this.runSomethingWhenCounterChainging();
        }
    }

}
</script>