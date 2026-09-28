<template>
  <div class="dashboard">

    <!-- HEADER -->
    <div class="page-header">

      <div>

        <span class="eyebrow">
          ADMINISTRATION
        </span>

        <h1>
          Admin Dashboard
        </h1>

        <p>
          Manage MoneyFlow users, transactions, and categories.
        </p>

      </div>

    </div>


    <!-- LOADING -->
    <div
      v-if="loading"
      class="stats-grid"
    >

      <div
        v-for="i in 4"
        :key="i"
        class="stat-card skeleton-card"
      >

        <div class="skeleton-icon"></div>

        <div class="skeleton-content">
          <div class="skeleton-line small"></div>
          <div class="skeleton-line large"></div>
        </div>

      </div>

    </div>


    <!-- STATISTICS -->
    <div
      v-else
      class="stats-grid"
    >

      <div class="stat-card">

        <div class="stat-icon">
          U
        </div>

        <div>
          <span>
            Total Users
          </span>

          <strong>
            {{ stats.users }}
          </strong>
        </div>

      </div>


      <div class="stat-card">

        <div class="stat-icon">
          T
        </div>

        <div>
          <span>
            Total Transactions
          </span>

          <strong>
            {{ stats.transactions }}
          </strong>
        </div>

      </div>


      <div class="stat-card">

        <div class="stat-icon">
          C
        </div>

        <div>
          <span>
            Total Categories
          </span>

          <strong>
            {{ stats.categories }}
          </strong>
        </div>

      </div>


      <div class="stat-card">

        <div class="stat-icon">
          A
        </div>

        <div>
          <span>
            Active Users
          </span>

          <strong>
            {{ stats.activeUsers }}
          </strong>
        </div>

      </div>

    </div>


    <!-- ERROR -->
    <div
      v-if="errorMessage"
      class="error-card"
    >
      {{ errorMessage }}
    </div>


    <!-- MANAGEMENT -->
    <div class="section-header">

      <h2>
        Management
      </h2>

    </div>


    <div class="management-grid">

      <RouterLink
        to="/admin/users"
        class="management-card"
      >

        <div class="management-icon">
          U
        </div>

        <div>

          <h3>
            User Management
          </h3>

          <p>
            View and manage MoneyFlow users.
          </p>

        </div>

        <span>
          →
        </span>

      </RouterLink>


      <RouterLink
        to="/admin/transactions"
        class="management-card"
      >

        <div class="management-icon">
          T
        </div>

        <div>

          <h3>
            Transaction Management
          </h3>

          <p>
            Monitor transactions across users.
          </p>

        </div>

        <span>
          →
        </span>

      </RouterLink>


      <RouterLink
        to="/admin/categories"
        class="management-card"
      >

        <div class="management-icon">
          C
        </div>

        <div>

          <h3>
            Category Management
          </h3>

          <p>
            Manage income and expense categories.
          </p>

        </div>

        <span>
          →
        </span>

      </RouterLink>

    </div>


    <!-- RECENT USERS -->
    <div class="recent-card">

      <div class="recent-header">

        <div>

          <span class="eyebrow">
            ACTIVITY
          </span>

          <h2>
            Recent Users
          </h2>

        </div>

        <RouterLink to="/admin/users">
          View all
        </RouterLink>

      </div>


      <div
        v-if="recentUsers.length"
        class="users-list"
      >

        <div
          v-for="user in recentUsers"
          :key="user.id"
          class="user-row"
        >

          <div class="avatar">
            {{ getInitials(user.name) }}
          </div>

          <div class="user-info">

            <strong>
              {{ user.name }}
            </strong>

            <span>
              {{ user.email }}
            </span>

          </div>

        </div>

      </div>


      <div
        v-else-if="!loading"
        class="empty"
      >
        No recent users available.
      </div>

    </div>

  </div>
</template>


<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'

const API_BASE =
  import.meta.env.VITE_API_BASE_URL || ''

const loading = ref(true)
const errorMessage = ref('')

const stats = ref({
  users: 0,
  transactions: 0,
  categories: 0,
  activeUsers: 0
})

const recentUsers = ref([])


const getHeaders = () => ({
  Authorization:
    `Bearer ${localStorage.getItem('auth_token')}`,

  Accept: 'application/json'
})


const getInitials = (name) => {

  if (!name) {
    return 'U'
  }

  const parts =
    name.trim().split(/\s+/)

  if (parts.length === 1) {
    return parts[0]
      .slice(0, 2)
      .toUpperCase()
  }

  return (
    parts[0][0] +
    parts[1][0]
  ).toUpperCase()
}


const loadDashboard = async () => {

  const token =
    localStorage.getItem('auth_token')

  if (!token) {
    errorMessage.value =
      'You are not logged in.'
    loading.value = false
    return
  }

  loading.value = true
  errorMessage.value = ''

  try {

    const response =
      await axios.get(
        `${API_BASE}/api/admin/dashboard`,
        {
          headers: getHeaders()
        }
      )

    const data =
      response.data?.data || {}

    stats.value = {
      users:
        data.total_users ?? 0,

      transactions:
        data.total_transactions ?? 0,

      categories:
        data.total_categories ?? 0,

      activeUsers:
        data.active_users ?? 0
    }

    recentUsers.value =
      data.recent_users || []

  } catch (error) {

    console.error(
      'Admin dashboard error:',
      error
    )

    if (error.response?.status === 403) {

      errorMessage.value =
        'Access denied. This account is not an administrator.'

    } else if (error.response?.status === 401) {

      errorMessage.value =
        'Your session has expired. Please log in again.'

    } else {

      errorMessage.value =
        error.response?.data?.message ||
        'Unable to load admin dashboard.'
    }

  } finally {

    loading.value = false
  }
}


onMounted(loadDashboard)
</script>


<style scoped>
.dashboard {
  max-width: 1400px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 28px;
}

.eyebrow {
  color: #8178ef;

  font-size: 10px;
  font-weight: 800;

  letter-spacing: 1.4px;
}

h1 {
  margin: 6px 0;

  font-size: 30px;
  line-height: 1.15;
}

.page-header p {
  margin: 0;

  color: #8c94a5;

  font-size: 13px;
}


/* STATS */

.stats-grid {
  display: grid;

  grid-template-columns:
    repeat(4, minmax(0, 1fr));

  gap: 18px;

  margin-bottom: 30px;
}

.stat-card {
  min-height: 120px;

  display: flex;
  align-items: center;

  gap: 15px;

  padding: 20px;

  background: white;

  border: 1px solid #edf0f6;

  border-radius: 15px;

  box-shadow:
    0 5px 20px rgba(31, 35, 52, .035);
}

.stat-icon {
  width: 45px;
  height: 45px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 12px;

  background: #efedff;
  color: #665be9;

  font-size: 14px;
  font-weight: 800;
}

.stat-card span {
  display: block;

  margin-bottom: 5px;

  color: #9198a8;

  font-size: 11px;
}

.stat-card strong {
  font-size: 25px;
}


/* SKELETON */

.skeleton-card {
  overflow: hidden;
}

.skeleton-icon {
  width: 45px;
  height: 45px;

  border-radius: 12px;

  background: #eeeeF4;
}

.skeleton-content {
  flex: 1;
}

.skeleton-line {
  border-radius: 5px;

  background: #eeeeF4;
}

.skeleton-line.small {
  width: 70px;
  height: 9px;

  margin-bottom: 10px;
}

.skeleton-line.large {
  width: 55px;
  height: 20px;
}


/* ERROR */

.error-card {
  margin-bottom: 25px;

  padding: 13px 15px;

  background: #fff1f1;

  border: 1px solid #ffd8d8;

  border-radius: 10px;

  color: #c64e4e;

  font-size: 12px;
}


/* MANAGEMENT */

.section-header {
  margin-bottom: 15px;
}

.section-header h2 {
  margin: 0;

  font-size: 17px;
}

.management-grid {
  display: grid;

  grid-template-columns:
    repeat(3, minmax(0, 1fr));

  gap: 18px;

  margin-bottom: 30px;
}

.management-card {
  display: flex;
  align-items: center;

  gap: 15px;

  padding: 20px;

  background: white;

  border: 1px solid #edf0f6;

  border-radius: 15px;

  text-decoration: none;
  color: inherit;

  transition: .2s ease;
}

.management-card:hover {
  transform: translateY(-2px);

  border-color: #dcd8ff;

  box-shadow:
    0 8px 25px rgba(31, 35, 52, .07);
}

.management-icon {
  width: 43px;
  height: 43px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 11px;

  background: #f0efff;
  color: #665be9;

  font-weight: 800;
}

.management-card h3 {
  margin: 0 0 4px;

  font-size: 13px;
}

.management-card p {
  margin: 0;

  color: #9299a9;

  font-size: 11px;
  line-height: 1.5;
}

.management-card > span {
  margin-left: auto;

  color: #888fa0;
}


/* RECENT */

.recent-card {
  padding: 22px;

  background: white;

  border: 1px solid #edf0f6;

  border-radius: 15px;
}

.recent-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  margin-bottom: 18px;
}

.recent-header h2 {
  margin: 5px 0 0;

  font-size: 17px;
}

.recent-header a {
  color: #665be9;

  font-size: 12px;
  font-weight: 650;

  text-decoration: none;
}

.users-list {
  display: flex;
  flex-direction: column;
}

.user-row {
  display: flex;
  align-items: center;

  gap: 12px;

  padding: 12px 0;

  border-top: 1px solid #f0f1f5;
}

.avatar {
  width: 36px;
  height: 36px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #6c63ff;
  color: white;

  font-size: 11px;
  font-weight: 700;
}

.user-info {
  display: flex;
  flex-direction: column;

  gap: 3px;
}

.user-info strong {
  font-size: 12px;
}

.user-info span {
  color: #969dad;

  font-size: 10px;
}

.empty {
  padding: 25px 0;

  text-align: center;

  color: #969dad;

  font-size: 12px;
}


@media (max-width: 1000px) {

  .stats-grid {
    grid-template-columns:
      repeat(2, 1fr);
  }

  .management-grid {
    grid-template-columns: 1fr;
  }
}


@media (max-width: 550px) {

  .stats-grid {
    grid-template-columns: 1fr;
  }

  h1 {
    font-size: 25px;
  }
}
</style>