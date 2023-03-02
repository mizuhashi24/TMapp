<script setup>
import { ref, reactive, onMounted, onUnmounted, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import timerUpdate from '@/Components/Micromodal/TimerUpdate.vue';

// prop関連
const props = defineProps({
    selectedTimer: Object,
    timers: Array,
})
// 選択タイマーをリアクティブなオブジェクトへ代入
const selectedTimer = reactive({
    timerId: props.selectedTimer.timerId,
    name: props.timers.find((v) => v.id === props.selectedTimer.timerId).name,
    memo: props.timers.find((v) => v.id === props.selectedTimer.timerId).memo,
    setSeconds: props.timers.find((v) => v.id === props.selectedTimer.timerId).set_seconds,
    totalSeconds: props.timers.find((v) => v.id === props.selectedTimer.timerId).total_seconds,
})
// 選択タイマーの内容がprop更新で反映されるようにcomputedする
selectedTimer.name = computed(() => { return props.timers.find((v) => v.id === props.selectedTimer.timerId).name })
selectedTimer.memo = computed(() => { return props.timers.find((v) => v.id === props.selectedTimer.timerId).memo })
selectedTimer.setSeconds = computed(() => { return props.timers.find((v) => v.id === props.selectedTimer.timerId).set_seconds })
selectedTimer.totalSeconds = computed(() => { return props.timers.find((v) => v.id === props.selectedTimer.timerId).total_seconds })
// 選択タイマー自体の現在情報のオブジェクト
const timer = reactive({
    startTime: props.selectedTimer.startTime,
    passedTime: props.selectedTimer.passedTime,
    status: props.selectedTimer.status,
})
// 親→子コンポーネントの参照
// const child = ref(null);

// emit関連
// 設定更新時に走るメソッド
const updateShowSeconds = seconds => { showSeconds.value = seconds }

// 表示されるタイマー時間のリアクティブ変数
const showSeconds = ref(selectedTimer.setSeconds * 1000 - timer.passedTime)

// ボタン用の関数
let intervalId = 0  // setInterval用の変数
const startTimer = () => {
    timer.status = 1
    // child.value.toggleSettingButton();
    timer.startTime = Date.now()
    // startTime, statusをcontrollerへpatch送信
    Inertia.patch('/timers/toggle', timer)
    // スタートボタンを押したあと、表示時間が1秒ずつ減るよう処理
    intervalId = setInterval(() => {
        showSeconds.value -= 1000
        
        if (selectedTimer.setSeconds * 1000 <= (timer.passedTime + (Date.now() - timer.startTime))) {
            clearInterval(intervalId)
            toggleTimerCountColor();
            showSeconds.value = 0
            intervalId = setInterval(() => {
                soundEffect.play();
            }, 0);
            // 過ぎた時間(passedTime)がsetTimeを超えていたら
            // setTimeをDBへ入れる処理をコントローラでかく
        }
    }, 1000);
}
const stopTimer = () => {
    if (selectedTimer.setSeconds * 1000 <= (timer.passedTime + (Date.now() - timer.startTime))) {
        // 表示時間が0秒になっているときの処理
        resetTimer();
    } else {
        clearInterval(intervalId)
        timer.status = 0
        timer.passedTime += ( Date.now() - timer.startTime)
        timer.startTime = 0
        // status, passedTime, startTimeをcontrollerに送信
        Inertia.patch('/timers/toggle', timer)
    }
}
const resetTimer = () => {
    clearInterval(intervalId)
    // タイマーのテキスト色が変更されている場合、元に戻す
    if (timerCountColor.value === 'text-red-500') timerCountColor.value = '';
    if (timer.startTime === 0 && timer.passedTime === 0) {
        // タイマーが初期状態の場合、リセットボタンを無効化
    } else {
        timer.status = 0
        if (timer.startTime === 0) {
            timer.passedTime = 0
        } else {
            timer.passedTime += ( Date.now() - timer.startTime)
        }
        timer.startTime = 0
        // status, passedTime, startTimeをcontrollerに送信
        Inertia.patch('/timers/reset', timer)
        // 表示タイマーと経過時間のリセット
        showSeconds.value = selectedTimer.setSeconds * 1000
        timer.passedTime = 0
    }
    
}

// アコーディオンの開閉制御
const isOpen = ref(false)
function toggleAccordion() {
    isOpen.value = !isOpen.value;
}

// タイマーのテキスト色管理
const timerCountColor = ref('');
const toggleTimerCountColor = () => {
    if (timerCountColor.value === '') {
        timerCountColor.value = 'text-red-500';
    } else {
        timerCountColor.value = '';
    }
}

// 効果音
let soundEffect = new Audio();
soundEffect.src = "/sounds/warning1.mp3";

// ボタン非活性処理
let isActiveButton = true;



// onMounted, onUnmounted
let blank = 0;
onMounted(() => {
    if (timer.status === 1 && selectedTimer.setSeconds * 1000 <= (timer.passedTime + (Date.now() - timer.startTime))) {
        showSeconds.value = 0
        toggleTimerCountColor();
        intervalId = setInterval(() => {
                soundEffect.play();
        }, 0);
    } else if (timer.status === 1) {
        intervalId = setInterval(() => {
            showSeconds.value -= 1000
            if (selectedTimer.setSeconds * 1000 <= (timer.passedTime + (Date.now() - timer.startTime))) {
            clearInterval(intervalId)
            showSeconds.value = 0
            toggleTimerCountColor();
            intervalId = setInterval(() => {
                soundEffect.play();
            }, 0);
            // 過ぎた時間(passedTime)がsetTimeを超えていたら
            // setTimeをDBへ入れる処理をコントローラでかく
            }
        }, 1000);
        blank = Date.now() - timer.startTime
        showSeconds.value -= blank
        timer.passedTime += blank
    }
})

onUnmounted(() => {
    clearInterval(intervalId)
})




</script>

<template>
    <div class="flex flex-wrap flex-col items-center  w-full bg-gray-100 py-5 px-5 relative mb-4">
        <p class="mx-10">Timer Name</p>
        <p class="text-3xl mx-10 my-3">{{ selectedTimer.name }}</p>
        <div class="flex justify-center w-4/5">
            <p class="text-7xl sm:text-8xl">
                <span :class="[timerCountColor]">{{ ('0' + String(Math.floor(showSeconds / 1000 / 3600))).slice(-2) }}:{{ ('0' + String(Math.floor(showSeconds /1000 % 3600 / 60))).slice(-2) }}:{{ ('0' + String(Math.floor(showSeconds /1000 % 3600 % 60))).slice(-2) }}</span>
            </p>
        </div>
        <div class="flex flex-col md:flex-row justify-center w-3/4 mt-4 mb-1">
            <button @click="startTimer" v-if="timer.status === 0" type="button" class="text-white bg-gray-500 border-0 w-full md:w-40 h-16 py-2 px-8 focus:outline-none hover:bg-gray-600 active:bg-gray-700 rounded text-lg whitespace-nowrap" href='javascript:;' >START</button>
            <button @click="stopTimer" v-if="timer.status === 1" type="button" class="text-white bg-gray-500 border-0 w-full md:w-40 h-16 py-2 px-8 focus:outline-none hover:bg-gray-600 active:bg-gray-700 rounded text-lg whitespace-nowrap" href='javascript:;' >STOP</button>
            <button @click="resetTimer" type="button" class="text-white bg-gray-500 border-0 w-full md:w-40 h-16 py-2 px-8 mt-4 md:mt-0 md:ml-6 focus:outline-none hover:bg-gray-600 active:bg-gray-700 rounded text-lg whitespace-nowrap" href='javascript:;' >RESET</button>
            <timerUpdate :timer="selectedTimer" :status="timer.status" @update:showSeconds="updateShowSeconds" />
        </div>
        <div class="w-full">
            <button @click="toggleAccordion()" class="flex justify-center items-center  w-20 h-20 m-auto space-x-3">
                <svg class="w-6 transition-all duration-200 transform" :class="{'rotate-180': isOpen, 'rotate-0': !isOpen}" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 10">
                <path d="M15 1.2l-7 7-7-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <div class="flex flex-col justify-center transition-all duration-400 overflow-hidden" :class="{'max-h-96': isOpen, 'max-h-0': !isOpen,}">
                <p class="m-auto">Total Time</p>
                <p class="m-auto text-xl">{{ Math.floor(Math.floor(selectedTimer.totalSeconds / 1000) / 3600) }}h {{ Math.floor(Math.floor(selectedTimer.totalSeconds / 1000) % 3600 / 60) }}m {{ Math.floor(selectedTimer.totalSeconds / 1000) % 60 % 60 }}s</p>
                <p class="m-auto text-xl">{{ selectedTimer.totalSeconds }} EXP</p>
                <p class="m-auto mt-5">Memo</p>
                <p class="m-auto w-2/3 text-center text-xl">{{ selectedTimer.memo }}</p>
            </div>
        </div>
        <div class="text-center relative z-10 w-full">
        </div>
    </div>

</template>