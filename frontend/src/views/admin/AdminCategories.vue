<template>
  <div class="admin-page">

    <!-- HEADER -->
    <div class="page-header">

      <div>

        <span class="eyebrow">
          ADMINISTRATION
        </span>

        <h1>
          Category Management
        </h1>

        <p>
          View and manage categories created by MoneyFlow users.
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


    <!-- CATEGORIES -->
    <div class="category-grid">

      <!-- LOADING -->
      <template v-if="loading">

        <div
          v-for="i in 6"
          :key="i"
          class="category-card skeleton-card"
        >

          <div class="skeleton-icon"></div>

          <div class="skeleton-info">

            <div class="skeleton-line"></div>

            <div class="skeleton-line small"></div>

          </div>

        </div>

      </template>


      <!-- CATEGORIES -->
      <template v-else>

        <div
          v-for="category in categories"
          :key="category.id_kategori"
          class="category-card"
        >

          <div class="category-icon">
            {{ category.ikon || '•' }}
          </div>


          <div class="category-info">

            <strong>
              {{ category.nama_kategori }}
            </strong>


            <span
              :class="[
                'type',
                category.jenis
              ]"
            >
              {{ category.jenis }}
            </span>


            <small>

              Created by:
              {{ category.user?.name || 'Unknown user' }}

            </small>

          </div>


          <button
            class="delete-button"
            :disabled="
              deletingId === category.id_kategori
            "
            @click="
              deleteCategory(
                category.id_kategori
              )
            "
          >

            {{
              deletingId === category.id_kategori
                ? 'Deleting...'
                : 'Delete'
            }}

          </button>

        </div>


        <!-- EMPTY -->
        <div
          v-if="!categories.length"
          class="empty"
        >
          No categories available.
        </div>

      </template>

    </div>

  </div>
</template>


<script setup>
import {
  onMounted,
  ref
} from 'vue'

import axios from 'axios'


const API_BASE =
  import.meta.env.VITE_API_BASE_URL || ''

const categories = ref([])

const loading = ref(false)

const deletingId = ref(null)

const errorMessage = ref('')


const getHeaders = () => ({
  Authorization:
    `Bearer ${localStorage.getItem('auth_token')}`,

  Accept: 'application/json'
})


const loadCategories = async () => {

  loading.value = true
  errorMessage.value = ''

  try {

    const response =
      await axios.get(
        `${API_BASE}/api/admin/categories`,
        {
          headers: getHeaders()
        }
      )

    categories.value =
      response.data?.data || []

  } catch (error) {

    console.error(
      'Failed to load categories:',
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
        'Failed to load categories.'
    }

  } finally {

    loading.value = false
  }
}


const deleteCategory = async (id) => {

  const category =
    categories.value.find(
      item => item.id_kategori === id
    )

  if (!category) {
    return
  }


  const confirmed =
    confirm(
      `Delete "${category.nama_kategori}"?`
    )

  if (!confirmed) {
    return
  }


  deletingId.value = id

  errorMessage.value = ''


  try {

    await axios.delete(
      `${API_BASE}/api/admin/categories/${id}`,
      {
        headers: getHeaders()
      }
    )


    categories.value =
      categories.value.filter(
        item => item.id_kategori !== id
      )

  } catch (error) {

    console.error(
      'Failed to delete category:',
      error
    )

    errorMessage.value =
      error.response?.data?.message ||
      'Category could not be deleted.'

  } finally {

    deletingId.value = null
  }
}


onMounted(loadCategories)
</script>


<style scoped>
.admin-page {
  max-width: 1400px;
  margin: auto;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;

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


/* GRID */

.category-grid {
  display: grid;

  grid-template-columns:
    repeat(2, minmax(0, 1fr));

  gap: 14px;
}


/* CARD */

.category-card {
  display: flex;
  align-items: center;

  gap: 13px;

  padding: 17px;

  background: white;

  border: 1px solid #edf0f6;

  border-radius: 13px;

  box-shadow:
    0 5px 20px rgba(31, 35, 52, .025);
}

.category-icon {
  width: 40px;
  height: 40px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 10px;

  background: #efedff;
  color: #665be9;

  font-weight: 700;
}

.category-info {
  display: flex;
  flex-direction: column;

  gap: 5px;

  flex: 1;

  min-width: 0;
}

.category-info strong {
  color: #252c3b;

  font-size: 13px;
}

.category-info small {
  color: #969dad;

  font-size: 9px;

  overflow: hidden;

  text-overflow: ellipsis;

  white-space: nowrap;
}


/* TYPE */

.type {
  width: fit-content;

  padding: 4px 7px;

  border-radius: 10px;

  font-size: 8px;
  font-weight: 700;

  text-transform: capitalize;
}

.type.pemasukan {
  background: #eafaf1;
  color: #23945a;
}

.type.pengeluaran {
  background: #fff0f0;
  color: #d45a5a;
}


/* DELETE */

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


/* SKELETON */

.skeleton-card {
  min-height: 78px;
}

.skeleton-icon {
  width: 40px;
  height: 40px;

  flex-shrink: 0;

  border-radius: 10px;

  background: #eeeeF4;
}

.skeleton-info {
  flex: 1;
}

.skeleton-line {
  width: 100px;
  height: 11px;

  margin-bottom: 9px;

  border-radius: 5px;

  background: #eeeeF4;
}

.skeleton-line.small {
  width: 65px;
  height: 8px;

  margin-bottom: 0;
}


/* EMPTY */

.empty {
  grid-column: 1 / -1;

  padding: 50px;

  background: white;

  border: 1px solid #edf0f6;

  border-radius: 15px;

  text-align: center;

  color: #969dad;

  font-size: 12px;
}


@media (max-width: 850px) {

  .category-grid {
    grid-template-columns: 1fr;
  }
}


@media (max-width: 600px) {

  .page-header {
    align-items: flex-start;

    flex-direction: column;

    gap: 15px;
  }

  .category-card {
    padding: 14px;
  }

  .delete-button {
    flex-shrink: 0;
  }
}
</style>