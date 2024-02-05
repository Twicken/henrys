<template>
  <div class="container mx-auto px-4 sm:px-8">
    <div class="py-8">
      <div class="shadow overflow-x-auto rounded border-b border-gray-200"> <!-- Update the overflow property -->
        <table class="min-w-full bg-white dark:bg-gray-800">
          <thead class="dark:bg-blue-900 bg-gray-800 border-b dark:border-blue-900 border-gray-700 dark:text-white text-gray-200">
            <tr>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">First Name</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Last Name</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Email Address</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Phone Number</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Response Date</th>
              <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700  text-gray-800 dark:text-gray-200">
            <tr v-for="candidate in displayedCandidates" :key="candidate.num" class="hover:bg-gray-100 hover:dark:bg-gray-600 ">
              <td class="text-left py-3 px-4">{{ candidate.first_name }}</td>
              <td class="text-left py-3 px-4">{{ candidate.last_name }}</td>
              <td class="text-left py-3 px-4">{{ candidate.email }}</td>
              <td class="text-left py-3 px-4">{{ candidate.phone }}</td>
              <td class="text-left py-3 px-4">{{ formatDate(candidate.response_date) }}</td>
              <td class="text-left py-3 px-4">
                <InertiaLink :href="route('candidate.details', candidate.num)">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                  View Details
                </button></InertiaLink>
                
              </td>

            </tr>
          </tbody>
        </table>
      </div>
      <pagination v-if="shouldDisplayPagination" :total-items="totalItems" :items-per-page="itemsPerPage"
        @page-changed="changePage" />
    </div>
  </div>
</template>


<script>
import axios from 'axios';
import Pagination from './Pagination.vue';
import moment from 'moment';
import { InertiaLink } from '@inertiajs/inertia-vue3';

export default {
  components: {
    Pagination,
    InertiaLink,
  },

  data() {
    return {
      candidates: [],
      itemsPerPage: 25,
      currentPage: 1,
      itemsToLoad: 50, // Load 50 candidates from API at a time
    };
  },

  computed: {
    totalItems() {
      return this.candidates.length; // Calculate total items based on all candidates
    },
    totalPages() {
      return Math.ceil(this.totalItems / this.itemsPerPage);
    },
    displayedCandidates() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.candidates.slice(start, end);
    },
    shouldDisplayPagination() {
      return this.totalPages > 1; // Display pagination if there's more than one page
    },
  },

  mounted() {
    this.fetchCandidates();
  },

  methods: {
    fetchCandidates() {
      if (this.loadingCandidates) {
        // If candidates are already being loaded, prevent additional requests
        return;
      }

      this.loadingCandidates = true; // Set loading flag to true

      const loadCandidates = () => {
        const offset = this.candidates.length;
        axios
          .get('/candidates', {
            params: {
              limit: this.itemsToLoad,
              offset: offset,
            },
          })
          .then((response) => {
            const newCandidates = response.data;

            if (newCandidates.length > 0) {
              // Concatenate new candidates
              this.candidates = this.candidates.concat(newCandidates);

              // Continue loading more candidates if there might be more
              if (newCandidates.length === this.itemsToLoad) {
                loadCandidates();
              } else {
                // All candidates have been loaded
                this.loadingCandidates = false;
              }
            } else {
              // All candidates have been loaded
              this.loadingCandidates = false;
            }
          })
          .catch((error) => {
            this.loadingCandidates = false;
          });
      };

      loadCandidates(); // Initial call to start loading candidates
    },
    changePage(page) {
      this.currentPage = page;
    },
    viewDetails(candidateNum) {
      this.$emit("view-details", candidateNum);

    },
    formatDate(timestamp) {
      if (!timestamp) {
        return ''; // Return an empty string if timestamp is not available
      }
      return moment(timestamp).format('DD/MM/YYYY');
    },

  },
};
</script>
