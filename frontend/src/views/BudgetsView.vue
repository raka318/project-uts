<template>
  <div class="page">

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>

        <h1>Budgets</h1>

        <p>
          Set spending limits and stay on track.
        </p>
      </div>

      <button class="primary-button" @click="openAddModal">
        <span>+</span>
        Add Budget
      </button>
    </div>


    <!-- TOTAL BUDGET -->
    <div class="overview-card">

      <div>
        <span>Total Monthly Budget</span>

        <strong>
          {{ budgets.length ? '$' + formatNumber(totalLimit) : '—' }}
        </strong>

        <p>
          {{ budgets.length
            ? '$' + formatNumber(totalSpent) + ' spent this month'
            : 'No budget data yet' }}
        </p>
      </div>

      <div class="budget-progress">

        <div class="progress-track">
          <div
            class="progress-fill"
            :class="{
              warning: totalPercent >= 80 && totalPercent < 100,
              danger: totalPercent >= 100
            }"
            :style="{ width: Math.min(totalPercent, 100) + '%' }"
          ></div>
        </div>

        <span>
          {{ budgets.length ? totalPercent + '% used' : '—' }}
        </span>

      </div>

    </div>


    <!-- SECTION -->
    <div class="section-header">
      <div>
        <h2>Your Budgets</h2>

        <p>
          Track your spending limits.
        </p>
      </div>
    </div>


    <!-- =========================
        LOADING SKELETON
    ========================== -->
    <div v-if="loading" class="budget-skeleton">

      <!-- Header skeleton -->
      <div class="skeleton-header">

        <div class="skeleton-header-text">
          <div class="skeleton-line skeleton-eyebrow"></div>
          <div class="skeleton-line skeleton-title"></div>
          <div class="skeleton-line skeleton-subtitle"></div>
        </div>

        <div class="skeleton-button"></div>

      </div>


      <!-- Overview skeleton -->
      <div class="skeleton-overview">

        <div class="skeleton-overview-info">
          <div class="skeleton-line skeleton-small"></div>
          <div class="skeleton-line skeleton-total"></div>
          <div class="skeleton-line skeleton-description"></div>
        </div>

        <div class="skeleton-overview-progress">
          <div class="skeleton-progress"></div>
          <div class="skeleton-line skeleton-percent"></div>
        </div>

      </div>


      <!-- Section title -->
      <div class="skeleton-section-header">
        <div class="skeleton-line skeleton-section-title"></div>
        <div class="skeleton-line skeleton-section-subtitle"></div>
      </div>


      <!-- Budget cards -->
      <div class="skeleton-budget-grid">

        <div
          v-for="i in 6"
          :key="'budget-skeleton-' + i"
          class="skeleton-budget-card"
        >

          <div class="skeleton-budget-top">

            <div class="skeleton-category-icon"></div>

            <div class="skeleton-more"></div>

          </div>

          <div class="skeleton-line skeleton-budget-name"></div>

          <div class="skeleton-line skeleton-budget-category"></div>

          <div class="skeleton-budget-money">
            <div class="skeleton-line skeleton-spent"></div>
            <div class="skeleton-line skeleton-limit"></div>
          </div>

          <div class="skeleton-progress"></div>

          <div class="skeleton-budget-footer">
            <div class="skeleton-line skeleton-footer-left"></div>
            <div class="skeleton-line skeleton-footer-right"></div>
          </div>

          <div class="skeleton-line skeleton-dates"></div>

        </div>

      </div>

    </div>

    <!-- EMPTY STATE -->
    <div
      v-else-if="budgets.length === 0"
      class="empty-state"
    >
      <div class="empty-icon">💜</div>

      <strong>No budgets yet.</strong>

      <span>
        Create a budget to start tracking your spending limits.
      </span>

      <button
        class="empty-add-button"
        @click="openAddModal"
      >
        + Create Budget
      </button>
    </div>


    <!-- BUDGET GRID -->
    <div
      v-else
      class="budget-grid"
    >

      <div
        v-for="budget in normalizedBudgets"
        :key="budget.id"
        class="budget-card"
      >

        <div class="budget-top">

          <div class="category-icon">
            {{ budget.icon || '💜' }}
          </div>

          <button
            class="more-button"
            @click="toggleMenu(budget.id)"
          >
            ⋮
          </button>

          <!-- MENU -->
          <div
            v-if="openMenu === budget.id"
            class="budget-menu"
          >
            <button
              v-if="!budget.hasSavingsTransfers"
              @click="openEditModal(budget)"
            >
              Edit
            </button>

            <button
              v-else
              class="disabled-option"
              type="button"
              @click="showEditBlocked(budget)"
            >
              Edit locked
            </button>

            <button
              v-if="budget.hasSavingsTransfers"
              class="disabled-option"
              type="button"
              @click="showDeleteBlocked(budget)"
            >
              Delete locked
            </button>

            <button
              v-if="budget.hasSavingsTransfers"
              class="undo-option"
              type="button"
              @click="undoSavingsContributionsForBudget(budget)"
            >
              Undo Savings
            </button>

            <button
              v-else
              class="delete-option"
              type="button"
              @click="deleteBudget(budget)"
            >
              Delete
            </button>
          </div>

        </div>


        <!-- BUDGET NAME -->
        <h3>
          {{ budget.name || 'Unnamed Budget' }}
        </h3>

        <!-- CATEGORY NAME -->
        <div class="budget-category">
          {{ budget.categoryName }}
        </div>


        <div class="budget-money">

          <strong>
            ${{ formatNumber(budget.spent) }}
          </strong>

          <span>
            / ${{ formatNumber(budget.limit) }}
          </span>

        </div>


        <div class="progress-track">

          <div
            class="progress-fill"
            :class="{
              warning: budget.percent >= 80 && budget.percent < 100,
              danger: budget.percent >= 100
            }"
            :style="{ width: Math.min(budget.percent, 100) + '%' }"
          ></div>

        </div>


        <div class="budget-footer">

          <span>
            {{ budget.percent }}% used
          </span>

          <span
            :class="{ negative: budget.remaining < 0 }"
          >
            ${{ formatNumber(Math.abs(budget.remaining)) }}
            {{ budget.remaining < 0 ? 'over' : 'left' }}
          </span>

        </div>


        <div class="budget-dates">
          {{ formatDate(budget.startDate) }}
          —
          {{ formatDate(budget.endDate) }}
        </div>

      </div>

    </div>


    <!-- ADD / EDIT MODAL -->
    <div
      v-if="showModal"
      class="modal-overlay"
      @click.self="closeModal"
    >

      <div class="modal">

        <div class="modal-header">

          <div>
            <span class="eyebrow">
              {{ editingBudget ? 'EDIT BUDGET' : 'NEW BUDGET' }}
            </span>

            <h2>
              {{ editingBudget ? 'Edit Budget' : 'Create Budget' }}
            </h2>
          </div>

          <button
            class="close-button"
            @click="closeModal"
          >
            ×
          </button>

        </div>


        <form @submit.prevent="saveBudget">

          <!-- NAME -->
          <div class="form-group">

            <label>
              Budget Name
            </label>

            <input
              v-model="form.nama_anggaran"
              type="text"
              placeholder="e.g. Food Budget"
              required
              maxlength="150"
            />

          </div>


          <!-- CATEGORY -->
          <div class="form-group">

            <label>
              Category
            </label>

            <select
              v-model="form.id_kategori"
              required
            >
              <option
                value=""
                disabled
              >
                Select a category
              </option>

              <option
                v-for="category in expenseCategories"
                :key="category.id_kategori"
                :value="category.id_kategori"
              >
                {{ category.ikon || '📁' }}
                {{ category.nama_kategori }}
              </option>

            </select>

            <small v-if="expenseCategories.length === 0">
              No expense categories found. Create an expense category first.
            </small>

          </div>


          <!-- AMOUNT -->
          <div class="form-group">

            <label>
              Budget Amount
            </label>

            <div class="amount-input">

              <span>$</span>

              <input
                v-model="form.jumlah"
                type="number"
                min="0.01"
                step="0.01"
                placeholder="0.00"
                required
              />

            </div>

          </div>


          <!-- DATES -->
          <div class="date-grid">

            <div class="form-group">

              <label>
                Start Date
              </label>

              <input
                v-model="form.tanggal_mulai"
                type="date"
                required
              />

            </div>

            <div class="form-group">

              <label>
                End Date
              </label>

              <input
                v-model="form.tanggal_selesai"
                type="date"
                required
              />

            </div>

          </div>


          <!-- ERROR -->
          <div
            v-if="errorMessage"
            class="form-error"
          >
            {{ errorMessage }}
          </div>


          <!-- ACTIONS -->
          <div class="modal-actions">

            <button
              type="button"
              class="cancel-button"
              @click="closeModal"
            >
              Cancel
            </button>

            <button
              type="submit"
              class="save-button"
              :disabled="saving || expenseCategories.length === 0"
            >
              {{ saving
                ? 'Saving...'
                : editingBudget
                  ? 'Save Changes'
                  : 'Create Budget'
              }}
            </button>

          </div>

        </form>

      </div>

    </div>

  </div>
</template>


<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../utils/api.js'


const router = useRouter()


/* =========================
   STATE
========================= */

const budgets = ref([])
const categories = ref([])
const transactions = ref([])

const loading = ref(true)
const saving = ref(false)

const showModal = ref(false)
const editingBudget = ref(null)

const openMenu = ref(null)

const errorMessage = ref('')


/* =========================
   FORM
========================= */

const form = ref({
  id_kategori: '',
  nama_anggaran: '',
  jumlah: '',
  tanggal_mulai: '',
  tanggal_selesai: ''
})


/* =========================
   EXPENSE CATEGORIES
========================= */

const expenseCategories = computed(() => {
  return categories.value.filter(
    category => category.jenis === 'pengeluaran'
  )
})


/* =========================
   SPENDING CALCULATION
========================= */

const calculateSpent = (budget) => {

  const start = new Date(
    `${formatInputDate(budget.tanggal_mulai)}T00:00:00`
  )

  const end = new Date(
    `${formatInputDate(budget.tanggal_selesai)}T23:59:59`
  )

  return transactions.value
    .filter(transaction => {

      if (transaction.jenis !== 'pengeluaran') {
        return false
      }

      if (
        Number(transaction.id_kategori) !==
        Number(budget.id_kategori)
      ) {
        return false
      }

      const transactionDate =
        new Date(
          `${formatInputDate(transaction.tanggal)}T00:00:00`
        )

      return (
        transactionDate >= start &&
        transactionDate <= end
      )

    })
    .reduce(
      (total, transaction) =>
        total + Number(transaction.jumlah || 0),
      0
    )
}


/* =========================
   NORMALIZE BUDGETS
========================= */

const normalizedBudgets = computed(() => {

  return budgets.value.map(budget => {

    const spent = calculateSpent(budget)

    const limit = Number(budget.jumlah || 0)

    const remaining = limit - spent

    const percent = limit > 0
      ? Math.round((spent / limit) * 100)
      : 0

    return {
      id: budget.id_anggaran,

      categoryId: budget.id_kategori,

      name: budget.nama_anggaran,

      categoryName:
        budget.kategori?.nama_kategori ||
        'Uncategorized',

      limit,
      spent,
      remaining,

      percent,

      hasSavingsTransfers:
        Boolean(budget.has_savings_transfers),

      savingsContributed:
        Number(budget.savings_contributed || 0),

      startDate: budget.tanggal_mulai,
      endDate: budget.tanggal_selesai,

      icon:
        budget.kategori?.ikon ||
        '💜'
    }

  })

})


/* =========================
   TOTALS
========================= */

const totalLimit = computed(() => {

  return normalizedBudgets.value.reduce(
    (total, budget) =>
      total + budget.limit,
    0
  )

})


const totalSpent = computed(() => {

  return normalizedBudgets.value.reduce(
    (total, budget) =>
      total + budget.spent,
    0
  )

})


const totalPercent = computed(() => {

  if (!totalLimit.value) {
    return 0
  }

  return Math.round(
    (totalSpent.value / totalLimit.value) * 100
  )

})


/* =========================
   LOAD DATA
========================= */

const loadData = async () => {

  loading.value = true

  try {

    const [
      budgetResponse,
      categoryResponse,
      transactionResponse
    ] = await Promise.all([
      api.get('/anggaran'),
      api.get('/kategori'),
      api.get('/transaksi')
    ])


    budgets.value =
      budgetResponse.data?.data || []


    categories.value =
      categoryResponse.data?.data || []


    transactions.value =
      transactionResponse.data?.data || []

  } catch (error) {

    console.error(
      'Failed to load budget data:',
      error
    )

    if (error.response?.status === 401) {
      router.push('/login')
      return
    }

  } finally {

    loading.value = false

  }

}


/* =========================
   RESET FORM
========================= */

const resetForm = () => {

  form.value = {
    id_kategori:
      expenseCategories.value[0]?.id_kategori || '',

    nama_anggaran: '',

    jumlah: '',

    tanggal_mulai:
      getToday(),

    tanggal_selesai:
      getMonthEnd()
  }

}


/* =========================
   DATE HELPERS
========================= */

const getToday = () => {

  const date = new Date()

  return date.toISOString().split('T')[0]

}


const getMonthEnd = () => {

  const date = new Date()

  date.setMonth(
    date.getMonth() + 1,
    0
  )

  return date.toISOString().split('T')[0]

}


const formatInputDate = (date) => {

  if (!date) {
    return ''
  }

  return String(date).split('T')[0]

}


/* =========================
   OPEN ADD MODAL
========================= */

const openAddModal = () => {

  editingBudget.value = null

  errorMessage.value = ''

  resetForm()

  showModal.value = true

  openMenu.value = null

}


/* =========================
   OPEN EDIT MODAL
========================= */

const openEditModal = (budget) => {

  if (budget.hasSavingsTransfers) {
    showEditBlocked(budget)
    return
  }

  const original = budgets.value.find(
    item =>
      Number(item.id_anggaran) ===
      Number(budget.id)
  )

  if (!original) {
    return
  }

  editingBudget.value = original

  errorMessage.value = ''

  form.value = {
    id_kategori:
      original.id_kategori,

    nama_anggaran:
      original.nama_anggaran,

    jumlah:
      original.jumlah,

    tanggal_mulai:
      formatInputDate(
        original.tanggal_mulai
      ),

    tanggal_selesai:
      formatInputDate(
        original.tanggal_selesai
      )
  }

  showModal.value = true

  openMenu.value = null

}


/* =========================
   SAVE BUDGET
========================= */

const saveBudget = async () => {

  errorMessage.value = ''

  if (
    !form.value.id_kategori ||
    !form.value.nama_anggaran ||
    !form.value.jumlah ||
    !form.value.tanggal_mulai ||
    !form.value.tanggal_selesai
  ) {
    errorMessage.value =
      'Please fill in all fields.'

    return
  }


  if (
    form.value.tanggal_selesai <
    form.value.tanggal_mulai
  ) {
    errorMessage.value =
      'End date must be on or after the start date.'

    return
  }


  saving.value = true

  try {

    const payload = {
      id_kategori:
        Number(form.value.id_kategori),

      nama_anggaran:
        form.value.nama_anggaran,

      jumlah:
        Number(form.value.jumlah),

      tanggal_mulai:
        form.value.tanggal_mulai,

      tanggal_selesai:
        form.value.tanggal_selesai
    }


    let response


    if (editingBudget.value) {

      response = await api.put(
        `/anggaran/${editingBudget.value.id_anggaran}`,
        payload
      )

    } else {

      response = await api.post(
        '/anggaran',
        payload
      )

    }


    const savedBudget =
      response.data?.data


    if (!savedBudget) {
      throw new Error('Budget response was empty.')
    }


    if (editingBudget.value) {

      const index =
        budgets.value.findIndex(
          budget =>
            Number(budget.id_anggaran) ===
            Number(savedBudget.id_anggaran)
        )

      if (index !== -1) {
        budgets.value[index] =
          savedBudget
      }

    } else {

      budgets.value.unshift(
        savedBudget
      )

    }


    closeModal()

  } catch (error) {

    console.error(
      'Failed to save budget:',
      error
    )

    if (error.response?.status === 401) {

      router.push('/login')

      return

    }


    if (error.response?.data?.errors) {

      const errors =
        error.response.data.errors

      const firstError =
        Object.values(errors)?.[0]?.[0]

      errorMessage.value =
        firstError ||
        'Please check your input.'

    } else {

      errorMessage.value =
        error.response?.data?.message ||
        error.message ||
        'Failed to save budget.'

    }

  } finally {

    saving.value = false

  }

}


/* =========================
   SAVINGS / BUDGET PROTECTION
========================= */

const showEditBlocked = (budget) => {
  const contributed = formatNumber(
    budget.savingsContributed || 0
  )

  window.alert(
    `This budget cannot be edited because $${contributed} has already been moved to savings. ` +
    `Undo the savings contribution first.`
  )
}


const showDeleteBlocked = (budget) => {
  const contributed = formatNumber(
    budget.savingsContributed || 0
  )

  window.alert(
    `This budget cannot be deleted because $${contributed} has already been moved to savings. ` +
    `Undo the savings contribution first.`
  )
}


const undoSavingsContributionsForBudget = async (budget) => {
  const confirmed = window.confirm(
    `Undo savings contributions from "${budget.name}"?\n\n` +
    `The money will return to the wallet and the savings goal(s) will be reduced.`
  )

  if (!confirmed) {
    return
  }

  try {
    /*
     * The budget page already loads all transactions, so we can
     * find the savings-transfer transactions without another GET.
     */
    const savingsTransactions =
      transactions.value.filter((transaction) => {

        if (transaction.jenis !== 'pengeluaran') {
          return false
        }

        const description =
          String(transaction.deskripsi || '')

        return description.includes(
          `[SAVINGS_TRANSFER budget_id:${budget.id}`
        )
      })

    if (savingsTransactions.length === 0) {
      window.alert(
        'No savings contribution was found for this budget.'
      )

      await loadData()

      return
    }

    /*
     * Undo every contribution attached to this budget.
     */
    for (const transaction of savingsTransactions) {

      const match =
        String(transaction.deskripsi || '')
          .match(/goal_id:(\d+)/)

      if (!match) {
        throw new Error(
          `Could not identify the savings goal for transaction ${transaction.id_transaksi}.`
        )
      }

      const goalId = Number(match[1])

      await api.post(
        `/tujuan-tabungan/${goalId}/undo-budget-saving`,
        {
          id_transaksi:
            transaction.id_transaksi
        }
      )
    }

    await loadData()

    openMenu.value = null

    window.alert(
      'Savings contribution(s) undone. The budget can now be edited or deleted.'
    )

  } catch (error) {

    console.error(
      'Failed to undo savings contributions:',
      error
    )

    if (error.response?.status === 401) {
      router.push('/login')
      return
    }

    window.alert(
      error.response?.data?.message ||
      error.message ||
      'Failed to undo savings contributions.'
    )
  }
}


/* =========================
   DELETE BUDGET
========================= */

const deleteBudget = async (budget) => {

  if (budget.hasSavingsTransfers) {
    showDeleteBlocked(budget)
    return
  }

  const confirmed =
    window.confirm(
      `Delete "${budget.name}"?`
    )

  if (!confirmed) {
    return
  }


  try {

    await api.delete(
      `/anggaran/${budget.id}`
    )


    budgets.value =
      budgets.value.filter(
        item =>
          Number(item.id_anggaran) !==
          Number(budget.id)
      )


    openMenu.value = null

  } catch (error) {

    console.error(
      'Failed to delete budget:',
      error
    )

    if (error.response?.status === 401) {

      router.push('/login')

      return

    }

    alert(
      error.response?.data?.message ||
      'Failed to delete budget.'
    )

  }

}


/* =========================
   MENU
========================= */

const toggleMenu = (id) => {

  openMenu.value =
    openMenu.value === id
      ? null
      : id

}


/* =========================
   CLOSE MODAL
========================= */

const closeModal = () => {

  showModal.value = false

  editingBudget.value = null

  errorMessage.value = ''

}


/* =========================
   FORMATTING
========================= */

const formatNumber = (value) => {

  return Number(value || 0)
    .toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })

}


const formatDate = (date) => {

  if (!date) {
    return ''
  }

  const cleanDate =
    formatInputDate(date)

  const parts =
    cleanDate.split('-')

  if (parts.length !== 3) {
    return cleanDate
  }

  return `${parts[1]}/${parts[2]}/${parts[0]}`
}


/* =========================
   INITIAL LOAD
========================= */

onMounted(() => {
  loadData()
})

</script>


<style scoped>

* {
  box-sizing: border-box;
}

.page {
  padding: 32px 42px 50px;
  color: #172033;
}


/* =========================
   HEADER
========================= */

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
}

.eyebrow {
  display: block;
  margin-bottom: 5px;
  color: #969caf;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 1.3px;
}

.page-header h1 {
  margin: 0 0 6px;
  font-size: 28px;
  letter-spacing: -.7px;
}

.page-header p {
  margin: 0;
  color: #9299aa;
  font-size: 13px;
}


/* =========================
   BUTTON
========================= */

.primary-button {
  height: 44px;

  display: flex;
  align-items: center;
  justify-content: center;

  gap: 8px;

  padding: 0 20px;

  border: 0;
  border-radius: 9px;

  background: #6655e9;

  color: white;

  font-size: 12px;
  font-weight: 700;

  cursor: pointer;

  box-shadow:
    0 8px 20px rgba(102, 85, 233, .2);

  transition: .2s ease;
}

.primary-button:hover {
  background: #5746dc;
  transform: translateY(-1px);
}


/* =========================
   OVERVIEW
========================= */

.overview-card {
  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 28px;

  margin-bottom: 32px;

  border: 1px solid #e8eaf0;
  border-radius: 14px;

  background: white;
}

.overview-card span {
  color: #9299aa;
  font-size: 11px;
}

.overview-card strong {
  display: block;
  margin: 8px 0;
  font-size: 28px;
}

.overview-card p {
  margin: 0;
  color: #a0a6b5;
  font-size: 11px;
}

.budget-progress {
  width: 45%;
}

.progress-track {
  width: 100%;
  height: 7px;

  overflow: hidden;

  border-radius: 10px;

  background: #eceef3;
}

.progress-fill {
  height: 100%;

  border-radius: inherit;

  background: #6655e9;

  transition: width .3s ease;
}

.progress-fill.warning {
  background: #f59e0b;
}

.progress-fill.danger {
  background: #ef4444;
}

.budget-progress > span {
  display: block;

  margin-top: 8px;

  text-align: right;

  color: #9299aa;
}


/* =========================
   SECTION
========================= */

.section-header {
  margin-bottom: 18px;
}

.section-header h2 {
  margin: 0 0 5px;
  font-size: 17px;
}

.section-header p {
  margin: 0;
  color: #969cab;
  font-size: 11px;
}


/* =========================
   EMPTY
========================= */

.empty-state {
  min-height: 280px;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  padding: 40px 20px;

  border: 1px solid #e8eaf0;
  border-radius: 14px;

  background: white;

  text-align: center;

  color: #9aa1b2;
}

.empty-icon {
  width: 48px;
  height: 48px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 12px;

  border-radius: 12px;

  background: #f0edff;

  font-size: 20px;
}

.empty-state strong {
  margin-bottom: 5px;
  color: #667085;
  font-size: 13px;
}

.empty-state span {
  max-width: 300px;

  color: #9aa1b2;

  font-size: 10px;
  line-height: 1.5;
}

.empty-add-button {
  margin-top: 18px;

  padding: 10px 16px;

  border: 0;
  border-radius: 9px;

  background: #6655e9;

  color: white;

  font-size: 12px;
  font-weight: 700;

  cursor: pointer;
}

.empty-add-button:hover {
  background: #5746dc;
}


/* =========================
   GRID
========================= */

.budget-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 18px;
}


/* =========================
   CARD
========================= */

.budget-card {
  position: relative;

  padding: 22px;

  border: 1px solid #e8eaf0;

  border-radius: 13px;

  background: white;

  transition: .2s ease;
}

.budget-card:hover {
  border-color: #ddd9fb;

  box-shadow:
    0 8px 25px rgba(31, 35, 50, .05);
}

.budget-top {
  position: relative;

  display: flex;
  justify-content: space-between;

  margin-bottom: 18px;
}

.category-icon {
  width: 42px;
  height: 42px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 10px;

  background: #f0edff;

  font-size: 18px;
}

.more-button {
  width: 30px;
  height: 30px;

  border: 0;

  border-radius: 7px;

  background: transparent;

  color: #9ca3b3;

  font-size: 18px;

  cursor: pointer;
}

.more-button:hover {
  background: #f4f2ff;
  color: #6655e9;
}


/* =========================
   MENU
========================= */

.budget-menu {
  position: absolute;

  top: 35px;
  right: 0;

  z-index: 10;

  min-width: 110px;

  padding: 5px;

  border: 1px solid #e8eaf0;

  border-radius: 9px;

  background: white;

  box-shadow:
    0 10px 25px rgba(31, 35, 50, .12);
}

.budget-menu button {
  width: 100%;

  padding: 8px 10px;

  border: 0;

  border-radius: 6px;

  background: transparent;

  text-align: left;

  color: #4b5563;

  font-size: 12px;

  cursor: pointer;
}

.budget-menu button:hover {
  background: #f5f4ff;
}

.budget-menu .delete-option {
  color: #ef4444;
}

.budget-menu .disabled-option {
  color: #9ca3af;
  cursor: not-allowed;
}

.budget-menu .disabled-option:hover {
  background: #fafafa;
}

.budget-menu .undo-option {
  color: #6655e9;
}

.budget-menu .undo-option:hover {
  background: #f0edff;
}


/* =========================
   CARD CONTENT
========================= */

.budget-card h3 {
  margin: 0 0 5px;
  font-size: 13px;
}

.budget-category {
  margin-bottom: 12px;

  color: #9299aa;

  font-size: 10px;
}

.budget-money {
  margin-bottom: 12px;
}

.budget-money strong {
  font-size: 19px;
}

.budget-money span {
  color: #9ba2b1;
  font-size: 11px;
}

.budget-footer {
  display: flex;
  justify-content: space-between;

  margin-top: 9px;

  color: #969cab;

  font-size: 10px;
}

.budget-footer .negative {
  color: #ef4444;
}

.budget-dates {
  margin-top: 14px;

  padding-top: 12px;

  border-top: 1px solid #f0f1f4;

  color: #a0a6b5;

  font-size: 9px;
}


/* =========================
   MODAL
========================= */

.modal-overlay {
  position: fixed;

  inset: 0;

  z-index: 100;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 20px;

  background: rgba(20, 24, 38, .45);

  backdrop-filter: blur(3px);
}

.modal {
  width: 100%;
  max-width: 500px;

  max-height: 90vh;

  overflow-y: auto;

  padding: 26px;

  border-radius: 15px;

  background: white;

  box-shadow:
    0 25px 60px rgba(0, 0, 0, .18);
}

.modal-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;

  margin-bottom: 24px;
}

.modal-header h2 {
  margin: 0;
  font-size: 20px;
}

.close-button {
  width: 32px;
  height: 32px;

  border: 0;

  border-radius: 8px;

  background: #f4f5f7;

  color: #6b7280;

  font-size: 20px;

  cursor: pointer;
}

.close-button:hover {
  background: #eceef2;
}


/* =========================
   FORM
========================= */

.form-group {
  margin-bottom: 18px;
}

.form-group label {
  display: block;

  margin-bottom: 7px;

  color: #374151;

  font-size: 11px;

  font-weight: 700;
}

.form-group input,
.form-group select {
  width: 100%;

  height: 42px;

  padding: 0 12px;

  border: 1px solid #e2e5eb;

  border-radius: 9px;

  outline: none;

  background: white;

  color: #172033;

  font-size: 12px;

  transition: .2s ease;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #6655e9;

  box-shadow:
    0 0 0 3px rgba(102, 85, 233, .08);
}

.form-group small {
  display: block;

  margin-top: 6px;

  color: #ef4444;

  font-size: 10px;
}

.amount-input {
  position: relative;
}

.amount-input > span {
  position: absolute;

  left: 13px;
  top: 50%;

  transform: translateY(-50%);

  color: #8d94a4;

  font-size: 12px;
}

.amount-input input {
  padding-left: 28px;
}

.date-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}


/* =========================
   FORM ERROR
========================= */

.form-error {
  margin-bottom: 16px;

  padding: 10px 12px;

  border-radius: 8px;

  background: #fef2f2;

  color: #dc2626;

  font-size: 11px;
}


/* =========================
   MODAL ACTIONS
========================= */

.modal-actions {
  display: flex;
  justify-content: flex-end;

  gap: 10px;

  margin-top: 24px;
}

.cancel-button,
.save-button {
  height: 40px;

  padding: 0 18px;

  border-radius: 9px;

  font-size: 12px;

  font-weight: 700;

  cursor: pointer;
}

.cancel-button {
  border: 1px solid #e1e4ea;

  background: white;

  color: #667085;
}

.cancel-button:hover {
  background: #f8f9fb;
}

.save-button {
  border: 0;

  background: #6655e9;

  color: white;
}

.save-button:hover {
  background: #5746dc;
}

.save-button:disabled {
  opacity: .6;
  cursor: not-allowed;
}


/* =========================
   MOBILE
========================= */

@media (max-width: 700px) {

  .page {
    padding: 22px 16px 35px;
  }

  .page-header {
    align-items: flex-start;
    flex-direction: column;
    gap: 18px;
  }

  .primary-button {
    width: 100%;
  }

  .overview-card {
    align-items: flex-start;
    flex-direction: column;
    gap: 22px;
  }

  .budget-progress {
    width: 100%;
  }

  .budget-grid {
    grid-template-columns: 1fr;
  }

  .date-grid {
    grid-template-columns: 1fr;
  }

  .modal {
    padding: 20px;
  }

}
/* =========================
   BUDGET LOADING SKELETON
========================= */

.budget-skeleton {
  width: 100%;
}


/* =========================
   SHIMMER
========================= */

.budget-skeleton .skeleton-line,
.budget-skeleton .skeleton-button,
.budget-skeleton .skeleton-category-icon,
.budget-skeleton .skeleton-more,
.budget-skeleton .skeleton-progress {
  position: relative;
  overflow: hidden;

  background: #eceef3;
}

.budget-skeleton .skeleton-line::after,
.budget-skeleton .skeleton-button::after,
.budget-skeleton .skeleton-category-icon::after,
.budget-skeleton .skeleton-more::after,
.budget-skeleton .skeleton-progress::after {
  content: "";

  position: absolute;

  inset: 0;

  transform: translateX(-100%);

  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, .65),
    transparent
  );

  animation: budgetSkeletonShimmer 1.4s infinite;
}

@keyframes budgetSkeletonShimmer {

  100% {
    transform: translateX(100%);
  }

}


/* =========================
   HEADER
========================= */

.skeleton-header {
  display: flex;
  align-items: center;
  justify-content: space-between;

  margin-bottom: 28px;
}

.skeleton-header-text {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.skeleton-eyebrow {
  width: 90px;
  height: 9px;

  border-radius: 4px;
}

.skeleton-title {
  width: 130px;
  height: 29px;

  border-radius: 7px;
}

.skeleton-subtitle {
  width: 210px;
  height: 12px;

  border-radius: 5px;
}

.skeleton-button {
  width: 125px;
  height: 44px;

  border-radius: 9px;
}


/* =========================
   OVERVIEW
========================= */

.skeleton-overview {
  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 28px;

  margin-bottom: 32px;

  border: 1px solid #e8eaf0;
  border-radius: 14px;

  background: white;
}

.skeleton-overview-info {
  display: flex;
  flex-direction: column;
  gap: 9px;
}

.skeleton-small {
  width: 125px;
  height: 11px;

  border-radius: 5px;
}

.skeleton-total {
  width: 145px;
  height: 29px;

  border-radius: 7px;
}

.skeleton-description {
  width: 180px;
  height: 10px;

  border-radius: 5px;
}

.skeleton-overview-progress {
  width: 45%;
}

.skeleton-progress {
  width: 100%;
  height: 7px;

  overflow: hidden;

  border-radius: 10px;
}

.skeleton-percent {
  width: 65px;
  height: 10px;

  margin-top: 8px;
  margin-left: auto;

  border-radius: 5px;
}


/* =========================
   SECTION HEADER
========================= */

.skeleton-section-header {
  display: flex;
  flex-direction: column;
  gap: 8px;

  margin-bottom: 18px;
}

.skeleton-section-title {
  width: 120px;
  height: 18px;

  border-radius: 5px;
}

.skeleton-section-subtitle {
  width: 175px;
  height: 10px;

  border-radius: 5px;
}


/* =========================
   BUDGET GRID
========================= */

.skeleton-budget-grid {
  display: grid;

  grid-template-columns: repeat(3, 1fr);

  gap: 18px;
}


/* =========================
   BUDGET CARD
========================= */

.skeleton-budget-card {
  padding: 22px;

  border: 1px solid #e8eaf0;

  border-radius: 13px;

  background: white;
}

.skeleton-budget-top {
  display: flex;
  align-items: center;
  justify-content: space-between;

  margin-bottom: 18px;
}

.skeleton-category-icon {
  width: 42px;
  height: 42px;

  border-radius: 10px;
}

.skeleton-more {
  width: 30px;
  height: 30px;

  border-radius: 7px;
}


/* =========================
   CARD CONTENT
========================= */

.skeleton-budget-name {
  width: 115px;
  height: 13px;

  margin-bottom: 7px;

  border-radius: 5px;
}

.skeleton-budget-category {
  width: 80px;
  height: 9px;

  margin-bottom: 14px;

  border-radius: 4px;
}

.skeleton-budget-money {
  display: flex;
  align-items: baseline;
  gap: 7px;

  margin-bottom: 12px;
}

.skeleton-spent {
  width: 80px;
  height: 19px;

  border-radius: 5px;
}

.skeleton-limit {
  width: 65px;
  height: 10px;

  border-radius: 4px;
}

.skeleton-budget-card > .skeleton-progress {
  margin-bottom: 0;
}


/* =========================
   FOOTER
========================= */

.skeleton-budget-footer {
  display: flex;
  justify-content: space-between;

  margin-top: 9px;
}

.skeleton-footer-left {
  width: 55px;
  height: 9px;

  border-radius: 4px;
}

.skeleton-footer-right {
  width: 70px;
  height: 9px;

  border-radius: 4px;
}

.skeleton-dates {
  width: 145px;
  height: 9px;

  margin-top: 18px;
  padding-top: 0;

  border-radius: 4px;
}


/* =========================
   MOBILE
========================= */

@media (max-width: 700px) {

  .skeleton-header {
    align-items: flex-start;
    flex-direction: column;

    gap: 18px;
  }

  .skeleton-button {
    width: 100%;
  }

  .skeleton-overview {
    align-items: flex-start;
    flex-direction: column;

    gap: 22px;
  }

  .skeleton-overview-progress {
    width: 100%;
  }

  .skeleton-budget-grid {
    grid-template-columns: 1fr;
  }

}

</style>
