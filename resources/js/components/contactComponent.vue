<template>
    <div>
        <div class="es-alphabet">
                    <div>
                        <label :title="key"  v-for="(value, key) in grouped" :key="key"><a :href="`#${key}`">{{key}}</a></label>
                        <!-- <a :href="`#${key}`"></a> -->
                    </div>
        </div>
        
            <div class="p-3">
                <div class="p-scroll">
                    <div v-for="(value, key) in grouped" :key="key">
                        <div class="category-title">
                            <span :id="key">{{ key }}</span>
                        </div>
                        <div v-for="(contactsName,i) in value" :key="i">
                            <div v-for="(contact,j) in contacts" :key="j">
                                <div v-if="contact.name == contactsName.name">
                                    <div class="es-item">
                                        <div class="es-item-left">
                                            <Avatar src="https://i.loli.net/2017/08/21/599a521472424.jpg" v-if="contact.userAvatar" />
                                            <Avatar icon="ios-person" v-else/>
                                            <div class="es-item-info">
                                                <div class="title">{{contact.name}}</div>
                                                <div class="main">{{contact.phoneNumber}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
    </div>
</template>

<script>
import lodash from 'lodash';
export default {
    props:['menuLists'],
    data(){
        return{
            contacts:[],
            contactsName:[]
        }
    },
    mounted(){
        
    },
    async created(){
        let payload = {};
        if ('className' in this.currentPath.params){
            payload = {
                schoolId : parseInt(this.currentPath.params.schoolName),
                classId : parseInt(this.currentPath.params.className),
            }
        }
        if(!('className' in this.currentPath.params)){
            payload = {
                schoolId : parseInt(this.currentPath.params.schoolName),
                classId : null,
            }
        }
        this.isloadingContact = true;
        await axios.get('/api/contact',{params:payload})
        .then(res=>{
            this.contacts = res.data.user;
            this.contactsName = res.data.userName;
        })
        .catch(err=>{
            console.log(err.response);
        })
        this.isloadingContact = false;
    },
    computed:{
        grouped(){
            return lodash.groupBy(this.contactsName,(item)=>{
                return item.name.charAt(0)
            })
        },
        currentPath(){
            return this.$route
        }
    },
}
</script>