<template>
    <div class="pagination-container">
      <button @click="changePage(currentPage - 1)" :disabled="currentPage <= 1" class="pagination-button" :class="{ 'disabled': currentPage <= 1 }">Prev</button>
      <div class="pagination-pages">
        <span v-for="page in totalPages" :key="page" @click="changePage(page)" class="pagination-page" :class="{ 'active': page === currentPage }">{{ page }}</span>
      </div>
      <button @click="changePage(currentPage + 1)" :disabled="currentPage >= totalPages" class="pagination-button" :class="{ 'disabled': currentPage >= totalPages }">Next</button>
    </div>
  </template>
  
  <style scoped>
  .pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
  }
  
  .pagination-button {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 5px 10px;
    margin: 0 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
  }
  
  .pagination-button:hover {
    background-color: #f0f0f0;
  }
  
  .pagination-button.disabled {
    cursor: not-allowed;
    opacity: 0.6;
  }
  
  .pagination-pages {
    display: flex;
  }
  
  .pagination-page {
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    padding: 5px 10px;
    margin: 0 5px;
    cursor: pointer;
    transition: background-color 0.3s, color 0.3s;
  }
  
  .pagination-page:hover {
    background-color: #f0f0f0;
  }
  
  .pagination-page.active {
    background-color: #007bff;
    color: #fff;
  }
  </style>
  
  
  <script>
  export default {
    props: {
      totalItems: Number,
      itemsPerPage: Number
    },
    computed: {
      totalPages() {
        return Math.ceil(this.totalItems / this.itemsPerPage);
      }
    },
    data() {
      return {
        currentPage: 1
      };
    },
    methods: {
      changePage(page) {
        if (page < 1 || page > this.totalPages) return;
        this.currentPage = page;
        this.$emit('page-changed', this.currentPage);
      }
    }
  };
  </script>
  