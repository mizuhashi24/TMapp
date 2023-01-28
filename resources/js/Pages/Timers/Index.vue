<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/inertia-vue3';
import { ref, reactive, onMounted, computed } from 'vue';

import TimerCreate from '@/Components/Micromodal/TimerCreate.vue';
import Timer from '@/Components/Timer.vue';

const props = defineProps({
    timers: Array,
    selectedTimer: Array,
});

const selectedTimer = reactive({
    timerId: props.selectedTimer[0].timer_id,
    startTime: props.selectedTimer[0].start_time,
    passedTime: props.selectedTimer[0].passed_time,
    status: props.selectedTimer[0].status,
});



</script>

<template>
    <Head title="Timers" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Timers</h2>
        </template>
        
        <div class="pt-0 pb-12 sm:py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    
                    <section class="text-gray-600 body-font">
                        <div class="container px-5 pb-10 mx-auto flex flex-wrap">
                            <div class="flex justify-end lg:w-2/3 mx-auto py-2 my-2 w-full">
                                <!-- <button @click="toggleCreateTimerStatus" class="text-white bg-emerald-500 border-0 py-2 px-8 focus:outline-none hover:bg-emerald-600 rounded text-lg">+ New</button> -->
                                <TimerCreate />
                            </div>
                            <Micromodal  />
                            <div class="lg:w-2/3 mx-auto">
                                <Timer :selectedTimer="selectedTimer" :timers="props.timers" />
                                <div class="flex flex-wrap -mx-2">
                                    <div class="px-2 w-1/2">
                                        <div class="flex flex-wrap w-full bg-gray-100 sm:py-24 py-16 sm:px-10 px-6 relative">
                                            <img alt="gallery" class="w-full object-cover h-full object-center block opacity-25 absolute inset-0" src="https://dummyimage.com/542x460">
                                            <div class="text-center relative z-10 w-full">
                                                <h2 class="text-xl text-gray-900 font-medium title-font mb-2">Shooting Stars</h2>
                                                <p class="leading-relaxed">Skateboard +1 mustache fixie paleo lumbersexual.</p>
                                                <a class="mt-3 text-indigo-500 inline-flex items-center">Learn More
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-2 w-1/2">
                                        <div class="flex flex-wrap w-full bg-gray-100 sm:py-24 py-16 sm:px-10 px-6 relative">
                                            <img alt="gallery" class="w-full object-cover h-full object-center block opacity-25 absolute inset-0" src="https://dummyimage.com/542x420">
                                            <div class="text-center relative z-10 w-full">
                                                <h2 class="text-xl text-gray-900 font-medium title-font mb-2">Shooting Stars</h2>
                                                <p class="leading-relaxed">Skateboard +1 mustache fixie paleo lumbersexual.</p>
                                                <a class="mt-3 text-indigo-500 inline-flex items-center">Learn More
                                                    <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                                                        <path d="M5 12h14M12 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">You're logged in!</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>