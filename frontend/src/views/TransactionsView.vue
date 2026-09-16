<template>
  <div class="transactions-page">

    <!-- =========================
         LOADING SKELETON
    ========================== -->
    <div v-if="loading" class="transactions-skeleton">

      <!-- Skeleton header -->
      <div class="skeleton-header">
        <div>
          <div class="skeleton skeleton-eyebrow"></div>
          <div class="skeleton skeleton-title"></div>
          <div class="skeleton skeleton-description"></div>
        </div>

        <div class="skeleton-header-actions">
          <div class="skeleton skeleton-button"></div>
          <div class="skeleton skeleton-button"></div>
        </div>
      </div>

      <!-- Skeleton summary cards -->
      <div class="summary-grid">
        <div
          v-for="i in 3"
          :key="'summary-skeleton-' + i"
          class="summary-card skeleton-card"
        >
          <div class="skeleton skeleton-summary-icon"></div>
          <div class="skeleton-summary-text">
            <div class="skeleton skeleton-label"></div>
            <div class="skeleton skeleton-value"></div>
          </div>
        </div>
      </div>

      <!-- Skeleton categories -->
      <section class="category-card skeleton-card">
        <div class="section-header">
          <div>
            <div class="skeleton skeleton-section-title"></div>
            <div class="skeleton skeleton-section-description"></div>
          </div>
        </div>

        <div class="category-list">
          <div
            v-for="i in 5"
            :key="'category-skeleton-' + i"
            class="category-row skeleton-category-row"
          >
            <div class="skeleton skeleton-category-icon"></div>

            <div class="category-main">
              <div class="skeleton skeleton-category-name"></div>
              <div class="skeleton skeleton-progress"></div>
            </div>

            <div class="skeleton skeleton-category-total"></div>
            <div class="skeleton skeleton-category-arrow"></div>
          </div>
        </div>
      </section>

      <!-- Skeleton transactions -->
      <section class="transactions-card skeleton-card">
        <div class="transactions-header">
          <div>
            <div class="skeleton skeleton-section-title"></div>
            <div class="skeleton skeleton-section-description"></div>
          </div>

          <div class="filters skeleton-filters">
            <div class="skeleton skeleton-filter"></div>
            <div class="skeleton skeleton-filter"></div>
            <div class="skeleton skeleton-filter"></div>
          </div>
        </div>

        <div class="transaction-list">
          <div
            v-for="i in 6"
            :key="'transaction-skeleton-' + i"
            class="transaction-row skeleton-transaction-row"
          >
            <div class="skeleton skeleton-transaction-icon"></div>

            <div class="transaction-info">
              <div class="skeleton skeleton-transaction-title"></div>
              <div class="skeleton skeleton-transaction-category"></div>
            </div>

            <div class="skeleton skeleton-transaction-wallet"></div>
            <div class="skeleton skeleton-transaction-date"></div>
            <div class="skeleton skeleton-transaction-amount"></div>
            <div class="skeleton skeleton-delete"></div>
          </div>
        </div>
      </section>
    </div>

    <!-- =========================
         ACTUAL PAGE
    ========================== -->
    <div v-else>

    <!-- HEADER -->
    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>

        <h1>Transactions</h1>

        <p>
          Track and manage all your income and expenses.
        </p>
      </div>

        <div class="header-actions">

          <button
            type="button"
            class="header-button"
            @click="router.push('/categories')"
          >
            <span>📂</span>
            Categories
          </button>

          <button
            type="button"
            class="header-button"
            @click="openModal"
          >
            <span>+</span>
            Add Transaction
          </button>

        </div>
    </div>


    <!-- SUMMARY -->
    <div class="summary-grid">

      <div class="summary-card">
        <div class="summary-icon balance-icon">$</div>
        <div>
          <span>Total Balance</span>
          <strong>{{ money(totalBalance) }}</strong>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon income-icon">↗</div>
        <div>
          <span>Total Income</span>
          <strong>{{ money(totalIncome) }}</strong>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon expense-icon">↘</div>
        <div>
          <span>Total Expenses</span>
          <strong>{{ money(totalExpenses) }}</strong>
        </div>
      </div>

    </div>


    <!-- SPENDING BY CATEGORY -->
    <section class="category-card">

      <div class="section-header">
        <div>
          <h2>Spending by Category</h2>
          <p>{{ categoryPeriodText }}</p>
        </div>

        <button
          v-if="selectedCategory !== 'all'"
          class="clear-filter-btn"
          @click="clearCategory"
        >
          Clear filter
        </button>
      </div>


      <!-- EMPTY CATEGORY STATE -->
      <div
        v-if="categoryExpenses.length === 0"
        class="category-empty"
      >
        <div class="empty-circle">◎</div>
        <strong>No spending yet</strong>
        <span>
          Your expense categories will appear here.
        </span>
      </div>


      <!-- CATEGORY LIST -->
      <div v-else class="category-list">

        <button
          v-for="category in visibleCategories"
          :key="category.id"
          class="category-row"
          :class="{
            selected: selectedCategory === category.id
          }"
          @click="selectCategory(category.id)"
        >

          <div class="category-icon">
            {{ category.icon }}
          </div>

          <div class="category-main">

            <div class="category-name-row">
              <strong>{{ category.name }}</strong>

              <span>
                {{ category.count }}
                {{ category.count === 1 ? 'transaction' : 'transactions' }}
              </span>
            </div>

            <div class="progress-track">
              <div
                class="progress-fill"
                :style="{ width: category.percentage + '%' }"
              ></div>
            </div>

          </div>

          <div class="category-total">
            <strong>{{ money(category.amount) }}</strong>
            <span>{{ category.percentage }}%</span>
          </div>

          <div class="category-arrow">
            →
          </div>

        </button>


        <button
          v-if="categoryExpenses.length > categoryLimit"
          class="view-all-btn"
          @click="showAllCategories = !showAllCategories"
        >
          {{
            showAllCategories
              ? 'Show less'
              : `View all ${categoryExpenses.length} categories`
          }}

          <span>
            {{ showAllCategories ? '↑' : '↓' }}
          </span>
        </button>

      </div>

    </section>


    <!-- TRANSACTIONS -->
    <section class="transactions-card">

      <div class="transactions-header">

        <div>
          <h2>All Transactions</h2>

          <p>
            {{
              selectedCategory !== 'all'
                ? `Showing ${selectedCategoryName} transactions`
                : 'Your recent financial activity'
            }}
          </p>
        </div>


        <div class="filters">

          <select v-model="filter">
            <option value="all">All transactions</option>
            <option value="income">Income</option>
            <option value="expense">Expenses</option>
          </select>

          <select v-model="period">
            <option value="all">All time</option>
            <option value="month">This Month</option>
            <option value="lastMonth">Last Month</option>
            <option value="year">This Year</option>
          </select>

          <select v-model="selectedCategory">
            <option value="all">All categories</option>

            <option
              v-for="category in categoryOptions"
              :key="category.id"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>

        </div>

      </div>


      <!-- TRANSACTION TABLE -->
      <div
        v-if="filteredTransactions.length"
        class="transaction-list"
      >

        <div
          v-for="transaction in filteredTransactions"
          :key="transaction.id_transaksi"
          class="transaction-row"
        >

          <!-- ICON -->
          <div
            class="transaction-icon"
            :class="transaction.type"
          >
            {{ transaction.icon }}
          </div>


          <!-- NAME -->
          <div class="transaction-info">

            <strong>
              {{ transaction.judul }}
            </strong>

            <span>
              {{ transaction.category }}
            </span>

          </div>


          <!-- WALLET -->
          <div class="transaction-wallet">
            {{ transaction.wallet }}
          </div>


          <!-- DATE -->
          <div class="transaction-date">
            {{ transaction.date }}
          </div>


          <!-- AMOUNT -->
          <div
            class="transaction-amount"
            :class="transaction.type"
          >
            {{ transaction.type === 'income' ? '+' : '-' }}
            {{ money(transaction.jumlah) }}
          </div>


          <!-- DELETE -->
          <button
            class="delete-btn"
            title="Delete transaction"
            @click="deleteTransaction(transaction.id_transaksi)"
          >
            ×
          </button>

        </div>

      </div>


      <!-- EMPTY -->
      <div
        v-else
        class="empty-state"
      >
        <div class="empty-circle">↗</div>

        <strong>
          {{
            transactions.length
              ? 'No transactions found'
              : 'No transactions yet'
          }}
        </strong>

        <span>
          {{
            transactions.length
              ? 'Try changing your filters.'
              : 'Your income and expenses will appear here.'
          }}
        </span>
      </div>

    </section>


    <!-- ADD TRANSACTION MODAL -->
    <div
      v-if="showModal"
      class="modal-overlay"
      @click.self="closeModal"
    >

      <div class="modal">

        <button
          class="modal-close"
          @click="closeModal"
        >
          ×
        </button>

        <h2>Add Transaction</h2>

        <p class="modal-description">
          Add a new income or expense.
        </p>


        <div
          v-if="formError"
          class="form-error"
        >
          {{ formError }}
        </div>


        <!-- TITLE -->
        <div class="form-group">
          <label>Transaction name</label>

          <input
            v-model="newTransaction.judul"
            type="text"
            placeholder="Example: Lunch"
          />
        </div>


        <!-- AMOUNT / TYPE -->
        <div class="form-row">

          <div class="form-group">
            <label>Amount</label>

            <input
              v-model="newTransaction.jumlah"
              type="number"
              min="0.01"
              step="0.01"
              placeholder="0.00"
            />
          </div>


          <div class="form-group">
            <label>Type</label>

            <select v-model="newTransaction.jenis">
              <option value="pengeluaran">
                Expense
              </option>

              <option value="pemasukan">
                Income
              </option>
            </select>
          </div>

        </div>


        <!-- WALLET / CATEGORY -->
        <div class="form-row">

          <div class="form-group">
            <label>Wallet</label>

            <select v-model="newTransaction.id_dompet">

              <option value="" disabled>
                Select wallet
              </option>

              <option
                v-for="wallet in wallets"
                :key="wallet.id_dompet"
                :value="wallet.id_dompet"
              >
                {{ wallet.nama_dompet }}
              </option>

            </select>
          </div>


          <div class="form-group">
            <label>Category</label>

            <select v-model="newTransaction.id_kategori">

              <option value="" disabled>
                Select category
              </option>

              <option
                v-for="category in availableCategories"
                :key="category.id_kategori"
                :value="category.id_kategori"
              >
                {{ category.ikon || '•' }}
                {{ category.nama_kategori }}
              </option>

            </select>
          </div>

        </div>


        <!-- DATE -->
        <div class="form-group">
          <label>Date</label>

          <input
            v-model="newTransaction.tanggal"
            type="date"
          />
        </div>


        <!-- DESCRIPTION -->
        <div class="form-group">
          <label>
            Description
            <span class="optional">(optional)</span>
          </label>

          <textarea
            v-model="newTransaction.deskripsi"
            rows="3"
            placeholder="Add a note..."
          ></textarea>
        </div>


        <!-- ACTIONS -->
        <div class="modal-actions">

          <button
            class="cancel-btn"
            @click="closeModal"
          >
            Cancel
          </button>

          <button
            class="save-btn"
            :disabled="saving"
            @click="addTransaction"
          >
            {{ saving ? 'Saving...' : 'Add Transaction' }}
          </button>

        </div>

      </div>

    </div>

    </div>
  </div>
</template>


<script setup>

import {
  computed,
  onMounted,
  ref,
  watch
} from 'vue'

import {
  useRouter
} from 'vue-router'

import api from '../utils/api.js'


const router = useRouter()


/* =========================
   STATE
========================= */

const transactions = ref([])

const wallets = ref([])

const categories = ref([])

const loading = ref(false)

const saving = ref(false)

const showModal = ref(false)

const formError = ref('')


/* =========================
   FILTERS
========================= */

const filter = ref('all')

const period = ref('all')

const selectedCategory = ref('all')

const showAllCategories = ref(false)

const categoryLimit = 6


/* =========================
   DATE
========================= */

const today = new Date()
  .toISOString()
  .slice(0, 10)


/* =========================
   FORM
========================= */

const newTransaction = ref({

  id_dompet: '',

  id_kategori: '',

  jenis: 'pengeluaran',

  jumlah: '',

  judul: '',

  deskripsi: '',

  tanggal: today

})


/* =========================
   HELPERS
========================= */

const isUnauthorized = (error) => {
  return error?.response?.status === 401
}


const money = (value) => {

  return '$' + Number(value || 0)
    .toLocaleString('en-US', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    })

}


const formatDate = (value) => {

  if (!value) {
    return '—'
  }

  const date = new Date(value)

  if (Number.isNaN(date.getTime())) {
    return value
  }

  return date.toLocaleDateString(
    'en-US',
    {
      month: 'short',
      day: 'numeric',
      year: 'numeric'
    }
  )

}


/* =========================
   LOAD DATA
========================= */

const loadData = async () => {

  loading.value = true

  try {

    const [
      transactionResponse,
      walletResponse,
      categoryResponse
    ] = await Promise.all([
      api.get('/transaksi'),
      api.get('/dompet'),
      api.get('/kategori')
    ])


    transactions.value =
      Array.isArray(transactionResponse.data?.data)
        ? transactionResponse.data.data
        : []


    wallets.value =
      Array.isArray(walletResponse.data?.data)
        ? walletResponse.data.data
        : []


    categories.value =
      Array.isArray(categoryResponse.data?.data)
        ? categoryResponse.data.data
        : []

  } catch (error) {

    if (isUnauthorized(error)) {
      router.push('/login')
      return
    }

    console.error(
      'Failed to load transaction data:',
      error
    )

  } finally {

    loading.value = false

  }

}


/* =========================
   TRANSACTION NORMALIZATION
========================= */

const normalizedTransactions = computed(() => {

  return transactions.value.map(
    transaction => {

      const category =
        transaction.kategori

      const wallet =
        wallets.value.find(
          wallet =>
            Number(wallet.id_dompet) ===
            Number(transaction.id_dompet)
        )


      const isIncome =
        transaction.jenis === 'pemasukan'


      return {

        ...transaction,

        type:
          isIncome
            ? 'income'
            : 'expense',

        category:
          category?.nama_kategori ||
          'Uncategorized',

        categoryId:
          category?.id_kategori ??
          transaction.id_kategori,

        wallet:
          wallet?.nama_dompet ||
          'Unknown wallet',

        date:
          formatDate(transaction.tanggal),

        icon:
          category?.ikon ||
          (isIncome ? '💰' : '💳')

      }

    }
  )

})


/* =========================
   TOTALS
========================= */

const totalIncome = computed(() => {

  return normalizedTransactions.value
    .filter(
      transaction =>
        transaction.type === 'income'
    )
    .reduce(
      (total, transaction) =>
        total + Number(transaction.jumlah || 0),
      0
    )

})


const totalExpenses = computed(() => {

  return normalizedTransactions.value
    .filter(
      transaction =>
        transaction.type === 'expense'
    )
    .reduce(
      (total, transaction) =>
        total + Number(transaction.jumlah || 0),
      0
    )

})


const totalBalance = computed(() => {

  return totalIncome.value -
    totalExpenses.value

})


/* =========================
   PERIOD CHECK
========================= */

const matchesPeriod = (transaction) => {

  if (period.value === 'all') {
    return true
  }

  if (!transaction.tanggal) {
    return false
  }

  const date =
    new Date(transaction.tanggal)

  if (Number.isNaN(date.getTime())) {
    return false
  }

  const now = new Date()

  const currentYear =
    now.getFullYear()

  const currentMonth =
    now.getMonth()


  if (period.value === 'year') {

    return (
      date.getFullYear() ===
      currentYear
    )

  }


  if (period.value === 'month') {

    return (
      date.getFullYear() ===
        currentYear &&
      date.getMonth() ===
        currentMonth
    )

  }


  if (period.value === 'lastMonth') {

    const previousMonth =
      new Date(
        currentYear,
        currentMonth - 1,
        1
      )

    return (
      date.getFullYear() ===
        previousMonth.getFullYear() &&
      date.getMonth() ===
        previousMonth.getMonth()
    )

  }


  return true

}


/* =========================
   CATEGORY PERIOD TEXT
========================= */

const categoryPeriodText = computed(() => {

  if (period.value === 'month') {
    return 'Your spending this month'
  }

  if (period.value === 'lastMonth') {
    return 'Your spending last month'
  }

  if (period.value === 'year') {
    return 'Your spending this year'
  }

  return 'Your spending across all time'

})


/* =========================
   CATEGORY OPTIONS
========================= */

const categoryOptions = computed(() => {

  return categories.value

    .filter(
      category =>
        category.jenis === 'pengeluaran'
    )

    .map(category => ({

      id:
        String(category.id_kategori),

      name:
        category.nama_kategori,

      icon:
        category.ikon || '💳'

    }))

})


/* =========================
   CATEGORY EXPENSES
========================= */

const categoryExpenses = computed(() => {

  const expenses =
    normalizedTransactions.value
      .filter(
        transaction =>
          transaction.type === 'expense' &&
          matchesPeriod(transaction)
      )


  const groups = {}


  expenses.forEach(transaction => {

    const id =
      String(
        transaction.categoryId ??
        'uncategorized'
      )


    if (!groups[id]) {

      groups[id] = {

        id,

        name:
          transaction.category ||
          'Uncategorized',

        icon:
          transaction.icon ||
          '💳',

        amount: 0,

        count: 0

      }

    }


    groups[id].amount +=
      Number(transaction.jumlah || 0)

    groups[id].count += 1

  })


  const total =
    expenses.reduce(
      (sum, transaction) =>
        sum +
        Number(transaction.jumlah || 0),
      0
    )


  return Object.values(groups)

    .map(category => ({

      ...category,

      percentage:
        total > 0
          ? Math.round(
              (category.amount / total) *
              100
            )
          : 0

    }))

    .sort(
      (a, b) =>
        b.amount -
        a.amount
    )

})


/* =========================
   VISIBLE CATEGORIES
========================= */

const visibleCategories = computed(() => {

  if (showAllCategories.value) {
    return categoryExpenses.value
  }

  return categoryExpenses.value.slice(
    0,
    categoryLimit
  )

})


/* =========================
   SELECT CATEGORY
========================= */

const selectCategory = (id) => {

  const value = String(id)

  if (selectedCategory.value === value) {

    selectedCategory.value = 'all'

    return

  }

  selectedCategory.value = value

  filter.value = 'expense'

}


/* =========================
   CLEAR CATEGORY
========================= */

const clearCategory = () => {

  selectedCategory.value = 'all'

}


/* =========================
   SELECTED CATEGORY NAME
========================= */

const selectedCategoryName = computed(() => {

  if (selectedCategory.value === 'all') {
    return ''
  }

  const category =
    categoryOptions.value.find(
      category =>
        category.id ===
        selectedCategory.value
    )


  return category?.name ||
    'Selected category'

})


/* =========================
   FILTERED TRANSACTIONS
========================= */

const filteredTransactions = computed(() => {

  let result =
    normalizedTransactions.value


  if (filter.value !== 'all') {

    result =
      result.filter(
        transaction =>
          transaction.type ===
          filter.value
      )

  }


  result =
    result.filter(
      transaction =>
        matchesPeriod(transaction)
    )


  if (selectedCategory.value !== 'all') {

    result =
      result.filter(
        transaction =>
          String(transaction.categoryId) ===
          String(selectedCategory.value)
      )

  }


  return result

})


/* =========================
   AVAILABLE FORM CATEGORIES
========================= */

const availableCategories = computed(() => {

  return categories.value.filter(
    category =>
      category.jenis ===
      newTransaction.value.jenis
  )

})


/* =========================
   WATCH TRANSACTION TYPE
========================= */

watch(
  () => newTransaction.value.jenis,
  () => {

    const first =
      availableCategories.value[0]

    newTransaction.value.id_kategori =
      first?.id_kategori || ''

  }
)


/* =========================
   RESET FORM
========================= */

const resetForm = () => {

  newTransaction.value = {

    id_dompet:
      wallets.value[0]?.id_dompet ||
      '',

    id_kategori:
      availableCategories.value[0]?.id_kategori ||
      '',

    jenis: 'pengeluaran',

    jumlah: '',

    judul: '',

    deskripsi: '',

    tanggal: today

  }

  formError.value = ''

}


/* =========================
   OPEN MODAL
========================= */

const openModal = () => {

  resetForm()

  showModal.value = true

}


/* =========================
   CLOSE MODAL
========================= */

const closeModal = () => {

  if (saving.value) {
    return
  }

  showModal.value = false

  formError.value = ''

}


/* =========================
   ADD TRANSACTION
========================= */

const addTransaction = async () => {

  formError.value = ''


  if (
    !newTransaction.value.judul ||
    !newTransaction.value.jumlah ||
    !newTransaction.value.id_dompet ||
    !newTransaction.value.id_kategori ||
    !newTransaction.value.tanggal
  ) {

    formError.value =
      'Please complete all required fields.'

    return

  }


  if (
    Number(newTransaction.value.jumlah) <= 0
  ) {

    formError.value =
      'Amount must be greater than 0.'

    return

  }


  saving.value = true


  try {

    const response =
      await api.post(
        '/transaksi',
        {

          id_dompet:
            Number(
              newTransaction.value.id_dompet
            ),

          id_kategori:
            Number(
              newTransaction.value.id_kategori
            ),

          jenis:
            newTransaction.value.jenis,

          jumlah:
            Number(
              newTransaction.value.jumlah
            ),

          judul:
            newTransaction.value.judul,

          deskripsi:
            newTransaction.value.deskripsi ||
            null,

          tanggal:
            newTransaction.value.tanggal

        }
      )


    const created =
      response.data?.data


    if (created) {

      transactions.value.unshift(
        created
      )

    } else {

      await loadData()

    }


    showModal.value = false

    resetForm()

  } catch (error) {

    if (isUnauthorized(error)) {

      router.push('/login')

      return

    }


    formError.value =
      error?.response?.data?.message ||
      'Unable to save transaction.'

  } finally {

    saving.value = false

  }

}


/* =========================
   DELETE TRANSACTION
========================= */

const deleteTransaction = async (id) => {

  if (!id) {
    return
  }


  const confirmed =
    window.confirm(
      'Delete this transaction?'
    )


  if (!confirmed) {
    return
  }


  try {

    await api.delete(
      `/transaksi/${id}`
    )


    transactions.value =
      transactions.value.filter(
        transaction =>
          transaction.id_transaksi !== id
      )

  } catch (error) {

    if (isUnauthorized(error)) {

      router.push('/login')

      return

    }


    window.alert(
      error?.response?.data?.message ||
      'Unable to delete transaction.'
    )

  }

}


/* =========================
   START
========================= */

onMounted(loadData)

</script>


<style scoped>

/* =========================================
   PAGE
========================================= */

.transactions-page {
  padding: 32px 42px 50px;
  color: #172033;
}


/* =========================================
   HEADER
========================================= */

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 28px;
}


.eyebrow {
  margin-bottom: 5px;
  color: #969caf;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: 1.3px;
}


.page-header h1 {
  margin: 0 0 6px;
  font-size: 28px;
  letter-spacing: -0.7px;
}


.page-header p {
  margin: 0;
  color: #9299aa;
  font-size: 13px;
}


/* =========================================
   BUTTON
========================================= */

.add-btn {
  height: 44px;

  display: flex;
  align-items: center;

  gap: 8px;

  padding: 0 20px;

  border: none;
  border-radius: 9px;

  background: #6655e9;

  color: white;

  font-size: 12px;
  font-weight: 700;

  cursor: pointer;

  box-shadow:
    0 8px 20px rgba(102, 85, 233, .2);

  transition: .2s;
}


.add-btn:hover {
  background: #5746dc;
  transform: translateY(-1px);
}


.add-btn span {
  font-size: 18px;
  line-height: 1;
}


/* =========================================
   SUMMARY
========================================= */

.summary-grid {
  display: grid;

  grid-template-columns:
    repeat(3, 1fr);

  gap: 18px;

  margin-bottom: 24px;
}


.summary-card {
  display: flex;
  align-items: center;

  gap: 14px;

  padding: 20px;

  background: white;

  border: 1px solid #e8eaf0;

  border-radius: 12px;
}


.summary-card span {
  display: block;

  margin-bottom: 6px;

  color: #9299aa;

  font-size: 11px;
}


.summary-card strong {
  font-size: 20px;
}


.summary-icon {
  width: 42px;
  height: 42px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 10px;

  font-weight: 700;
}


.balance-icon {
  color: #6655e9;
  background: #eeebff;
}


.income-icon {
  color: #16a56b;
  background: #e7f8f0;
}


.expense-icon {
  color: #ef6262;
  background: #ffeded;
}


/* =========================================
   CATEGORY CARD
========================================= */

.category-card,
.transactions-card {
  background: white;

  border: 1px solid #e8eaf0;

  border-radius: 14px;

  overflow: hidden;
}


.category-card {
  margin-bottom: 24px;
}


.section-header,
.transactions-header {
  min-height: 76px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 20px 24px;

  border-bottom: 1px solid #edf0f5;
}


.section-header h2,
.transactions-header h2 {
  margin: 0 0 5px;

  font-size: 16px;
}


.section-header p,
.transactions-header p {
  margin: 0;

  color: #9aa1b2;

  font-size: 11px;
}


/* =========================================
   CATEGORY
========================================= */

.category-list {
  padding: 0 24px;
}


.category-row {
  width: 100%;
  min-height: 78px;

  display: flex;
  align-items: center;

  gap: 14px;

  padding: 13px 0;

  border: none;
  border-bottom: 1px solid #eef0f4;

  background: transparent;

  text-align: left;

  cursor: pointer;

  transition: .18s ease;
}


.category-row:hover {
  transform: translateX(3px);
}


.category-row.selected {
  padding-left: 10px;
  padding-right: 10px;

  border-radius: 9px;

  background: #f7f5ff;
}


.category-icon {
  width: 42px;
  height: 42px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 10px;

  background: #f0edff;

  font-size: 18px;
}


.category-main {
  min-width: 0;
  flex: 1;
}


.category-name-row {
  display: flex;
  align-items: center;
  justify-content: space-between;

  margin-bottom: 9px;
}


.category-name-row strong {
  font-size: 12px;
}


.category-name-row span {
  color: #a1a7b5;
  font-size: 10px;
}


.progress-track {
  width: 100%;
  height: 5px;

  overflow: hidden;

  border-radius: 10px;

  background: #f0f1f5;
}


.progress-fill {
  height: 100%;

  border-radius: 10px;

  background: #6655e9;

  transition: width .3s ease;
}


.category-total {
  min-width: 100px;

  text-align: right;
}


.category-total strong {
  display: block;

  margin-bottom: 4px;

  font-size: 12px;
}


.category-total span {
  color: #8f96a7;
  font-size: 10px;
}


.category-arrow {
  width: 25px;

  color: #b2b7c3;

  font-size: 15px;

  text-align: right;
}


.view-all-btn {
  width: 100%;

  padding: 16px 0;

  border: none;

  background: transparent;

  color: #6655e9;

  font-size: 11px;
  font-weight: 700;

  cursor: pointer;
}


.view-all-btn span {
  margin-left: 5px;
}


/* =========================================
   CLEAR FILTER
========================================= */

.clear-filter-btn {
  padding: 8px 12px;

  border: 1px solid #e0e3ea;

  border-radius: 7px;

  background: white;

  color: #6655e9;

  font-size: 11px;
  font-weight: 600;

  cursor: pointer;
}


.clear-filter-btn:hover {
  background: #f7f5ff;
}


/* =========================================
   TRANSACTION FILTERS
========================================= */

.filters {
  display: flex;
  gap: 8px;
}


.filters select {
  height: 34px;

  padding: 0 10px;

  border: 1px solid #e0e3ea;

  border-radius: 7px;

  background: white;

  color: #4e5668;

  font-size: 11px;

  cursor: pointer;
}


.filters select:focus {
  outline: none;
  border-color: #6655e9;
}


/* =========================================
   TRANSACTION ROW
========================================= */

.transaction-list {
  padding: 0 24px;
}


.transaction-row {
  min-height: 70px;

  display: grid;

  grid-template-columns:
    42px
    minmax(150px, 1fr)
    120px
    110px
    110px
    30px;

  align-items: center;

  gap: 14px;

  border-bottom: 1px solid #eef0f4;
}


.transaction-icon {
  width: 40px;
  height: 40px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 10px;

  font-size: 17px;
}


.transaction-icon.income {
  background: #e7f8f0;
}


.transaction-icon.expense {
  background: #ffeded;
}


.transaction-info strong {
  display: block;

  margin-bottom: 5px;

  font-size: 12px;
}


.transaction-info span {
  color: #979eae;

  font-size: 10px;
}


.transaction-wallet {
  color: #6e7586;

  font-size: 11px;
}


.transaction-date {
  color: #979eae;

  font-size: 11px;
}


.transaction-amount {
  font-size: 12px;
  font-weight: 700;

  text-align: right;
}


.transaction-amount.income {
  color: #16a56b;
}


.transaction-amount.expense {
  color: #ef6262;
}


.delete-btn {
  width: 28px;
  height: 28px;

  border: none;
  border-radius: 7px;

  background: transparent;

  color: #a6abb7;

  font-size: 18px;

  cursor: pointer;
}


.delete-btn:hover {
  background: #fff0f0;
  color: #ef6262;
}


/* =========================================
   EMPTY
========================================= */

.empty-state,
.category-empty {
  min-height: 180px;

  display: flex;
  flex-direction: column;

  align-items: center;
  justify-content: center;

  gap: 7px;

  color: #969dad;
}


.empty-state strong,
.category-empty strong {
  color: #555d6f;

  font-size: 13px;
}


.empty-state span,
.category-empty span {
  font-size: 11px;
}


.empty-circle {
  width: 42px;
  height: 42px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 5px;

  border-radius: 50%;

  background: #f1efff;

  color: #6655e9;

  font-size: 19px;
}


/* =========================================
   LOADING SKELETON
========================================= */

.transactions-skeleton {
  width: 100%;
}

.skeleton {
  position: relative;
  overflow: hidden;
  border-radius: 7px;
  background: #eceef3;
}

.skeleton::after {
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
  animation: skeleton-shimmer 1.4s ease-in-out infinite;
}

.skeleton-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 28px;
}

.skeleton-eyebrow {
  width: 105px;
  height: 10px;
  margin-bottom: 9px;
}

.skeleton-title {
  width: 170px;
  height: 30px;
  margin-bottom: 9px;
  border-radius: 8px;
}

.skeleton-description {
  width: 285px;
  height: 13px;
}

.skeleton-header-actions {
  display: flex;
  gap: 10px;
}

.skeleton-button {
  width: 150px;
  height: 42px;
  border-radius: 10px;
}

.skeleton-card {
  overflow: hidden;
}

.skeleton-summary-icon {
  width: 42px;
  height: 42px;
  flex-shrink: 0;
  border-radius: 10px;
}

.skeleton-summary-text {
  flex: 1;
}

.skeleton-label {
  width: 85px;
  height: 10px;
  margin-bottom: 9px;
}

.skeleton-value {
  width: 125px;
  height: 21px;
  border-radius: 6px;
}

.skeleton-section-title {
  width: 150px;
  height: 17px;
  margin-bottom: 8px;
}

.skeleton-section-description {
  width: 185px;
  height: 11px;
}

.skeleton-category-row {
  pointer-events: none;
}

.skeleton-category-icon {
  width: 42px;
  height: 42px;
  flex-shrink: 0;
  border-radius: 10px;
}

.skeleton-category-name {
  width: 115px;
  height: 12px;
  margin-bottom: 12px;
}

.skeleton-progress {
  width: 100%;
  height: 5px;
  border-radius: 10px;
}

.skeleton-category-total {
  width: 75px;
  height: 13px;
  border-radius: 6px;
}

.skeleton-category-arrow {
  width: 18px;
  height: 12px;
}

.skeleton-filters {
  pointer-events: none;
}

.skeleton-filter {
  width: 105px;
  height: 34px;
  border-radius: 7px;
}

.skeleton-transaction-row {
  pointer-events: none;
}

.skeleton-transaction-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
}

.skeleton-transaction-title {
  width: 125px;
  height: 12px;
  margin-bottom: 7px;
}

.skeleton-transaction-category {
  width: 80px;
  height: 9px;
}

.skeleton-transaction-wallet {
  width: 85px;
  height: 10px;
}

.skeleton-transaction-date {
  width: 75px;
  height: 10px;
}

.skeleton-transaction-amount {
  width: 75px;
  height: 12px;
  justify-self: end;
}

.skeleton-delete {
  width: 28px;
  height: 28px;
  border-radius: 7px;
}

@keyframes skeleton-shimmer {
  100% {
    transform: translateX(100%);
  }
}

/* =========================================
   LOADING
========================================= */

.loading-spinner {
  width: 28px;
  height: 28px;

  margin-bottom: 8px;

  border: 3px solid #eee;

  border-top-color: #6655e9;

  border-radius: 50%;

  animation: spin .8s linear infinite;
}


@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}


/* =========================================
   MODAL
========================================= */

.modal-overlay {
  position: fixed;

  inset: 0;

  z-index: 1000;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 20px;

  background: rgba(20, 24, 35, .45);
}


.modal {
  position: relative;

  width: min(600px, 100%);

  max-height: 90vh;

  overflow-y: auto;

  padding: 28px;

  border-radius: 14px;

  background: white;

  box-shadow:
    0 25px 70px
    rgba(20, 24, 35, .2);
}


.modal h2 {
  margin: 0 0 5px;

  font-size: 20px;
}


.modal-description {
  margin: 0 0 22px;

  color: #969dad;

  font-size: 12px;
}


.modal-close {
  position: absolute;

  top: 17px;
  right: 18px;

  width: 30px;
  height: 30px;

  border: none;

  border-radius: 7px;

  background: #f5f5f7;

  color: #727887;

  font-size: 20px;

  cursor: pointer;
}


.form-error {
  margin-bottom: 16px;

  padding: 11px 13px;

  border-radius: 7px;

  background: #fff0f0;

  color: #d95050;

  font-size: 11px;
}


.form-row {
  display: grid;

  grid-template-columns:
    repeat(2, 1fr);

  gap: 14px;
}


.form-group {
  margin-bottom: 15px;
}


.form-group label {
  display: block;

  margin-bottom: 7px;

  color: #525a6b;

  font-size: 11px;
  font-weight: 700;
}


.optional {
  color: #a4a9b5;
  font-weight: 400;
}


.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;

  padding: 10px 11px;

  border: 1px solid #dfe2e8;

  border-radius: 7px;

  background: white;

  color: #252c3d;

  font-family: inherit;

  font-size: 12px;

  outline: none;

  transition: .2s;
}


.form-group textarea {
  resize: vertical;
}


.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #6655e9;

  box-shadow:
    0 0 0 3px
    rgba(102, 85, 233, .08);
}


/* =========================================
   MODAL ACTIONS
========================================= */

.modal-actions {
  display: flex;

  justify-content: flex-end;

  gap: 9px;

  margin-top: 8px;
}


.cancel-btn,
.save-btn {
  height: 40px;

  padding: 0 16px;

  border-radius: 7px;

  font-size: 11px;
  font-weight: 700;

  cursor: pointer;
}


.cancel-btn {
  border: 1px solid #dfe2e8;

  background: white;

  color: #697080;
}


.save-btn {
  border: none;

  background: #6655e9;

  color: white;
}


.save-btn:hover {
  background: #5746dc;
}


.save-btn:disabled {
  opacity: .6;
  cursor: not-allowed;
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 900px) {

  .skeleton-header {
    align-items: flex-start;
    flex-direction: column;
    gap: 18px;
  }

  .skeleton-header-actions {
    width: 100%;
  }

  .skeleton-button {
    flex: 1;
  }

  .skeleton-category-row {
    grid-template-columns: 42px 1fr auto;
  }

  .skeleton-category-arrow {
    display: none;
  }



  .transactions-page {
    padding: 25px 20px 40px;
  }


  .summary-grid {
    grid-template-columns: 1fr;
  }


  .transactions-header {
    align-items: flex-start;

    flex-direction: column;

    gap: 15px;
  }


  .filters {
    width: 100%;

    display: grid;

    grid-template-columns:
      repeat(3, 1fr);
  }


  .filters select {
    width: 100%;
  }


  .transaction-row {
    grid-template-columns:
      42px
      1fr
      auto
      30px;
  }


  .transaction-wallet,
  .transaction-date {
    display: none;
  }

}


@media (max-width: 600px) {

  .skeleton-header-actions {
    width: 100%;
    flex-direction: column;
  }

  .skeleton-button {
    width: 100%;
  }

  .skeleton-summary-icon {
    width: 40px;
    height: 40px;
  }

  .skeleton-category-total {
    width: 55px;
  }

  .skeleton-filters {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr;
  }

  .skeleton-filter {
    width: 100%;
  }



  .page-header {
    align-items: flex-start;

    flex-direction: column;

    gap: 18px;
  }


  .add-btn {
    width: 100%;
  }


  .section-header {
    align-items: flex-start;

    flex-direction: column;

    gap: 12px;
  }


  .category-list,
  .transaction-list {
    padding: 0 15px;
  }


  .category-total {
    min-width: 70px;
  }


  .category-arrow {
    display: none;
  }


  .form-row {
    grid-template-columns: 1fr;
    gap: 0;
  }


  .modal {
    padding: 23px;

    max-height: 94vh;
  }


  .filters {
    grid-template-columns: 1fr;
  }


  .transaction-row {
    grid-template-columns:
      38px
      1fr
      auto
      25px;

    gap: 10px;
  }


  .transaction-icon {
    width: 36px;
    height: 36px;
  }

}

.header-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.header-button {
  height: 42px;
  min-width: 150px;

  display: flex;
  align-items: center;
  justify-content: center;

  gap: 8px;
  padding: 0 16px;

  border: none;
  border-radius: 10px;

  background: #6655e9;
  color: white;

  font-size: 12px;
  font-weight: 700;

  cursor: pointer;

  box-shadow: 0 8px 20px rgba(102, 85, 233, .20);

  transition: .2s ease;
}

.header-button:hover {
  background: #5746dc;
  transform: translateY(-1px);
}

.header-button:active {
  transform: translateY(0);
}

.header-button span {
  font-size: 15px;
}
</style>