<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="Welcome" />

    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0"
    >
        

        <div class="w-1/2 max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-8 bg-white overflow-hidden shadow sm:rounded-lg">
                <div class="">
                    <div class="flex items-center justify-center p-6">
                        <div class="flex items-center">
                            <section class="text-gray-600 body-font relative">
                                <div class="container px-5 py-24 mx-auto">
                                    <div class="flex flex-col text-center w-full mb-6">
                                        <img src="/images/logo_rect_M.png" class="h-full bg-white">
                                        <p class="lg:w-2/3 mx-auto leading-relaxed text-base">テストデプロイ中<br>(タイマーのみ実装)</p>
                                    </div>

                                    <div v-if="canLogin" class="flex flex-col">
                                        <Link
                                            v-if="$page.props.auth.user"
                                            :href="route('dashboard')"
                                            class="w-full text-center mx-auto mb-6 text-white bg-gray-500 border-0 py-2 px-8 focus:outline-none hover:bg-gray-600 rounded text-lg"
                                            >Dashboard</Link
                                        >
                                        <template v-else>
                                            <Link :href="route('login')" class="w-full text-center mx-auto mb-6 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">ログイン</Link>

                                            <Link
                                                v-if="canRegister"
                                                :href="route('register')"
                                                class="w-full text-center mx-auto text-white bg-emerald-500 border-0 py-2 px-8 focus:outline-none hover:bg-emerald-600 rounded text-lg"
                                                >新規登録</Link
                                            >
                                        </template>
                                    </div>

                                    <!-- <button class="w-full text-center mx-auto mb-6 text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">ログイン</button>
                                    <button class="w-full text-center mx-auto text-white bg-emerald-500 border-0 py-2 px-8 focus:outline-none hover:bg-emerald-600 rounded text-lg">新規登録
                                    </button> -->
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                <div class="ml-4 text-center text-sm text-gray-500 sm:text-right sm:ml-0">
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </div>
            </div>
        </div>
    </div>
</template>