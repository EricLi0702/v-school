<template>
    <div>
        <div class="es-alphabet">
                    <div>
                        <label :title="key"  v-for="(value, key) in grouped" :key="key"><a :href="`#${key}`">{{key}}</a></label>
                        <!-- <a :href="`#${key}`"></a> -->
                    </div>
        </div>
        <perfect-scrollbar>
            <div class="p-3">
                
                <div class="p-scroll">
                    
                    <div class="mb-2"  v-for="(menu,i) in menuLists.member" :key="i">

                        <Row type="flex" justify="space-between" class="code-row-bg" v-if="i == 0">
                            <Col span="5" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                                <div>
                                    <img :src="subMenu.imgurl" alt="">
                                    <span>{{subMenu.label}}</span>
                                </div>
                                <Modal
                                    footer-hide
                                    draggable
                                    v-model="subMenu.active"
                                    :title="subMenu.label"
                                    :styles="{top:'75px',left:'-90px'}"
                                >
                                    <div class="es-app-detail-header">
                                        <Input prefix="ios-search" placeholder="搜索"/>
                                        <div class="operate-item">
                                            <Tooltip content="Bottom Center text" placement="bottom">
                                                <img src="/img/icon/ico_report.png" alt="">
                                            </Tooltip>

                                            <Tooltip content="Bottom Center text" placement="bottom">
                                                <img src="/img/icon/ico_app_set.png" alt="">
                                            </Tooltip>

                                        </div>
                                    </div>
                                    
                                </Modal>
                            </Col>
                        </Row>
                        <div v-else-if="i < 6" class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                            <div class="es-item-left">
                                <img :src="subMenu.imgurl" alt="">
                                <div class="es-item-info">
                                    <div class="title">高一年级</div>
                                    <div class="main">班级8,老师24,学生0</div>
                                </div>
                            </div>
                            <div class="es-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                        <div v-else class="es-item" v-for="(subMenu,j) in menu.subMenuLists" :key="j">
                            <div class="es-item-left">
                                <img :src="subMenu.imgurl" alt="">
                                <div class="es-item-info">
                                    <div class="title">高一年级</div>
                                    <div class="main">班级8,老师24,学生0</div>
                                </div>
                            </div>
                            <div class="es-item-right">
                                <Icon type="ios-arrow-forward" />
                            </div>
                        </div>
                    </div>
                    <div v-for="(value, key) in grouped" :key="key">
                        <div class="category-title">
                            <span :id="key">{{ key }}</span>
                        </div>
                        <div v-for="(contactName,i) in value" :key="i">
                            <div v-for="(contact,j) in contacts" :key="j">
                                <div v-if="contact.name == contactName.name">
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
        </perfect-scrollbar>
    </div>
</template>

<script>
import lodash from 'lodash';
export default {
    props:['menuLists'],
    data(){
        return{
            contacts:[],
            contactName:[]
        }
    },
    mounted(){
        //console.log('contact component imported');
        //console.log(this.contacts)
        //console.log(this.contactName)
        //console.log(this.menuLists)
    },
    async created(){
        
        axios.get('api/contact').then(res=>{
            if(res.status == 200){
                //console.log('axios get',res.data)
                this.contacts = res.data.user;
                this.contactsName = res.data.userName;
            }
        })
    },
    computed:{
        grouped(){
            return lodash.groupBy(this.contactsName,(item)=>{
                return item.name.charAt(0)
            })
        }
    },
}
</script>