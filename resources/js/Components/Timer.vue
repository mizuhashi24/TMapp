<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';

import AppAccordion from '@/Components/AppAccordion.vue';

const props = defineProps({
    selectedTimer: Object,
    timers: Array,
})

const selectedTimer = reactive({
    timerId: props.selectedTimer.timerId,
    name: props.timers.find((v) => v.id === props.selectedTimer.timerId).name,
    memo: props.timers.find((v) => v.id === props.selectedTimer.timerId).memo,
    setSeconds: props.timers.find((v) => v.id === props.selectedTimer.timerId).set_seconds,
    totalSeconds: props.timers.find((v) => v.id === props.selectedTimer.timerId).total_seconds,
    usedSeconds: props.timers.find((v) => v.id === props.selectedTimer.timerId).used_seconds,
})

const timer = reactive({
    startTime: props.selectedTimer.startTime,
    passedTime: props.selectedTimer.passedTime,
    status: props.selectedTimer.status,
})

const showSeconds = ref(selectedTimer.setSeconds * 1000 - timer.passedTime)

let intervalId = 0
const startTimer = () => {
    timer.status = 1
    timer.startTime = Date.now()
    Inertia.patch('/timers/toggle', timer)
    intervalId = setInterval(() => {
        showSeconds.value -= 1000
    }, 1000);
}
const stopTimer = () => {
    clearInterval(intervalId)
    timer.status = 0
    timer.passedTime += ( Date.now() - timer.startTime)
    timer.startTime = 0
    Inertia.patch('/timers/toggle', timer)
}
const resetTimer = () => {
    clearInterval(intervalId)
    if (timer.startTime === 0 && timer.passedTime === 0) {
        //
    } else {
        timer.status = 0
        timer.passedTime = 0
        timer.startTime = 0
        Inertia.patch('/timers/toggle', timer)
        showSeconds.value = selectedTimer.setSeconds * 1000
    }
    
}

let blank = 0;
onMounted(() => {
    if (timer.status === 1) {
        intervalId = setInterval(() => {
            showSeconds.value -= 1000
        }, 1000);
        blank = Date.now() - timer.startTime
        showSeconds.value -= blank
        timer.passedTime += blank
    }
})

const isOpen = ref(false)
function toggleAccordion() {
    isOpen.value = !isOpen.value;
}

</script>

<template>
    <div class="flex flex-wrap flex-col items-center  w-full bg-gray-100 py-5 px-5 relative mb-4">
        <p class="mx-10">Selected Timer</p>
        <p class="text-3xl mx-10 my-3">{{ selectedTimer.name }}</p>
        <div class="flex justify-center w-4/5">
            <p class="text-7xl sm:text-8xl">{{ ('0' + String(Math.floor(showSeconds / 1000 / 3600))).slice(-2) }}:{{ ('0' + String(Math.floor(showSeconds /1000 % 3600 / 60))).slice(-2) }}:{{ ('0' + String(Math.floor(showSeconds /1000 % 3600 % 60))).slice(-2) }}</p>
        </div>
        <div class="flex flex-col md:flex-row justify-center w-3/4 mt-4 mb-1">
            <button @click="startTimer" v-if="timer.status === 0" type="button" class="text-white bg-gray-500 border-0 w-full md:w-40 h-16 py-2 px-8 focus:outline-none hover:bg-gray-600 active:bg-gray-700 rounded text-lg whitespace-nowrap" href='javascript:;' >START</button>
            <button @click="stopTimer" v-if="timer.status === 1" type="button" class="text-white bg-gray-500 border-0 w-full md:w-40 h-16 py-2 px-8 focus:outline-none hover:bg-gray-600 active:bg-gray-700 rounded text-lg whitespace-nowrap" href='javascript:;' >STOP</button>
            <button @click="resetTimer" type="button" class="text-white bg-gray-500 border-0 w-full md:w-40 h-16 py-2 px-8 mt-4 md:mt-0 md:ml-6 focus:outline-none hover:bg-gray-600 active:bg-gray-700 rounded text-lg whitespace-nowrap" href='javascript:;' >RESET</button>
            <button type="button" class="text-white bg-gray-500 border-0 w-full md:w-40 h-16 py-2 px-8 mt-4 md:mt-0 md:ml-6 focus:outline-none hover:bg-gray-600 active:bg-gray-700 rounded text-lg whitespace-nowrap" href='javascript:;' >SETTING</button>
        </div>
        <div class="">
            <button @click="toggleAccordion()" class="flex justify-center items-center  w-20 h-20 m-auto space-x-3">
                <svg class="w-6 transition-all duration-200 transform" :class="{'rotate-180': isOpen, 'rotate-0': !isOpen}" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 10">
                <path d="M15 1.2l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="flex flex-col justify-center transition-all duration-400 overflow-hidden" :class="{'max-h-96': isOpen, 'max-h-0': !isOpen,}">
                <p class="m-auto">Total Time</p>
                <p class="m-auto">{{ Math.floor(Math.floor(selectedTimer.totalSeconds / 1000) / 3600) }}h {{ Math.floor(Math.floor(selectedTimer.totalSeconds / 1000) / 3600 / 60) }}m {{ Math.floor(Math.floor(selectedTimer.totalSeconds / 1000) / 3600) % 60 }}s</p>
                <p class="m-auto">{{ selectedTimer.totalSeconds }} EXP</p>
            </div>
        </div>
        <div class="text-center relative z-10 w-full">
        </div>
    </div>

</template>