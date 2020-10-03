<template>
    <div class="cu-col-30 chat-user-list h-100 p-0">
        <div class="h-100">
            <div class="chat-search-user pt-2 d-flex px-2">
            <Input class="search-user-bar" search enter-button placeholder="Enter something..." />
            <Icon class="pl-1" size="31" color="#2D8CF0"  type="md-add-circle" />
            </div>

            <div class="chat-contact-list mt-3">
                <ul class="list-group list-group-flush">
                    <li 
                        class="list-group-item d-flex"
                        v-for="user in users"
                        v-bind:key="user.id"
                        :class="{'selected':ChatWith === user.id}"
                        @click="updatechatwith(user.id)"
                    >
                        <div class="ch-user-avatar">
                            <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
                        </div>
                        <div class="ch-user-info">
                            <p class="ch-user-info-name ellipsis p-3">
                            {{user.name}}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        currentUser:{
            type: Object,
            required: true,
        },
        ChatWith: {
            type: Number,
            required: false,
        },
    },

    data(){
        return{
            users:[],
        }
    },

    async created(){
        const res = await this.callApi('get','api/chat/userList');
        if(res.status == 200){
            this.users = res.data;
            this.users = this.users.users.filter((user) => user.id !== this.currentUser.id);
        }
    },

    // computed: {
    //     userswithoutsigninuser() {
    //         console.log("beforeFilteringUser", this.users);
    //         return this.users.filter((user) => user.id !== this.currentUser.id);
    //     },
    // },

    methods: {
        updatechatwith(userid) {
            this.$emit("updatechatwith", userid);
        },
    },

}
</script>