<template>
    <div class="overlay" v-if="loader_status">
        <div class="loader-wrapper">
            <div class="breeding-rhombus-spinner">
                <div class="rhombus child-1"></div>
                <div class="rhombus child-2"></div>
                <div class="rhombus child-3"></div>
                <div class="rhombus child-4"></div>
                <div class="rhombus child-5"></div>
                <div class="rhombus child-6"></div>
                <div class="rhombus child-7"></div>
                <div class="rhombus child-8"></div>
                <div class="rhombus big"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loader_status: false
        }
    },
    mounted(){
        window.emitter.on('changeLoaderStatus', (status) => {
            this.loader_status = status;
        });
    }
}
</script>

<style scoped>

.overlay {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(1, 1, 1, 0.8);
    z-index: 9000;
}

.loader-wrapper {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: center;
    transform: translate(-50%, -50%);
    top: 50%;
    left: 50%;
    height: 100%;
    z-index: 9999;
}

.breeding-rhombus-spinner {
    height: 65px;
    width: 65px;
    position: relative;
    transform: rotate(45deg);
}

.breeding-rhombus-spinner,
.breeding-rhombus-spinner * {
    box-sizing: border-box;
}

.breeding-rhombus-spinner .rhombus {
    height: calc(65px / 7.5);
    width: calc(65px / 7.5);
    animation-duration: 2000ms;
    top: calc(65px / 2.3077);
    left: calc(65px / 2.3077);
    background-color: #ff1d5e;
    position: absolute;
    animation-iteration-count: infinite;
}

.breeding-rhombus-spinner .rhombus:nth-child(2n+0) {
    margin-right: 0;
}

.breeding-rhombus-spinner .rhombus.child-1 {
    animation-name: breeding-rhombus-spinner-animation-child-1;
    animation-delay: calc(100ms * 1);
}

.breeding-rhombus-spinner .rhombus.child-2 {
    animation-name: breeding-rhombus-spinner-animation-child-2;
    animation-delay: calc(100ms * 2);
}

.breeding-rhombus-spinner .rhombus.child-3 {
    animation-name: breeding-rhombus-spinner-animation-child-3;
    animation-delay: calc(100ms * 3);
}

.breeding-rhombus-spinner .rhombus.child-4 {
    animation-name: breeding-rhombus-spinner-animation-child-4;
    animation-delay: calc(100ms * 4);
}

.breeding-rhombus-spinner .rhombus.child-5 {
    animation-name: breeding-rhombus-spinner-animation-child-5;
    animation-delay: calc(100ms * 5);
}

.breeding-rhombus-spinner .rhombus.child-6 {
    animation-name: breeding-rhombus-spinner-animation-child-6;
    animation-delay: calc(100ms * 6);
}

.breeding-rhombus-spinner .rhombus.child-7 {
    animation-name: breeding-rhombus-spinner-animation-child-7;
    animation-delay: calc(100ms * 7);
}

.breeding-rhombus-spinner .rhombus.child-8 {
    animation-name: breeding-rhombus-spinner-animation-child-8;
    animation-delay: calc(100ms * 8);
}

.breeding-rhombus-spinner .rhombus.big {
    height: calc(65px / 3);
    width: calc(65px / 3);
    animation-duration: 2000ms;
    top: calc(65px / 3);
    left: calc(65px / 3);
    background-color: #ff1d5e;
    animation: breeding-rhombus-spinner-animation-child-big 2s infinite;
    animation-delay: 0.5s;
}


@keyframes breeding-rhombus-spinner-animation-child-1 {
    50% {
        transform: translate(-325%, -325%);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-2 {
    50% {
        transform: translate(0, -325%);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-3 {
    50% {
        transform: translate(325%, -325%);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-4 {
    50% {
        transform: translate(325%, 0);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-5 {
    50% {
        transform: translate(325%, 325%);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-6 {
    50% {
        transform: translate(0, 325%);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-7 {
    50% {
        transform: translate(-325%, 325%);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-8 {
    50% {
        transform: translate(-325%, 0);
    }
}

@keyframes breeding-rhombus-spinner-animation-child-big {
    50% {
        transform: scale(0.5);
    }
}
</style>
