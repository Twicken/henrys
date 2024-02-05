<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/inertia-vue3';
import { InertiaLink } from '@inertiajs/inertia-vue3';
import moment from 'moment';
</script>

<script>
export default {
    data() {

        return {
            refereeDetails: this.$page.props.refereedetails, // Store referee details associated with the candidate
            candidate: this.$page.props.candidate, // Store candidate details
            
        };
    },
    methods: {
        formatDate(timestamp) {
    if (!timestamp) {
        return 'N/A'; 
    }
  
    const date = moment(timestamp);
    if (!date.isValid()) {
        return 'N/A'; 
    }
  
    return date.format('DD/MM/YYYY');
},
        goBack() {
            const url = route('dashboard');
            this.$inertia.visit(url);

        },
    },
    created() {
    },
};
</script>

<template>
    <Head title="Candidate Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Candidate Details</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container mx-auto px-4 sm:px-8">
                        <div class="py-8">
                            <div class="shadow overflow-hidden rounded border-b border-gray-200">
                                <!-- Candidate Details -->
                                <div class="p-4">
                                    <h2 class="text-xl font-semibold">Candidate Details</h2>
                                    <div class="mt-4">
                                        <p><strong>First Name:</strong> {{ candidate.first_name }}</p>
                                        <p><strong>Last Name:</strong> {{ candidate.last_name }}</p>
                                        <p><strong>Email Address:</strong> {{ candidate.email }}</p>
                                    </div>
                                </div>

                                <!-- Referee Details Table -->
                                <div class="p-4">
                                    <h2 class="text-xl font-semibold">Referee Details</h2>
                                    <table v-if="refereeDetails && refereeDetails.length > 0" class="min-w-full bg-white mt-4">
                                        <thead class="bg-gray-800 text-white">
                                            <tr>
                                                <!-- ...other headers... -->
                                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Referee Name
                                                </th>
                                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Referee
                                                    Email</th>
                                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Phone Number
                                                </th>
                                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Check Type /
                                                    Status</th>

                                                <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Completion
                                                    Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700">
                                            <tr v-for="referee in refereeDetails" :key="referee.num">
                                                <td class="text-left py-3 px-4">{{ referee.name }}</td>
                                                <td class="text-left py-3 px-4">{{ referee.email }}</td>
                                                <td class="text-left py-3 px-4">{{ referee.phone }}</td>
                                                <td class="text-left py-3 px-4">
                                                    <ul>
                                                        <li v-for="check in referee.checks" :key="`check-${check.num}`">
                                                            {{ check.check_type_name }}: {{ check.status }}
                                                        </li>
                                                    </ul>
                                                </td>

                                                <td class="text-left py-3 px-4">
                                                    <ul>
                                                        <li v-for="check in referee.checks" :key="`check-${check.num}`">
                                                            {{ formatDate(check.completion_date) || 'N/A' }}
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div v-else>
                                        <p>No referee data</p>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                        @click="goBack">
                                        Back
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
        </div>
    </div>
</AuthenticatedLayout></template>
