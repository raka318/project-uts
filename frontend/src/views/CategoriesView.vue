<template>
  <section class="page">
    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>
        <h1>Categories</h1>
        <p>Organize your income and expenses with categories.</p>
      </div>

      <button class="primary-button" @click="openCreate">
        <span>+</span>
        Add Category
      </button>
    </div>

    <div v-if="error" class="alert">{{ error }}</div>

    <div v-if="loading" class="state-card">
      <div class="state-icon">↻</div>
      <strong>Loading categories...</strong>
      <span>Please wait while your categories are loaded.</span>
    </div>

    <div v-else-if="categories.length" class="category-grid">
      <article
        v-for="category in categories"
        :key="category.id_kategori"
        class="category-card"
      >
        <div class="category-icon">{{ category.ikon || (category.jenis === 'pemasukan' ? '↗' : '↘') }}</div>

        <div class="category-content">
          <div class="category-name">{{ category.nama_kategori }}</div>
          <span class="type-badge" :class="category.jenis">
            {{ category.jenis === 'pemasukan' ? 'Income' : 'Expense' }}
          </span>
        </div>

        <div class="category-actions">
          <button class="icon-button" title="Edit" @click="openEdit(category)">✎</button>
          <button class="icon-button danger" title="Delete" @click="deleteCategory(category)">×</button>
        </div>
      </article>
    </div>

    <div v-else class="state-card">
      <div class="state-icon">◈</div>
      <strong>No categories yet</strong>
      <span>Create categories such as Food, Salary, Transport, or Shopping so you can select them in transactions.</span>
      <button class="secondary-button" @click="openCreate">Create a category</button>
    </div>

    <div v-if="showModal" class="modal-backdrop" @click.self="closeModal">
      <div class="modal">
        <button class="close-button" @click="closeModal">×</button>

        <div class="modal-header">
          <span class="eyebrow">CATEGORY</span>
          <h2>{{ editingId ? 'Edit Category' : 'Add Category' }}</h2>
          <p>{{ editingId ? 'Update your category details.' : 'Create a category for your transactions.' }}</p>
        </div>

        <form @submit.prevent="saveCategory">
          <label>
            Category name
            <input v-model="form.nama_kategori" type="text" placeholder="Example: Food" maxlength="100" required />
          </label>

          <label>
            Type
            <select v-model="form.jenis" required>
              <option value="pemasukan">Income</option>
              <option value="pengeluaran">Expense</option>
            </select>
          </label>

          <label>
            Icon (optional)
            <input v-model="form.ikon" type="text" maxlength="100" placeholder="Example: 🍔" />
          </label>

          <p v-if="formError" class="form-error">{{ formError }}</p>

          <div class="modal-actions">
            <button type="button" class="cancel-button" @click="closeModal">Cancel</button>
            <button type="submit" class="primary-button" :disabled="saving">
              {{ saving ? 'Saving...' : editingId ? 'Save Changes' : 'Add Category' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import api from '../utils/api.js'

const categories = ref([])
const loading = ref(true)
const saving = ref(false)
const error = ref('')
const formError = ref('')
const showModal = ref(false)
const editingId = ref(null)

const emptyForm = () => ({
  nama_kategori: '',
  jenis: 'pengeluaran',
  ikon: '',
})

const form = reactive(emptyForm())

function resetForm() {
  Object.assign(form, emptyForm())
}

function getErrorMessage(err, fallback) {
  if (err?.response?.data?.errors) {
    return Object.values(err.response.data.errors).flat().join(' ')
  }
  return err?.response?.data?.message || err?.message || fallback
}

async function loadCategories() {
  loading.value = true
  error.value = ''

  try {
    const response = await api.get('/kategori')
    categories.value = Array.isArray(response.data?.data)
      ? response.data.data
      : []
  } catch (err) {
    if (err?.response?.status === 401) {
      window.location.href = '/login'
      return
    }

    categories.value = []
    error.value = getErrorMessage(err, 'Could not load categories.')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editingId.value = null
  resetForm()
  formError.value = ''
  showModal.value = true
}

function openEdit(category) {
  editingId.value = category.id_kategori
  Object.assign(form, {
    nama_kategori: category.nama_kategori || '',
    jenis: category.jenis || 'pengeluaran',
    ikon: category.ikon || '',
  })
  formError.value = ''
  showModal.value = true
}

function closeModal() {
  if (saving.value) return
  showModal.value = false
}

async function saveCategory() {
  saving.value = true
  formError.value = ''
  error.value = ''

  const payload = {
    nama_kategori: form.nama_kategori.trim(),
    jenis: form.jenis,
    ikon: form.ikon.trim() || null,
  }

  try {
    if (editingId.value) {
      const response = await api.put(`/kategori/${editingId.value}`, payload)
      const updated = response.data?.data

      if (updated) {
        const index = categories.value.findIndex(c => c.id_kategori === editingId.value)
        if (index !== -1) categories.value[index] = updated
      } else {
        await loadCategories()
      }
    } else {
      const response = await api.post('/kategori', payload)
      const created = response.data?.data

      if (created) {
        categories.value.unshift(created)
      } else {
        await loadCategories()
      }
    }

    closeModal()
  } catch (err) {
    if (err?.response?.status === 401) {
      window.location.href = '/login'
      return
    }
    formError.value = getErrorMessage(err, 'Could not save this category.')
  } finally {
    saving.value = false
  }
}

async function deleteCategory(category) {
  if (!window.confirm(`Delete "${category.nama_kategori}"?`)) return

  error.value = ''

  try {
    await api.delete(`/kategori/${category.id_kategori}`)
    categories.value = categories.value.filter(c => c.id_kategori !== category.id_kategori)
  } catch (err) {
    if (err?.response?.status === 401) {
      window.location.href = '/login'
      return
    }
    error.value = getErrorMessage(err, 'Could not delete this category.')
  }
}

onMounted(loadCategories)
</script>

<style scoped>
.page {
  width: 100%;
  max-width: 1180px;
  margin: 0 auto;
  padding: 34px 34px 48px;
  box-sizing: border-box;
  color: #172033;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  gap: 24px;
  margin-bottom: 28px;
}

.eyebrow {
  display: block;
  color: #6c5ce7;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .12em;
  margin-bottom: 7px;
}

h1, h2, p { margin: 0; }

h1 {
  font-size: 30px;
  line-height: 1.1;
  margin-bottom: 7px;
}

.page-header p,
.modal-header p {
  color: #7b8498;
  font-size: 14px;
}

.primary-button,
.secondary-button,
.cancel-button {
  border: 0;
  border-radius: 10px;
  min-height: 42px;
  padding: 0 17px;
  font-weight: 700;
  cursor: pointer;
}

.primary-button {
  color: white;
  background: #6655e9;
  box-shadow: 0 7px 18px rgba(102, 85, 233, .18);
}

.primary-button span { margin-right: 5px; font-size: 18px; }
.primary-button:disabled { opacity: .65; cursor: not-allowed; }

.secondary-button,
.cancel-button {
  color: #5c6476;
  background: #f0f1f6;
}

.alert,
.form-error {
  color: #b42318;
  background: #fff1f0;
  border: 1px solid #ffd5d1;
  border-radius: 10px;
  padding: 11px 13px;
  font-size: 13px;
  margin-bottom: 18px;
}

.category-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 14px;
}

.category-card {
  display: flex;
  align-items: center;
  gap: 15px;
  min-width: 0;
  background: white;
  border: 1px solid #e8eaf0;
  border-radius: 14px;
  padding: 17px;
  box-shadow: 0 8px 28px rgba(26, 32, 55, .05);
}

.category-icon {
  flex: 0 0 auto;
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  background: #f0edff;
  font-size: 20px;
}

.category-content {
  flex: 1;
  min-width: 0;
}

.category-name {
  font-size: 15px;
  font-weight: 800;
  margin-bottom: 7px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.type-badge {
  display: inline-flex;
  padding: 4px 8px;
  border-radius: 999px;
  font-size: 10px;
  font-weight: 800;
}

.type-badge.pemasukan {
  color: #157347;
  background: #eaf8f0;
}

.type-badge.pengeluaran {
  color: #c0392b;
  background: #fff0ee;
}

.category-actions {
  display: flex;
  gap: 5px;
}

.icon-button {
  width: 32px;
  height: 32px;
  border: 0;
  border-radius: 8px;
  background: #f5f6fa;
  color: #667085;
  cursor: pointer;
}

.icon-button.danger:hover {
  background: #fff0f0;
  color: #d92d20;
}

.state-card {
  min-height: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 35px;
  background: white;
  border: 1px solid #e8eaf0;
  border-radius: 16px;
  box-shadow: 0 8px 28px rgba(26, 32, 55, .05);
}

.state-icon {
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: grid;
  place-items: center;
  background: #f0edff;
  font-size: 20px;
  margin-bottom: 16px;
}

.state-card strong {
  font-size: 18px;
  margin-bottom: 7px;
}

.state-card span {
  color: #8a91a1;
  font-size: 13px;
  max-width: 500px;
  line-height: 1.6;
  margin-bottom: 18px;
}

.modal-backdrop {
  position: fixed;
  inset: 0;
  z-index: 100;
  display: grid;
  place-items: center;
  padding: 20px;
  background: rgba(21, 25, 39, .48);
}

.modal {
  position: relative;
  width: min(480px, 100%);
  max-height: 90vh;
  overflow-y: auto;
  background: white;
  border-radius: 18px;
  padding: 30px;
  box-sizing: border-box;
  box-shadow: 0 24px 70px rgba(20, 25, 45, .2);
}

.close-button {
  position: absolute;
  right: 20px;
  top: 18px;
  border: 0;
  background: transparent;
  color: #9299aa;
  font-size: 25px;
  cursor: pointer;
}

.modal-header { margin-bottom: 24px; padding-right: 25px; }
.modal-header h2 { font-size: 24px; margin-bottom: 6px; }

form {
  display: flex;
  flex-direction: column;
  gap: 17px;
}

label {
  display: flex;
  flex-direction: column;
  gap: 7px;
  color: #424a5b;
  font-size: 12px;
  font-weight: 700;
}

input, select {
  width: 100%;
  height: 42px;
  box-sizing: border-box;
  border: 1px solid #dfe2e9;
  border-radius: 9px;
  padding: 0 12px;
  outline: none;
  color: #252b39;
  background: white;
  font: inherit;
}

input:focus, select:focus {
  border-color: #8c80ed;
  box-shadow: 0 0 0 3px rgba(108, 92, 231, .1);
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 5px;
}

@media (max-width: 700px) {
  .page { padding: 25px 16px 35px; }
  .page-header { align-items: flex-start; flex-direction: column; }
  .primary-button { width: 100%; }
  .category-grid { grid-template-columns: 1fr; }
  .modal { padding: 24px 20px; }
}
</style>
