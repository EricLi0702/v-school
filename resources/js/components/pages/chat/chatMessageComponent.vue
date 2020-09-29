<template>
    <div>
        <div v-if="chatfrom === message.from.id" class="d-flex justify-content-end mb-4">
            <div class="msg-container-send">
                <p class="messagebox_p">
                    {{message.text}}
                </p>
            </div>
            <div class="ch-user-avatar-sender">
                <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
            </div>
        </div>

        <div v-else class="d-flex justify-content-start mb-4">
            <div class="ch-user-avatar">
                <img class="rounded-circle border-primary" src="/img/coverImage/bio_image.jpg" alt="">
            </div>
            <div class="msg-container">
            <p class="messagebox_p">
                {{message.text}}
            </p>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    props: {
        message: {
            type: Object,
            required: true,
        },
        chatto: {
            type: Number,
            required: true,
        },
        chatfrom: {
            type: Number,
            required: true,
        },
    },
}
</script>