
<template>
    <transition name="modal-fade">
        <div class="modal-backdrop">
            <div class="modal"
                role="dialog"
                aria-labelledby="modalTitle"
                aria-describedby="modalDescription"
            >
                <header
                class="modal-header d-flex"
                id="modalTitle"
                >   
                    <a @click="$router.go(-1)"><Icon color="white" type="ios-arrow-back" class="back-to-icon"/></a>
                    <p  class="mx-auto cu-modal-title-p">
                    <slot name="title">Title</slot> </p>
                    <Icon class="btn-close" color="white" type="md-close" @click="close" aria-label="Close modal"/>
                </header>

                <section class="modal-body" id="modalDescription">
                    <slot name="body">
                        something here.
                    </slot>
                </section>

                <!-- <footer class="modal-footer">
                    <slot name="footer">
                        <Button  @click="close" >Close</Button>
                    </slot>
                </footer> -->
            </div>
        </div>
    </transition>
</template>

<script>
    import quesetionViewComponent from './chungHua/questionModal'
    export default {
        name: 'modal',
        components:{
            quesetionViewComponent
        },
        methods: {
            close() {
                this.$emit('close');
            },
        },
    };
</script>
<style>

    .modal-fade-enter,
    .modal-fade-leave-active {
        opacity: 0;
    }

    .modal-fade-enter-active,
    .modal-fade-leave-active {
        transition: opacity .5s ease
    }

    .modal-backdrop {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal {
        background: #FFFFFF;
        box-shadow: 2px 2px 20px 1px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
        width: 1010px;
        margin: 74px auto;
        height: 843px;
    }

    .modal-header {
        border-bottom: 1px solid #eeeeee;
        color: white;
        background: #2D8CF0;
        justify-content: space-between;
        display: flex;
        padding: 0!important;
        border-top-right-radius: 0!important;
        border-top-left-radius: 0!important;
    }

    .modal-footer {
        border-top: 1px solid #eeeeee;
        justify-content: flex-end;
        display: flex;
        padding: 7px!important;
    }

    .modal-body {
        padding:0!important;
        overflow: auto;
    }

    .btn-close {
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
    }

    .cu-modal-title-p{
        font-size: 1.5em;
        padding: 4px;
    }
</style>