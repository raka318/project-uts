<template>
  <div class="page">
    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>
        <h1>Transactions</h1>
        <p>Track and manage all your income and expenses.</p>
      </div>

      <button class="primary-button" @click="openModal">
        <span>+</span>
        Add Transaction
      </button>
    </div>

    <div class="summary-grid">
      <div class="summary-card">
        <div class="summary-icon purple">$</div>
        <div>
          <span>Total Balance</span>
          <strong>{{ transactions.length ? '$' + formatNumber(totalBalance) : '—' }}</strong>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon green">↗</div>
        <div>
          <span>Total Income</span>
          <strong>{{ transactions.length ? '$' + formatNumber(totalIncome) : '—' }}</strong>
        </div>
      </div>

      <div class="summary-card">
        <div class="summary-icon red">↘</div>
        <div>
          <span>Total Expenses</span>
          <strong>{{ transactions.length ? '$' + formatNumber(totalExpenses) : '—' }}</strong>
        </div>
      </div>
    </div>

    <div class="transaction-card">
      <div class="card-header">
        <div>
          <h2>All Transactions</h2>
          <p>Your recent financial activity</p>
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
        </div>
      </div>

      <div v-if="loading" class="empty-state">
        <div class="empty-icon">↻</div>
        <strong>Loading transactions...</strong>
        <span>Please wait while your transactions are loaded.</span>
      </div>

      <div v-else class="transaction-list">
        <div
          v-for="transaction in filteredTransactions"
          :key="transaction.id_transaksi"
          class="transaction-item"
        >
          <div
            class="transaction-icon"
            :class="transaction.type"
          >
            {{ transaction.icon }}
          </div>

          <div class="transaction-info">
            <strong>{{ transaction.name }}</strong>
            <span>{{ transaction.category }}</span>
          </div>

          <div class="transaction-wallet">
            {{ transaction.wallet }}
          </div>

          <div class="transaction-date">
            {{ transaction.date }}
          </div>

          <div
            class="transaction-amount"
            :class="transaction.type"
          >
            {{ transaction.type === 'income' ? '+' : '-' }}${{
              formatNumber(transaction.amount)
            }}
          </div>

          <button
            class="delete-button"
            title="Delete transaction"
            @click="deleteTransaction(transaction.id_transaksi)"
          >
            ×
          </button>
        </div>
      </div>

      <div
        v-if="!loading && filteredTransactions.length === 0"
        class="empty-state"
      >
        <div class="empty-icon">↗</div>
        <strong>
          {{ transactions.length ? 'No transactions found.' : 'No transactions yet.' }}
        </strong>
        <span>
          {{ transactions.length
            ? 'Try changing your filter or period.'
            : 'Your income and expenses will appear here.' }}
        </span>
      </div>
    </div>

    <div
      v-if="showModal"
      class="modal-overlay"
      @click.self="closeModal"
    >
      <div class="modal">
        <button class="close-button" @click="closeModal">×</button>

        <h2>Add Transaction</h2>
        <p>Add a new income or expense.</p>

        <div v-if="formError" class="form-error">
          {{ formError }}
        </div>

        <div class="form-group">
          <label>Transaction name</label>
          <input
            v-model="newTransaction.judul"
            placeholder="Example: Lunch"
          />
        </div>

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
              <option value="pengeluaran">Expense</option>
              <option value="pemasukan">Income</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Wallet</label>
            <select v-model="newTransaction.id_dompet">
              <option value="" disabled>Select wallet</option>
              <option
                v-for="wallet in wallets"
                :key="wallet.id_dompet"
                :value="wallet.id_dompet"
              >
                {{ wallet.nama_dompet }}
              </option>
            </select>
            <small v-if="wallets.length === 0">
              No wallets available yet.
            </small>
          </div>

          <div class="form-group">
            <label>Category</label>
            <select v-model="newTransaction.id_kategori">
              <option value="" disabled>Select category</option>
              <option
                v-for="category in availableCategories"
                :key="category.id_kategori"
                :value="category.id_kategori"
              >
                {{ category.nama_kategori }}
              </option>
            </select>
            <small v-if="categories.length === 0">
              No categories available yet.
            </small>
          </div>
        </div>

        <div class="form-group">
          <label>Date</label>
          <input
            v-model="newTransaction.tanggal"
            type="date"
          />
        </div>

        <div class="form-group">
          <label>Description <span>(optional)</span></label>
          <textarea
            v-model="newTransaction.deskripsi"
            rows="3"
            placeholder="Add a note..."
          ></textarea>
        </div>

        <div class="modal-actions">
          <button class="cancel-button" @click="closeModal">
            Cancel
          </button>

          <button
            class="primary-button"
            :disabled="saving"
            @click="addTransaction"
          >
            {{ saving ? 'Saving...' : 'Add Transaction' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
import { computed, ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../utils/api.js'

const router = useRouter()

const filter = ref('all')
const period = ref('all')

const showModal = ref(false)
const loading = ref(false)
const saving = ref(false)
const formError = ref('')

const transactions = ref([])
const wallets = ref([])
const categories = ref([])

const today = new Date().toISOString().slice(0, 10)

const newTransaction = ref({
  id_dompet: '',
  id_kategori: '',
  jenis: 'pengeluaran',
  jumlah: '',
  judul: '',
  deskripsi: '',
  tanggal: today
})

const isUnauthorized = (error) => error?.response?.status === 401

const getApiData = (response) => {
  const data = response?.data?.data
  return Array.isArray(data) ? data : []
}

const normalizeTransaction = (transaction) => {
  const isIncome = transaction.jenis === 'pemasukan'

  return {
    ...transaction,
    type: isIncome ? 'income' : 'expense',
    name: transaction.judul || 'Untitled transaction',
    category: transaction.kategori?.nama_kategori || 'Uncategorized',
    wallet: transaction.dompet?.nama_dompet || 'Unknown wallet',
    amount: Number(transaction.jumlah || 0),
    date: formatDate(transaction.tanggal),
    icon: transaction.kategori?.ikon || (isIncome ? '💰' : '💳')
  }
}

const formatDate = (date) => {
  if (!date) return '—'

  const parsed = new Date(date)

  if (Number.isNaN(parsed.getTime())) {
    return date
  }

  return parsed.toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  })
}

const formatNumber = (value) =>
  Number(value || 0).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })

const totalIncome = computed(() =>
  transactions.value
    .filter(transaction => transaction.type === 'income')
    .reduce((total, transaction) => total + Number(transaction.amount || 0), 0)
)

const totalExpenses = computed(() =>
  transactions.value
    .filter(transaction => transaction.type === 'expense')
    .reduce((total, transaction) => total + Number(transaction.amount || 0), 0)
)

const totalBalance = computed(() =>
  totalIncome.value - totalExpenses.value
)

const availableCategories = computed(() =>
  categories.value.filter(category =>
    category.jenis === newTransaction.value.jenis
  )
)

const filteredTransactions = computed(() => {
  let result = transactions.value

  if (filter.value !== 'all') {
    result = result.filter(transaction => transaction.type === filter.value)
  }

  if (period.value !== 'all') {
    const now = new Date()
    const year = now.getFullYear()
    const month = now.getMonth()

    result = result.filter(transaction => {
      if (!transaction.tanggal) return false

      const date = new Date(transaction.tanggal)

      if (Number.isNaN(date.getTime())) return false

      if (period.value === 'year') {
        return date.getFullYear() === year
      }

      if (period.value === 'month') {
        return (
          date.getFullYear() === year &&
          date.getMonth() === month
        )
      }

      if (period.value === 'lastMonth') {
        const lastMonthDate = new Date(year, month - 1, 1)

        return (
          date.getFullYear() === lastMonthDate.getFullYear() &&
          date.getMonth() === lastMonthDate.getMonth()
        )
      }

      return true
    })
  }

  return result
})

const loadData = async () => {
  loading.value = true

  try {
    const results = await Promise.allSettled([
      api.get('/transaksi'),
      api.get('/dompet'),
      api.get('/kategori')
    ])

    const unauthorized = results.some(
      result =>
        result.status === 'rejected' &&
        isUnauthorized(result.reason)
    )

    if (unauthorized) {
      router.push('/login')
      return
    }

    const transactionResult = results[0]
    const walletResult = results[1]
    const categoryResult = results[2]

    transactions.value =
      transactionResult.status === 'fulfilled'
        ? getApiData(transactionResult.value).map(normalizeTransaction)
        : []

    wallets.value =
      walletResult.status === 'fulfilled'
        ? getApiData(walletResult.value)
        : []

    categories.value =
      categoryResult.status === 'fulfilled'
        ? getApiData(categoryResult.value)
        : []
  } finally {
    loading.value = false
  }
}

const resetForm = () => {
  newTransaction.value = {
    id_dompet: '',
    id_kategori: '',
    jenis: 'pengeluaran',
    jumlah: '',
    judul: '',
    deskripsi: '',
    tanggal: today
  }

  formError.value = ''
}

const openModal = () => {
  resetForm()

  if (wallets.value.length) {
    newTransaction.value.id_dompet = wallets.value[0].id_dompet
  }

  const firstCategory = availableCategories.value[0]

  if (firstCategory) {
    newTransaction.value.id_kategori = firstCategory.id_kategori
  }

  showModal.value = true
}

const closeModal = () => {
  if (saving.value) return

  showModal.value = false
  resetForm()
}

const addTransaction = async () => {
  formError.value = ''

  if (
    !newTransaction.value.judul ||
    !newTransaction.value.jumlah ||
    !newTransaction.value.id_dompet ||
    !newTransaction.value.id_kategori ||
    !newTransaction.value.tanggal
  ) {
    formError.value = 'Please complete all required fields.'
    return
  }

  if (Number(newTransaction.value.jumlah) <= 0) {
    formError.value = 'Amount must be greater than 0.'
    return
  }

  saving.value = true

  try {
    const response = await api.post('/transaksi', {
      id_dompet: Number(newTransaction.value.id_dompet),
      id_kategori: Number(newTransaction.value.id_kategori),
      jenis: newTransaction.value.jenis,
      jumlah: Number(newTransaction.value.jumlah),
      judul: newTransaction.value.judul,
      deskripsi: newTransaction.value.deskripsi || null,
      tanggal: newTransaction.value.tanggal
    })

    const created = response?.data?.data

    if (created) {
      transactions.value.unshift(normalizeTransaction(created))
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
      'Unable to save the transaction. Please try again.'
  } finally {
    saving.value = false
  }
}

const deleteTransaction = async (id) => {
  if (!id) return

  try {
    await api.delete(`/transaksi/${id}`)

    transactions.value = transactions.value.filter(
      transaction => transaction.id_transaksi !== id
    )
  } catch (error) {
    if (isUnauthorized(error)) {
      router.push('/login')
      return
    }

    window.alert(
      error?.response?.data?.message ||
      'Unable to delete the transaction.'
    )
  }
}

onMounted(loadData)
</script>
<style scoped>

* {
  box-sizing: border-box;
}

.page {
  padding: 32px 42px 50px;
  color: #172033;
}


/* HEADER */

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: center;

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

  letter-spacing: -0.7px;
}

.page-header p {
  margin: 0;

  color: #9299aa;

  font-size: 13px;
}


/* BUTTON */

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


/* SUMMARY */

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

.summary-icon.purple {
  color: #6655e9;
  background: #eeebff;
}

.summary-icon.green {
  color: #16a56b;
  background: #e7f8f0;
}

.summary-icon.red {
  color: #ef6262;
  background: #ffeded;
}


/* CARD */

.transaction-card {
  background: white;

  border: 1px solid #e8eaf0;

  border-radius: 14px;

  overflow: hidden;
}

.card-header {
  padding: 24px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  border-bottom: 1px solid #edf0f5;
}

.card-header h2 {
  margin: 0 0 5px;

  font-size: 16px;
}

.card-header p {
  margin: 0;

  color: #9aa1b2;

  font-size: 11px;
}

.filters {
  display: flex;
  gap: 10px;
}

.filters select {
  height: 38px;

  padding: 0 12px;

  border: 1px solid #e0e3ea;

  border-radius: 7px;

  background: white;

  color: #596176;

  font-size: 11px;

  outline: none;
}


/* TRANSACTIONS */

.transaction-list {
  padding: 0 24px;
}

.transaction-item {
  min-height: 72px;

  display: flex;
  align-items: center;

  gap: 14px;

  border-bottom: 1px solid #eef0f4;
}

.transaction-item:last-child {
  border-bottom: 0;
}

.transaction-icon {
  width: 38px;
  height: 38px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 9px;

  background: #f2f0ff;

  font-size: 16px;
}

.transaction-info {
  flex: 1;
}

.transaction-info strong {
  display: block;

  margin-bottom: 4px;

  font-size: 12px;
}

.transaction-info span {
  color: #a0a6b5;

  font-size: 10px;
}

.transaction-date {
  color: #9ca3b3;

  font-size: 10px;
}

.transaction-amount {
  min-width: 90px;

  text-align: right;

  font-size: 12px;
  font-weight: 700;
}

.transaction-amount.expense {
  color: #20283a;
}

.transaction-amount.income {
  color: #18a66b;
}

.empty-state {
  min-height: 220px;
  padding: 50px 20px;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  text-align: center;
  color: #9aa1b2;
}

.empty-icon {
  width: 44px;
  height: 44px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 12px;

  border-radius: 50%;

  background: #f0edff;
  color: #6655e9;

  font-size: 18px;
}

.empty-state strong {
  margin-bottom: 5px;

  color: #667085;
  font-size: 12px;
}

.empty-state span {
  color: #9aa1b2;
  font-size: 10px;
}


/* MODAL */

.modal-overlay {
  position: fixed;

  inset: 0;

  z-index: 100;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 20px;

  background: rgba(20, 24, 40, .35);

  backdrop-filter: blur(4px);
}

.modal {
  position: relative;

  width: min(420px, 100%);

  padding: 30px;

  border-radius: 16px;

  background: white;

  box-shadow:
    0 25px 70px rgba(20, 20, 60, .18);
}

.modal h2 {
  margin: 0 0 7px;
}

.modal > p {
  margin: 0 0 24px;

  color: #969caf;

  font-size: 12px;
}

.close-button {
  position: absolute;

  top: 16px;
  right: 16px;

  border: 0;

  background: transparent;

  color: #8d94a6;

  font-size: 22px;

  cursor: pointer;
}

.form-group {
  margin-bottom: 17px;
}

.form-group label {
  display: block;

  margin-bottom: 7px;

  font-size: 11px;
  font-weight: 600;
}

.form-group input,
.form-group select {
  width: 100%;
  height: 42px;

  padding: 0 12px;

  border: 1px solid #dfe2ea;

  border-radius: 8px;

  outline: none;

  font-size: 12px;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #6655e9;
}

.modal-actions {
  display: flex;

  justify-content: flex-end;

  gap: 10px;

  margin-top: 24px;
}

.cancel-button {
  padding: 0 18px;

  height: 42px;

  border: 1px solid #dfe2ea;

  border-radius: 8px;

  background: white;

  color: #697186;

  cursor: pointer;
}


/* MOBILE */

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

  .summary-grid {
    grid-template-columns: 1fr;
  }

  .card-header {
    align-items: flex-start;
    flex-direction: column;

    gap: 15px;
  }

  .filters {
    width: 100%;
  }

  .filters select {
    flex: 1;
  }

  .transaction-list {
    padding: 0 15px;
  }

  .transaction-item {
    gap: 9px;
  }

  .transaction-date {
    display: none;
  }

  .transaction-amount {
    min-width: 70px;
  }

}

<style>
/* API FORM / EXTRA FIELDS */

.form-row {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 14px;
}

.form-group small {
  display: block;
  margin-top: 6px;
  color: #a0a6b5;
  font-size: 11px;
}

.form-group label span {
  color: #a0a6b5;
  font-weight: 400;
}

textarea {
  width: 100%;
  padding: 11px 12px;
  border: 1px solid #e3e5ec;
  border-radius: 8px;
  background: #fff;
  color: #172033;
  font: inherit;
  font-size: 12px;
  resize: vertical;
  outline: none;
}

textarea:focus {
  border-color: #6655e9;
  box-shadow: 0 0 0 3px rgba(102, 85, 233, .08);
}

.transaction-wallet {
  min-width: 100px;
  color: #8e95a6;
  font-size: 11px;
  text-align: center;
}

.delete-button {
  width: 30px;
  height: 30px;
  border: 0;
  border-radius: 7px;
  background: #fff1f1;
  color: #e76565;
  font-size: 18px;
  line-height: 1;
  cursor: pointer;
}

.delete-button:hover {
  background: #ffe5e5;
}

.primary-button:disabled {
  opacity: .6;
  cursor: not-allowed;
  transform: none;
}

.form-error {
  margin-bottom: 14px;
  padding: 10px 12px;
  border: 1px solid #ffd4d4;
  border-radius: 8px;
  background: #fff3f3;
  color: #d94d4d;
  font-size: 12px;
}

@media (max-width: 900px) {
  .transaction-wallet {
    display: none;
  }
}

@media (max-width: 600px) {
  .form-row {
    grid-template-columns: 1fr;
  }
}
</style>