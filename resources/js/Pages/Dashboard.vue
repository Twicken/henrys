<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CandidatesListing from '@/Components/CandidatesListing.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Candidate Listing" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Candidate Listing</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100"></div>
                    <!-- Display success message if new staff was registered -->
                    <div v-if="$page.props.flash.success" class="p-6 text-green-900 dark:text-green-500">
                        {{ $page.props.flash.success }}
                    </div>
                    <candidates-listing :candidates="$page.props.candidates" />

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
