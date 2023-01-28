<script setup>
import { reactive, onMounted, computed } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import BreezeValisationErrors from '@/Components/ValidationErrors.vue';
import ShowFlashMessages from '@/Components/ShowFlashMessages.vue';

onMounted(() => {
    MicroModal.init({
    disableScroll: true,
    awaitOpenAnimation: true,
    awaitCloseAnimation: true,
})
})

const form = reactive({
    name: null,
    memo: null,
    hours: 1,
    minutes: 30,
    seconds: 0
})

const setSeconds = computed(() => {
    return form.hours * 3600 + form.minutes * 60 + form.seconds * 1
})

const storeTimer = () => {
    const timerAttributes = {
        name: form.name, memo: form.memo, setSeconds: form.hours * 3600 + form.minutes * 60 + form.seconds * 1
    }
    MicroModal.close('modal-1')
    Inertia.post('/timers', timerAttributes)
}

</script>

<template>
    
    <div class="flex justify-start mr-auto">
    <BreezeValisationErrors />
    <ShowFlashMessages />
    </div>  
    <div class="modal micromodal-slide z-50" id="modal-1" area-hidden="true">
        <div class="modal__overlay" tabindex="-1">
            <div class="modal__container" role="dialog" aria-modal="true" aria-labelledby="modal-1-title">
                <header class="modal__header">
                    <h2 class="modal__title" id="modal-1-title">
                    </h2>
                    <button type="button" class="modal__close" aria-label="Close modal" data-micromodal-close></button>
                </header>
                <main class="modal__content" id="modal-1-content">
                    <section class="text-gray-600 body-font relative">
                        <div class="container px-5 py-12 mx-auto">
                            <div class="flex flex-col text-center w-full mb-6">
                                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">Create New Timer</h1>
                            </div>
                            
                            <form @submit.prevent="storeTimer">
                                <div class="lg:w-1/2 md:w-2/3 mx-auto">
                                    <div class="flex flex-wrap -m-2">
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="name" class="leading-7 text-sm text-gray-600">Name<span class="text-red-600 text-xs">（必須）</span></label>
                                                <input v-model="form.name" type="text" id="name" name="name" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label for="memo" class="leading-7 text-sm text-gray-600">Memo</label>
                                                <textarea v-model="form.memo" id="memo" name="memo" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-24 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <div class="relative">
                                                <label class="leading-7 text-sm text-gray-600">Set Time<span class="text-red-600 text-xs">（必須）</span></label>
                                                <p class="text-center leading-7 text-xl text-gray-600">{{ form.hours }}h {{ form.minutes }}m {{ form.seconds }}s</p>
                                                <p class="text-center leading-7 text-xl text-gray-600">{{ setSeconds }} EXP</p>
                                                <p class="leading-7 text-sm text-gray-600">Hours</p>
                                                <input v-model="form.hours" type="range" min="0" max="6" class="w-full">
                                                <p class="leading-7 text-sm text-gray-600">Minutes</p>
                                                <input v-model="form.minutes" type="range" min="0" max="59" class="w-full">
                                                <p class="leading-7 text-sm text-gray-600">Seconds</p>
                                                <input v-model="form.seconds" type="range" min="0" max="59" class="w-full">
                                            </div>
                                        </div>
                                        <div class="p-2 w-full">
                                            <button @click="storeTimer" type="button" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 mt-6 focus:outline-none hover:bg-indigo-600 rounded text-lg">Create</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </main>
                <footer class="modal__footer">
                </footer>
            </div>
        </div>
    </div>
    <div class="flex items-center ml-4 absolute sm:static">
        <button type="button" data-micromodal-trigger="modal-1" class="text-white bg-emerald-500 border-0 w-30 h-11 py-2 px-8 focus:outline-none hover:bg-emerald-600 rounded text-lg whitespace-nowrap relative -top-16 z-30 sm:static sm:top-0 sm:z-0" href='javascript:;' >+ New</button>
    </div>
</template>
