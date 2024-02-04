<template>
    <div>
      <table>
        <thead>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email Address</th>
            <th>Phone Number</th>
            <th>Response Date</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="candidate in candidates" :key="candidate.num">
            <td>{{ candidate.first_name }}</td>
            <td>{{ candidate.last_name }}</td>
            <td>{{ candidate.email }}</td>
            <td>{{ candidate.phone }}</td>
            <td>{{ candidate.response_date }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      candidates: Array
    }
  }
  </script>
  