<template>
    <div class="ch-message-body h-75 bg-white">
        <div class="ch-message-body-container p-4 overflow-auto h-100">
            <ChatMessage
                v-for="message in messages"
                :key="message.id"
                :message="message"
                :chatto="chatto"
                :chatfrom="chatfrom"
            />
        </div>
    </div>
</template>

<script>
import ChatMessage from './chatMessageComponent';
export default {
    components:{
        ChatMessage,
    },
    props:{
        chatto:{
            type:Number,
            required:true
        },
        chatfrom:{
            type:Number,
            required:true
        },
        messages:{
            type:Array,
            required:true
        }
    }

}
</script>