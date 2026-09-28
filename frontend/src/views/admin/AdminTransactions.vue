<template>
  <div class="admin-page">

    <!-- HEADER -->
    <div class="page-header">

      <div>

        <span class="eyebrow">
          ADMINISTRATION
        </span>

        <h1>
          User Management
        </h1>

        <p>
          View and manage registered MoneyFlow users.
        </p>

      </div>

    </div>


    <!-- ERROR -->
    <div
      v-if="errorMessage"
      class="error-card"
    >
      {{ errorMessage }}
    </div>


    <!-- TABLE -->
    <div class="table-card">

      <div class="table-header">

        <strong>
          Users
        </strong>

        <input
          v-model="search"
          type="text"
          placeholder="Search users..."
        />

      </div>


      <div class="table-wrapper">

        <table>

          <thead>

            <tr>
              <th>ID</th>
              <th>User</th>
              <th>Email</th>
              <th>Role</th>
              <th>Created</th>
              <th>Action</th>
            </tr>

          </thead>


          <tbody>

            <!-- LOADING -->
            <tr v-if="loading">

              <td
                colspan="6"
                class="empty"
              >
                Loading users...
              </td>

            </tr>


            <!-- USERS -->
            <tr
              v-for="user in filteredUsers"
              :key="user.id"
            >

              <td>
                {{ user.id }}
              </td>


              <td>

                <div class="user-cell">

                  <div class="avatar">
                    {{ getInitials(user.name) }}
                  </div>

                  <strong>
                    {{ user.name }}
                  </strong>

                </div>

              </td>


              <td>
                {{ user.email }}
              </td>


              <td>

                <span class="badge">
                  {{ user.role || 'user' }}
                </span>

              </td>


              <td>
                {{ formatDate(user.created_at) }}
              </td>


              <td>

                <button
                  class="delete-button"
                  :disabled="deletingId === user.id"
                  @click="deleteUser(user.id)"
                >
                  {{
                    deletingId === user.id
                      ? 'Deleting...'
                      : 'Delete'
                  }}
                </button>

              </td>

            </tr>


            <!-- EMPTY -->
            <tr
              v-if="
                !loading &&
                !filteredUsers.length
              "
            >

              <td
                colspan="6"
                class="empty"
              >
                No users found.
              </td>

            </tr>

          </tbody>

        </table>

      </div>

    </div>

  </div>
</template>


<script setup>
import {
  computed,
  onMounted,
  ref
} from 'vue'

import axios from 'axios'


const API_BASE =
  import.meta.env.VITE_API_BASE_URL || ''

const users = ref([])

const search = ref('')

const loading = ref(false)

const deletingId = ref(null)

const errorMessage = ref('')


const getHeaders = () => ({
  Authorization:
    `Bearer ${localStorage.getItem('auth_token')}`,

  Accept: 'application/json'
})


const filteredUsers = computed(() => {

  const keyword =
    search.value
      .toLowerCase()
      .trim()

  if (!keyword) {
    return users.value
  }

  return users.value.filter(user => {

    return (
      String(user.name || '')
        .toLowerCase()
        .includes(keyword) ||

      String(user.email || '')
        .toLowerCase()
        .includes(keyword)
    )

  })
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


const formatDate = (date) => {

  if (!date) {
    return '-'
  }

  return new Date(date)
    .toLocaleDateString('id-ID')
}


const loadUsers = async () => {

  loading.value = true
  errorMessage.value = ''

  try {

    const response =
      await axios.get(
        `${API_BASE}/api/admin/users`,
        {
          headers: getHeaders()
        }
      )

    users.value =
      response.data?.data || []

  } catch (error) {

    console.error(
      'Failed to load users:',
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
        'Failed to load users.'
    }

  } finally {

    loading.value = false
  }
}


const deleteUser = async (id) => {

  const user =
    users.value.find(
      item => item.id === id
    )

  if (!user) {
    return
  }


  const confirmed =
    confirm(
      `Are you sure you want to delete "${user.name}"?`
    )

  if (!confirmed) {
    return
  }


  deletingId.value = id

  errorMessage.value = ''


  try {

    await axios.delete(
      `${API_BASE}/api/admin/users/${id}`,
      {
        headers: getHeaders()
      }
    )


    users.value =
      users.value.filter(
        item => item.id !== id
      )

  } catch (error) {

    console.error(
      'Failed to delete user:',
      error
    )

    errorMessage.value =
      error.response?.data?.message ||
      'Failed to delete user.'

  } finally {

    deletingId.value = null
  }
}


onMounted(loadUsers)
</script>


<style scoped>
.admin-page {
  max-width: 1400px;
  margin: auto;
}

.page-header {
  margin-bottom: 25px;
}

.eyebrow {
  color: #8178ef;

  font-size: 10px;
  font-weight: 800;

  letter-spacing: 1.4px;
}

h1 {
  margin: 6px 0;

  font-size: 28px;
}

.page-header p {
  margin: 0;

  color: #9299a9;

  font-size: 13px;
}


/* ERROR */

.error-card {
  margin-bottom: 20px;

  padding: 13px 15px;

  background: #fff1f1;

  border: 1px solid #ffd8d8;

  border-radius: 10px;

  color: #c64e4e;

  font-size: 12px;
}


/* TABLE */

.table-card {
  background: white;

  border: 1px solid #edf0f6;

  border-radius: 15px;

  overflow: hidden;
}

.table-header {
  min-height: 70px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 15px 20px;

  border-bottom: 1px solid #edf0f6;
}

.table-header strong {
  font-size: 15px;
}

.table-header input {
  width: 220px;

  padding: 10px 13px;

  border: 1px solid #e4e6ed;

  border-radius: 9px;

  outline: none;

  font-family: inherit;

  font-size: 12px;
}

.table-header input:focus {
  border-color: #6c63ff;
}

.table-wrapper {
  overflow-x: auto;
}

table {
  width: 100%;

  min-width: 750px;

  border-collapse: collapse;
}

th {
  padding: 14px 20px;

  background: #fafbfc;

  color: #9299a9;

  font-size: 10px;
  font-weight: 750;

  text-align: left;
}

td {
  padding: 15px 20px;

  border-top: 1px solid #f0f1f5;

  color: #626b7d;

  font-size: 12px;
}

.user-cell {
  display: flex;
  align-items: center;

  gap: 10px;
}

.user-cell strong {
  color: #252c3b;
}

.avatar {
  width: 32px;
  height: 32px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #6c63ff;
  color: white;

  font-size: 10px;
  font-weight: 700;
}

.badge {
  display: inline-flex;

  padding: 5px 9px;

  border-radius: 20px;

  background: #efedff;
  color: #665be9;

  font-size: 10px;
  font-weight: 700;
}

.delete-button {
  padding: 7px 10px;

  border: 0;

  border-radius: 7px;

  background: #fff1f1;
  color: #d75a5a;

  font-size: 10px;

  cursor: pointer;
}

.delete-button:hover:not(:disabled) {
  background: #ffe2e2;
}

.delete-button:disabled {
  opacity: .6;
  cursor: not-allowed;
}

.empty {
  padding: 40px !important;

  text-align: center;

  color: #969dad;
}


@media (max-width: 600px) {

  .table-header {
    align-items: stretch;

    flex-direction: column;

    gap: 12px;
  }

  .table-header input {
    width: 100%;
  }
}
</style>