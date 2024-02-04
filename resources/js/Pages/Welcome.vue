<script setup>
import { Head, Link } from '@inertiajs/vue3';


defineProps({
    canLogin: {
        type: Boolean,
    },
});

</script>

<template>
    <Head title="Henry's Outdoor Shop - Kiosk" />

    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-r from-green-400 to-blue-500 dark:bg-gradient-to-r dark:from-green-900 dark:to-blue-900">
        <div class="max-w-md w-full space-y-8">
            <div class="mx-auto h-25 w-25">
                <!-- temp logo -->
                <img src="/images/trees.svg" alt="Tree Logo" class="h-full w-full">
            </div>
            <div>
                <h2 class="mt-6 text-center text-4xl font-extrabold text-gray-900 dark:text-white">
                    Welcome to Henry's Outdoor Shop - Kiosk
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-200">
                    Log in to continue
                </p>
            </div>
            <div class="rounded bg-white dark:bg-gray-800 p-8 shadow-lg">
                <a :href="route('login')"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Login
            </a>
                <!-- <Link v-if="canRegister" :href="route('register')"
                    class="mt-4 group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Register
                </Link> -->
            </div>
        </div>
    </div>
</template>

