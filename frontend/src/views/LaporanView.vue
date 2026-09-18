<template>
  <div class="report-page">
    <div class="report-content">

      <!-- =========================================
           HEADER
      ========================================== -->

      <div class="page-header">
        <div>
          <span class="eyebrow">FINANCIAL REPORT</span>

          <h1>{{ reportTitle }}</h1>

          <p>
            Review your financial activity for the selected period.
          </p>
        </div>

        <button
          class="print-button"
          @click="printReport"
        >
          <span>🖨</span>
          Print Report
        </button>
      </div>

      <!-- =========================================
           REPORT PERIOD SELECTOR
      ========================================== -->

      <section class="period-panel">

        <div class="period-tabs">
          <button
            v-for="period in periods"
            :key="period.value"
            class="period-tab"
            :class="{ active: reportPeriod === period.value }"
            @click="changePeriod(period.value)"
          >
            {{ period.label }}
          </button>
        </div>

      </section>

      <!-- =========================================
           DATE NAVIGATION
      ========================================== -->

      <section class="date-panel">

        <button
          class="date-nav-button"
          @click="previousPeriod"
          aria-label="Previous period"
        >
          ‹
        </button>

        <div class="date-center">

          <span class="date-label">
            {{ periodLabel }}
          </span>

          <div class="selected-date">
            {{ formattedPeriod }}
          </div>

          <!-- DAILY -->
          <input
            v-if="reportPeriod === 'daily'"
            v-model="selectedDate"
            type="date"
            class="date-input"
          />

          <!-- WEEKLY -->
          <input
            v-else-if="reportPeriod === 'weekly'"
            v-model="selectedDate"
            type="date"
            class="date-input"
          />

          <!-- MONTHLY -->
          <input
            v-else-if="reportPeriod === 'monthly'"
            v-model="selectedMonth"
            type="month"
            class="date-input"
          />

          <!-- YEARLY -->
          <input
            v-else
            v-model="selectedYear"
            type="number"
            min="2000"
            max="2100"
            class="year-input"
          />

        </div>

        <button
          class="date-nav-button"
          @click="nextPeriod"
          aria-label="Next period"
        >
          ›
        </button>

      </section>

      <!-- =========================================
           LOADING
      ========================================== -->

      <div
        v-if="loading"
        class="loading-state"
      >

        <!-- Skeleton Stats -->

        <div class="skeleton-stats">

          <div
            v-for="n in 3"
            :key="n"
            class="skeleton-card"
          >
            <div class="skeleton skeleton-icon"></div>

            <div class="skeleton skeleton-small"></div>

            <div class="skeleton skeleton-value"></div>

            <div class="skeleton skeleton-description"></div>
          </div>

        </div>

        <!-- Skeleton Transaction Panel -->

        <div class="skeleton-panel">

          <div class="skeleton skeleton-title"></div>

          <div class="skeleton-table">

            <div
              v-for="n in 6"
              :key="n"
              class="skeleton-row"
            >
              <div class="skeleton"></div>
              <div class="skeleton"></div>
              <div class="skeleton"></div>
              <div class="skeleton"></div>
              <div class="skeleton"></div>
            </div>

          </div>

        </div>

        <!-- Skeleton Bottom -->

        <div class="skeleton-bottom">

          <div class="skeleton-panel">

            <div class="skeleton skeleton-title"></div>

            <div
              v-for="n in 4"
              :key="n"
              class="skeleton skeleton-line"
            ></div>

          </div>

          <div class="skeleton-panel">

            <div class="skeleton skeleton-title"></div>

            <div
              v-for="n in 5"
              :key="n"
              class="skeleton skeleton-line"
            ></div>

          </div>

        </div>

      </div>

      <!-- =========================================
           REPORT CONTENT
      ========================================== -->

      <div v-else>

        <!-- =========================================
             FINANCIAL STATEMENT
        ========================================== -->

        <section class="statement-grid">

          <!-- INCOME -->

          <div class="stat-card">

            <div class="stat-top">

              <div class="stat-icon income-icon">
                ↗
              </div>

              <span class="stat-label">
                INCOME
              </span>

            </div>

            <div class="stat-value income-text">
              {{ formatCurrency(periodIncome) }}
            </div>

            <div class="stat-description">

              {{ incomeTransactions.length }}

              income transaction{{
                incomeTransactions.length === 1
                  ? ''
                  : 's'
              }}

            </div>

          </div>

          <!-- EXPENSE -->

          <div class="stat-card">

            <div class="stat-top">

              <div class="stat-icon expense-icon">
                ↘
              </div>

              <span class="stat-label">
                EXPENSES
              </span>

            </div>

            <div class="stat-value expense-text">
              {{ formatCurrency(periodExpenses) }}
            </div>

            <div class="stat-description">

              {{ expenseTransactions.length }}

              expense transaction{{
                expenseTransactions.length === 1
                  ? ''
                  : 's'
              }}

            </div>

          </div>

          <!-- NET -->

          <div class="stat-card">

            <div class="stat-top">

              <div class="stat-icon net-icon">
                =
              </div>

              <span class="stat-label">
                NET RESULT
              </span>

            </div>

            <div
              class="stat-value"
              :class="
                periodNet >= 0
                  ? 'income-text'
                  : 'expense-text'
              "
            >
              {{ formatCurrency(periodNet) }}
            </div>

            <div class="stat-description">
              Income minus expenses
            </div>

          </div>

        </section>

        <!-- =========================================
             TRANSACTION DETAILS
        ========================================== -->

        <section class="panel transaction-panel">

          <div class="panel-header">

            <div>

              <h3>
                Transaction Details
              </h3>

              <p>
                {{ periodTransactions.length }}

                transaction{{
                  periodTransactions.length === 1
                    ? ''
                    : 's'
                }}

                recorded for this {{ reportPeriodLabel.toLowerCase() }}.
              </p>

            </div>

            <span class="panel-badge">
              {{ formattedPeriod }}
            </span>

          </div>

          <!-- TABLE -->

          <div
            v-if="periodTransactions.length"
            class="table-wrapper"
          >

            <table class="transaction-table">

              <thead>

                <tr>
                  <th>TRANSACTION</th>
                  <th>CATEGORY</th>
                  <th>WALLET</th>
                  <th>TYPE</th>
                  <th>AMOUNT</th>
                </tr>

              </thead>

              <tbody>

                <tr
                  v-for="transaction in periodTransactions"
                  :key="transaction.id_transaksi"
                >

                  <!-- TRANSACTION -->

                  <td>

                    <div class="transaction-name">
                      {{ transaction.judul || 'Untitled transaction' }}
                    </div>

                    <div
                      v-if="transaction.deskripsi"
                      class="transaction-description"
                    >
                      {{ transaction.deskripsi }}
                    </div>

                    <div class="transaction-date">
                      {{ formatTransactionDate(transaction.tanggal) }}
                    </div>

                  </td>

                  <!-- CATEGORY -->

                  <td>
                    <span class="category-text">
                      {{ getCategoryName(transaction) }}
                    </span>
                  </td>

                  <!-- WALLET -->

                  <td>
                    <span class="wallet-text">
                      {{ getWalletName(transaction) }}
                    </span>
                  </td>

                  <!-- TYPE -->

                  <td>

                    <span
                      class="type-badge"
                      :class="
                        transaction.jenis === 'pemasukan'
                          ? 'type-income'
                          : 'type-expense'
                      "
                    >

                      {{
                        transaction.jenis === 'pemasukan'
                          ? 'Income'
                          : 'Expense'
                      }}

                    </span>

                  </td>

                  <!-- AMOUNT -->

                  <td class="amount-cell">

                    <span
                      :class="
                        transaction.jenis === 'pemasukan'
                          ? 'income-text'
                          : 'expense-text'
                      "
                    >

                      {{
                        transaction.jenis === 'pemasukan'
                          ? '+ '
                          : '- '
                      }}

                      {{ formatCurrency(transaction.jumlah) }}

                    </span>

                  </td>

                </tr>

              </tbody>

            </table>

          </div>

          <!-- EMPTY -->

          <div
            v-else
            class="empty-state"
          >

            <div class="empty-icon">
              $
            </div>

            <h4>
              No transactions
            </h4>

            <p>
              There are no financial transactions recorded for
              {{ formattedPeriod }}.
            </p>

          </div>

        </section>

        <!-- =========================================
             BOTTOM GRID
        ========================================== -->

        <div class="bottom-grid">

          <!-- =======================================
               EXPENSE BREAKDOWN
          ======================================== -->

          <section class="panel">

            <div class="panel-header">

              <div>

                <h3>
                  Expense Breakdown
                </h3>

                <p>
                  Spending by category for this period.
                </p>

              </div>

            </div>

            <div
              v-if="expenseBreakdown.length"
              class="breakdown-list"
            >

              <div
                v-for="item in expenseBreakdown"
                :key="item.name"
                class="breakdown-item"
              >

                <div class="breakdown-header">

                  <span class="breakdown-name">
                    {{ item.name }}
                  </span>

                  <span class="breakdown-amount">
                    {{ formatCurrency(item.amount) }}
                  </span>

                </div>

                <div class="progress-track">

                  <div
                    class="progress-fill"
                    :style="{
                      width: `${item.percentage}%`
                    }"
                  ></div>

                </div>

                <div class="breakdown-footer">

                  <span>

                    {{ item.count }}

                    transaction{{
                      item.count === 1
                        ? ''
                        : 's'
                    }}

                  </span>

                  <span>
                    {{ item.percentage }}%
                  </span>

                </div>

              </div>

            </div>

            <div
              v-else
              class="small-empty"
            >
              No expenses recorded for this period.
            </div>

          </section>

          <!-- =======================================
               PERIOD SUMMARY
          ======================================== -->

          <section class="panel">

            <div class="panel-header">

              <div>

                <h3>
                  {{ reportPeriodLabel }} Summary
                </h3>

                <p>
                  Quick overview of your financial activity.
                </p>

              </div>

            </div>

            <div class="summary-list">

              <!-- TOTAL -->

              <div class="summary-row">

                <div class="summary-icon purple">
                  #
                </div>

                <div class="summary-info">

                  <span>
                    Total Transactions
                  </span>

                  <strong>
                    {{ periodTransactions.length }}
                  </strong>

                </div>

              </div>

              <!-- INCOME -->

              <div class="summary-row">

                <div class="summary-icon green">
                  ↗
                </div>

                <div class="summary-info">

                  <span>
                    Income Transactions
                  </span>

                  <strong>
                    {{ incomeTransactions.length }}
                  </strong>

                </div>

              </div>

              <!-- EXPENSE -->

              <div class="summary-row">

                <div class="summary-icon red">
                  ↘
                </div>

                <div class="summary-info">

                  <span>
                    Expense Transactions
                  </span>

                  <strong>
                    {{ expenseTransactions.length }}
                  </strong>

                </div>

              </div>

              <!-- LARGEST EXPENSE -->

              <div class="summary-row">

                <div class="summary-icon orange">
                  $
                </div>

                <div class="summary-info">

                  <span>
                    Largest Expense
                  </span>

                  <strong>

                    {{
                      largestExpense
                        ? formatCurrency(
                            largestExpense.jumlah
                          )
                        : 'Rp 0'
                    }}

                  </strong>

                </div>

              </div>

              <!-- TOP CATEGORY -->

              <div class="summary-row">

                <div class="summary-icon purple">
                  ★
                </div>

                <div class="summary-info">

                  <span>
                    Top Expense Category
                  </span>

                  <strong>
                    {{ topExpenseCategory || 'None' }}
                  </strong>

                </div>

              </div>

            </div>

          </section>

        </div>

        <!-- =========================================
             REPORT FOOTER
        ========================================== -->

        <div class="report-footer">

          <div>

            <strong>
              MoneyFlow
            </strong>

            <span>
              {{ reportPeriodLabel }} Financial Report
            </span>

          </div>

          <span>
            Generated for {{ formattedPeriod }}
          </span>

        </div>

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

import { useRouter } from 'vue-router'
import api from '../utils/api'

const router = useRouter()

/* =========================================
   STATE
========================================= */

const loading = ref(true)

const transactions = ref([])

/*
|--------------------------------------------------------------------------
| Report period
|--------------------------------------------------------------------------
|
| daily   = one day
| weekly  = one week
| monthly = one month
| yearly  = one year
|
*/

const reportPeriod = ref('daily')

const selectedDate = ref(
  formatDateForInput(new Date())
)

const selectedMonth = ref(
  formatMonthForInput(new Date())
)

const selectedYear = ref(
  String(new Date().getFullYear())
)

/* =========================================
   PERIOD OPTIONS
========================================= */

const periods = [
  {
    value: 'daily',
    label: 'Daily'
  },
  {
    value: 'weekly',
    label: 'Weekly'
  },
  {
    value: 'monthly',
    label: 'Monthly'
  },
  {
    value: 'yearly',
    label: 'Yearly'
  }
]

/* =========================================
   DATE HELPERS
========================================= */

function formatDateForInput(date) {
  const year = date.getFullYear()

  const month = String(
    date.getMonth() + 1
  ).padStart(2, '0')

  const day = String(
    date.getDate()
  ).padStart(2, '0')

  return `${year}-${month}-${day}`
}

function formatMonthForInput(date) {
  const year = date.getFullYear()

  const month = String(
    date.getMonth() + 1
  ).padStart(2, '0')

  return `${year}-${month}`
}

function parseLocalDate(dateString) {
  const [year, month, day] =
    dateString.split('-').map(Number)

  return new Date(
    year,
    month - 1,
    day
  )
}

/* =========================================
   REPORT LABELS
========================================= */

const reportPeriodLabel = computed(() => {
  if (reportPeriod.value === 'daily') {
    return 'Daily'
  }

  if (reportPeriod.value === 'weekly') {
    return 'Weekly'
  }

  if (reportPeriod.value === 'monthly') {
    return 'Monthly'
  }

  return 'Yearly'
})

const reportTitle = computed(() => {
  return `${reportPeriodLabel.value} Report`
})

const periodLabel = computed(() => {
  if (reportPeriod.value === 'daily') {
    return 'SELECTED DATE'
  }

  if (reportPeriod.value === 'weekly') {
    return 'SELECTED WEEK'
  }

  if (reportPeriod.value === 'monthly') {
    return 'SELECTED MONTH'
  }

  return 'SELECTED YEAR'
})

/* =========================================
   PERIOD RANGE
========================================= */

const periodStart = computed(() => {

  /* DAILY */

  if (reportPeriod.value === 'daily') {
    return parseLocalDate(
      selectedDate.value
    )
  }

  /* WEEKLY */

  if (reportPeriod.value === 'weekly') {

    const date = parseLocalDate(
      selectedDate.value
    )

    /*
     * Monday = first day of week
     */

    const day = date.getDay()

    const difference =
      day === 0
        ? -6
        : 1 - day

    date.setDate(
      date.getDate() + difference
    )

    return date
  }

  /* MONTHLY */

  if (reportPeriod.value === 'monthly') {

    const [year, month] =
      selectedMonth.value
        .split('-')
        .map(Number)

    return new Date(
      year,
      month - 1,
      1
    )
  }

  /* YEARLY */

  return new Date(
    Number(selectedYear.value),
    0,
    1
  )
})

const periodEnd = computed(() => {

  /* DAILY */

  if (reportPeriod.value === 'daily') {

    return parseLocalDate(
      selectedDate.value
    )
  }

  /* WEEKLY */

  if (reportPeriod.value === 'weekly') {

    const start =
      new Date(periodStart.value)

    start.setDate(
      start.getDate() + 6
    )

    return start
  }

  /* MONTHLY */

  if (reportPeriod.value === 'monthly') {

    const start =
      new Date(periodStart.value)

    return new Date(
      start.getFullYear(),
      start.getMonth() + 1,
      0
    )
  }

  /* YEARLY */

  return new Date(
    Number(selectedYear.value),
    11,
    31
  )
})

/* =========================================
   FORMATTED PERIOD
========================================= */

const formattedPeriod = computed(() => {

  const start = periodStart.value
  const end = periodEnd.value

  /* DAILY */

  if (reportPeriod.value === 'daily') {

    return start.toLocaleDateString(
      'en-US',
      {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      }
    )
  }

  /* WEEKLY */

  if (reportPeriod.value === 'weekly') {

    const startText =
      start.toLocaleDateString(
        'en-US',
        {
          month: 'short',
          day: 'numeric'
        }
      )

    const endText =
      end.toLocaleDateString(
        'en-US',
        {
          month: 'short',
          day: 'numeric',
          year: 'numeric'
        }
      )

    return `${startText} – ${endText}`
  }

  /* MONTHLY */

  if (reportPeriod.value === 'monthly') {

    return start.toLocaleDateString(
      'en-US',
      {
        month: 'long',
        year: 'numeric'
      }
    )
  }

  /* YEARLY */

  return String(
    Number(selectedYear.value)
  )
})

/* =========================================
   CHANGE PERIOD
========================================= */

function changePeriod(period) {
  reportPeriod.value = period
}

/* =========================================
   PREVIOUS PERIOD
========================================= */

function previousPeriod() {

  /* DAILY */

  if (reportPeriod.value === 'daily') {

    const date =
      parseLocalDate(
        selectedDate.value
      )

    date.setDate(
      date.getDate() - 1
    )

    selectedDate.value =
      formatDateForInput(date)

    return
  }

  /* WEEKLY */

  if (reportPeriod.value === 'weekly') {

    const date =
      parseLocalDate(
        selectedDate.value
      )

    date.setDate(
      date.getDate() - 7
    )

    selectedDate.value =
      formatDateForInput(date)

    return
  }

  /* MONTHLY */

  if (reportPeriod.value === 'monthly') {

    const date =
      parseLocalDate(
        `${selectedMonth.value}-01`
      )

    date.setMonth(
      date.getMonth() - 1
    )

    selectedMonth.value =
      formatMonthForInput(date)

    return
  }

  /* YEARLY */

  selectedYear.value =
    String(
      Number(selectedYear.value) - 1
    )
}

/* =========================================
   NEXT PERIOD
========================================= */

function nextPeriod() {

  /* DAILY */

  if (reportPeriod.value === 'daily') {

    const date =
      parseLocalDate(
        selectedDate.value
      )

    date.setDate(
      date.getDate() + 1
    )

    selectedDate.value =
      formatDateForInput(date)

    return
  }

  /* WEEKLY */

  if (reportPeriod.value === 'weekly') {

    const date =
      parseLocalDate(
        selectedDate.value
      )

    date.setDate(
      date.getDate() + 7
    )

    selectedDate.value =
      formatDateForInput(date)

    return
  }

  /* MONTHLY */

  if (reportPeriod.value === 'monthly') {

    const date =
      parseLocalDate(
        `${selectedMonth.value}-01`
      )

    date.setMonth(
      date.getMonth() + 1
    )

    selectedMonth.value =
      formatMonthForInput(date)

    return
  }

  /* YEARLY */

  selectedYear.value =
    String(
      Number(selectedYear.value) + 1
    )
}

/* =========================================
   FETCH TRANSACTIONS
========================================= */

async function fetchTransactions() {

  loading.value = true

  try {

    const response =
      await api.get('/transaksi')

    transactions.value =
      response.data?.data ||
      response.data ||
      []

  } catch (error) {

    console.error(
      'Failed to fetch transactions:',
      error
    )

    if (
      error.response?.status === 401
    ) {

      localStorage.removeItem(
        'auth_token'
      )

      localStorage.removeItem(
        'remember'
      )

      router.push('/login')
    }

  } finally {

    loading.value = false
  }
}

/* =========================================
   PERIOD TRANSACTIONS
========================================= */

const periodTransactions = computed(() => {

  const start =
    formatDateForInput(
      periodStart.value
    )

  const end =
    formatDateForInput(
      periodEnd.value
    )

  return transactions.value
    .filter(transaction => {

      if (!transaction.tanggal) {
        return false
      }

      const transactionDate =
        String(
          transaction.tanggal
        ).slice(0, 10)

      return (
        transactionDate >= start &&
        transactionDate <= end
      )
    })
    .sort((a, b) => {

      const dateA =
        String(a.tanggal || '')

      const dateB =
        String(b.tanggal || '')

      return dateB.localeCompare(dateA)
    })
})

/* =========================================
   INCOME / EXPENSE
========================================= */

const incomeTransactions = computed(() => {

  return periodTransactions.value.filter(
    transaction =>
      transaction.jenis === 'pemasukan'
  )
})

const expenseTransactions = computed(() => {

  return periodTransactions.value.filter(
    transaction =>
      transaction.jenis === 'pengeluaran'
  )
})

/* =========================================
   TOTAL INCOME
========================================= */

const periodIncome = computed(() => {

  return incomeTransactions.value.reduce(
    (total, transaction) => {

      return (
        total +
        Number(
          transaction.jumlah || 0
        )
      )

    },
    0
  )
})

/* =========================================
   TOTAL EXPENSE
========================================= */

const periodExpenses = computed(() => {

  return expenseTransactions.value.reduce(
    (total, transaction) => {

      return (
        total +
        Number(
          transaction.jumlah || 0
        )
      )

    },
    0
  )
})

/* =========================================
   NET
========================================= */

const periodNet = computed(() => {

  return (
    periodIncome.value -
    periodExpenses.value
  )
})

/* =========================================
   CATEGORY
========================================= */

function getCategoryName(transaction) {

  return (
    transaction.kategori?.nama_kategori ||
    transaction.nama_kategori ||
    'Uncategorized'
  )
}

/* =========================================
   WALLET
========================================= */

function getWalletName(transaction) {

  return (
    transaction.dompet?.nama_dompet ||
    transaction.nama_dompet ||
    'Unknown wallet'
  )
}

/* =========================================
   TRANSACTION DATE
========================================= */

function formatTransactionDate(value) {

  if (!value) {
    return ''
  }

  const dateString =
    String(value).slice(0, 10)

  const date =
    parseLocalDate(dateString)

  return date.toLocaleDateString(
    'en-US',
    {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    }
  )
}

/* =========================================
   EXPENSE BREAKDOWN
========================================= */

const expenseBreakdown = computed(() => {

  const grouped = {}

  expenseTransactions.value.forEach(
    transaction => {

      const category =
        getCategoryName(transaction)

      const amount =
        Number(
          transaction.jumlah || 0
        )

      if (!grouped[category]) {

        grouped[category] = {
          name: category,
          amount: 0,
          count: 0
        }
      }

      grouped[category].amount +=
        amount

      grouped[category].count +=
        1
    }
  )

  const total =
    periodExpenses.value

  return Object.values(grouped)
    .map(item => ({

      ...item,

      percentage:
        total > 0
          ? Math.round(
              (
                item.amount /
                total
              ) * 100
            )
          : 0

    }))
    .sort(
      (a, b) =>
        b.amount - a.amount
    )
})

/* =========================================
   LARGEST EXPENSE
========================================= */

const largestExpense = computed(() => {

  if (
    !expenseTransactions.value.length
  ) {
    return null
  }

  return [
    ...expenseTransactions.value
  ].sort(
    (a, b) =>
      Number(b.jumlah || 0) -
      Number(a.jumlah || 0)
  )[0]
})

/* =========================================
   TOP CATEGORY
========================================= */

const topExpenseCategory = computed(() => {

  if (
    !expenseBreakdown.value.length
  ) {
    return null
  }

  return expenseBreakdown.value[0].name
})

/* =========================================
   CURRENCY
========================================= */

function formatCurrency(value) {

  return new Intl.NumberFormat(
    'id-ID',
    {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }
  ).format(
    Number(value || 0)
  )
}

/* =========================================
   PRINT
========================================= */

function printReport() {
  window.print()
}

/* =========================================
   INITIAL LOAD
========================================= */

onMounted(() => {
  fetchTransactions()
})
</script>

<style scoped>

/* =========================================
   PAGE
========================================= */

.report-page {
  min-height: 100vh;
  padding: 28px 30px 35px;
  background: #f7f8fc;
  color: #25263a;
}

.report-content {
  width: 100%;
  max-width: 1220px;
  margin: 0 auto;
}

/* =========================================
   HEADER
========================================= */

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 20px;
}

.eyebrow {
  display: block;
  margin-bottom: 5px;
  color: #6c5ce7;
  font-size: 9px;
  font-weight: 800;
  letter-spacing: 1.2px;
}

.page-header h1 {
  margin: 0;
  color: #14213d;
  font-size: 25px;
  line-height: 1.2;
  font-weight: 750;
  letter-spacing: -0.5px;
}

.page-header p {
  margin: 6px 0 0;
  color: #9299aa;
  font-size: 11px;
  line-height: 1.5;
}

.print-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  min-height: 34px;
  padding: 0 13px;
  border: 1px solid #e1e3eb;
  border-radius: 8px;
  background: #ffffff;
  color: #4e5568;
  font-size: 10px;
  font-weight: 700;
  cursor: pointer;
  transition:
    background 0.18s ease,
    border-color 0.18s ease,
    color 0.18s ease,
    transform 0.18s ease;
}

.print-button:hover {
  border-color: #d5d0fb;
  background: #faf9ff;
  color: #6c5ce7;
}

.print-button:active {
  transform: translateY(1px);
}

/* =========================================
   PERIOD PANEL
========================================= */

.period-panel {
  margin-bottom: 12px;
  padding: 6px;
  border: 1px solid #e8eaf1;
  border-radius: 13px;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
}

.period-tabs {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 5px;
}

.period-tab {
  min-height: 34px;
  border: none;
  border-radius: 8px;
  background: transparent;
  color: #858c9e;
  font-family: inherit;
  font-size: 10px;
  font-weight: 700;
  cursor: pointer;
  transition:
    background 0.18s ease,
    color 0.18s ease;
}

.period-tab:hover {
  background: #f6f4ff;
  color: #6c5ce7;
}

.period-tab.active {
  background: #eeebff;
  color: #6c5ce7;
}

/* =========================================
   DATE PANEL
========================================= */

.date-panel {
  display: grid;
  grid-template-columns: 38px 1fr 38px;
  align-items: center;
  gap: 12px;
  min-height: 88px;
  margin-bottom: 17px;
  padding: 14px 17px;
  border: 1px solid #e8eaf1;
  border-radius: 13px;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
}

.date-nav-button {
  width: 34px;
  height: 34px;
  border: 1px solid #e4e6ed;
  border-radius: 8px;
  background: #ffffff;
  color: #6c5ce7;
  font-size: 24px;
  line-height: 1;
  cursor: pointer;
  transition:
    background 0.18s ease,
    border-color 0.18s ease,
    transform 0.18s ease;
}

.date-nav-button:hover {
  border-color: #d7d2fb;
  background: #f5f3ff;
}

.date-nav-button:active {
  transform: scale(0.96);
}

.date-center {
  text-align: center;
}

.date-label {
  display: block;
  margin-bottom: 3px;
  color: #a0a6b5;
  font-size: 8px;
  font-weight: 800;
  letter-spacing: 1px;
}

.selected-date {
  color: #25263a;
  font-size: 13px;
  font-weight: 700;
}

.date-input,
.year-input {
  display: block;
  width: 130px;
  height: 27px;
  margin: 7px auto 0;
  padding: 4px 7px;
  border: 1px solid #e6e8ee;
  border-radius: 6px;
  outline: none;
  background: #fafbfc;
  color: #777f91;
  font-family: inherit;
  font-size: 9px;
  text-align: center;
  cursor: pointer;
}

.date-input:focus,
.year-input:focus {
  border-color: #bdb5f7;
}

.year-input {
  width: 90px;
}

/* =========================================
   STAT CARDS
========================================= */

.statement-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 17px;
  margin-bottom: 17px;
}

.stat-card {
  min-height: 145px;
  padding: 18px;
  border: 1px solid #e9ebf2;
  border-radius: 13px;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
}

.stat-top {
  display: flex;
  align-items: center;
  gap: 9px;
  margin-bottom: 15px;
}

.stat-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 800;
}

.income-icon {
  background: #e8f8f0;
  color: #19a566;
}

.expense-icon {
  background: #fff0f0;
  color: #ef6565;
}

.net-icon {
  background: #eeebff;
  color: #6c5ce7;
}

.stat-label {
  color: #9299aa;
  font-size: 9px;
  font-weight: 800;
  letter-spacing: 0.8px;
}

.stat-value {
  margin-bottom: 5px;
  color: #25263a;
  font-size: 20px;
  line-height: 1.2;
  font-weight: 750;
  letter-spacing: -0.4px;
}

.stat-description {
  color: #a0a6b5;
  font-size: 9px;
}

/* =========================================
   COLORS
========================================= */

.income-text {
  color: #19a566 !important;
}

.expense-text {
  color: #e95f65 !important;
}

/* =========================================
   PANELS
========================================= */

.panel {
  overflow: hidden;
  border: 1px solid #e8eaf1;
  border-radius: 13px;
  background: #ffffff;
  box-shadow:
    0 8px 25px rgba(30, 40, 80, 0.025);
}

.transaction-panel {
  margin-bottom: 17px;
}

.panel-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;
  padding: 19px 19px 13px;
}

.panel-header h3 {
  margin: 0;
  color: #25263a;
  font-size: 13px;
  font-weight: 750;
}

.panel-header p {
  margin: 4px 0 0;
  color: #a0a6b5;
  font-size: 9px;
  line-height: 1.4;
}

.panel-badge {
  flex-shrink: 0;
  padding: 5px 8px;
  border-radius: 6px;
  background: #f0eeff;
  color: #6c5ce7;
  font-size: 8px;
  font-weight: 800;
}

/* =========================================
   TABLE
========================================= */

.table-wrapper {
  width: 100%;
  overflow-x: auto;
  padding: 0 19px 19px;
}

.transaction-table {
  width: 100%;
  min-width: 780px;
  border-collapse: collapse;
}

.transaction-table th {
  padding: 10px;
  border-bottom: 1px solid #eceef3;
  color: #a0a6b5;
  font-size: 8px;
  font-weight: 800;
  letter-spacing: 0.7px;
  text-align: left;
  white-space: nowrap;
}

.transaction-table td {
  padding: 12px 10px;
  border-bottom: 1px solid #f0f1f5;
  color: #596074;
  font-size: 9px;
  vertical-align: middle;
}

.transaction-table tbody tr:last-child td {
  border-bottom: none;
}

.transaction-table tbody tr {
  transition: background 0.15s ease;
}

.transaction-table tbody tr:hover {
  background: #fafaff;
}

.transaction-name {
  color: #30354a;
  font-size: 10px;
  font-weight: 700;
}

.transaction-description {
  max-width: 230px;
  margin-top: 3px;
  overflow: hidden;
  color: #999faf;
  font-size: 8px;
  line-height: 1.4;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.transaction-date {
  margin-top: 3px;
  color: #b0b5c1;
  font-size: 7px;
}

.category-text,
.wallet-text {
  color: #687083;
  font-size: 9px;
}

.wallet-text {
  font-weight: 600;
}

.type-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 55px;
  padding: 4px 7px;
  border-radius: 5px;
  font-size: 7px;
  font-weight: 800;
}

.type-income {
  background: #eaf8f1;
  color: #199f63;
}

.type-expense {
  background: #fff0f0;
  color: #df6065;
}

.amount-cell {
  text-align: right !important;
  white-space: nowrap;
  font-weight: 750;
}

/* =========================================
   EMPTY
========================================= */

.empty-state {
  padding: 40px 20px 45px;
  text-align: center;
}

.empty-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  margin: 0 auto 10px;
  border-radius: 10px;
  background: #eeebff;
  color: #6c5ce7;
  font-size: 15px;
  font-weight: 800;
}

.empty-state h4 {
  margin: 0 0 5px;
  color: #34394d;
  font-size: 12px;
}

.empty-state p {
  max-width: 350px;
  margin: 0 auto;
  color: #a0a6b5;
  font-size: 9px;
  line-height: 1.5;
}

.small-empty {
  padding: 25px 19px 30px;
  color: #a0a6b5;
  font-size: 9px;
  text-align: center;
}

/* =========================================
   BOTTOM GRID
========================================= */

.bottom-grid {
  display: grid;
  grid-template-columns: 1fr 1.05fr;
  gap: 17px;
}

/* =========================================
   BREAKDOWN
========================================= */

.breakdown-list {
  padding: 0 19px 19px;
}

.breakdown-item {
  margin-bottom: 16px;
}

.breakdown-item:last-child {
  margin-bottom: 0;
}

.breakdown-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
  margin-bottom: 7px;
}

.breakdown-name {
  color: #5f6678;
  font-size: 9px;
  font-weight: 650;
}

.breakdown-amount {
  color: #33384b;
  font-size: 9px;
  font-weight: 750;
}

.progress-track {
  width: 100%;
  height: 6px;
  overflow: hidden;
  border-radius: 10px;
  background: #eeeef4;
}

.progress-fill {
  height: 100%;
  min-width: 2px;
  border-radius: inherit;
  background: #6c5ce7;
  transition: width 0.4s ease;
}

.breakdown-footer {
  display: flex;
  justify-content: space-between;
  margin-top: 5px;
  color: #a7adba;
  font-size: 7px;
}

/* =========================================
   SUMMARY
========================================= */

.summary-list {
  padding: 0 19px 15px;
}

.summary-row {
  display: flex;
  align-items: center;
  gap: 10px;
  min-height: 48px;
  border-bottom: 1px solid #f0f1f5;
}

.summary-row:last-child {
  border-bottom: none;
}

.summary-icon {
  display: flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 7px;
  font-size: 11px;
  font-weight: 800;
}

.summary-icon.purple {
  background: #eeebff;
  color: #6c5ce7;
}

.summary-icon.green {
  background: #e8f8f0;
  color: #19a566;
}

.summary-icon.red {
  background: #fff0f0;
  color: #e95f65;
}

.summary-icon.orange {
  background: #fff5e8;
  color: #e99a35;
}

.summary-info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  width: 100%;
}

.summary-info span {
  color: #777f91;
  font-size: 9px;
}

.summary-info strong {
  max-width: 160px;
  overflow: hidden;
  color: #30354a;
  font-size: 9px;
  font-weight: 750;
  text-align: right;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* =========================================
   FOOTER
========================================= */

.report-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;
  padding: 20px 2px 0;
  color: #a2a8b5;
  font-size: 8px;
}

.report-footer div {
  display: flex;
  align-items: center;
  gap: 7px;
}

.report-footer strong {
  color: #6c5ce7;
  font-size: 9px;
}

.report-footer span {
  color: #a2a8b5;
}

/* =========================================
   LOADING
========================================= */

.loading-state {
  width: 100%;
}

.skeleton-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 17px;
  margin-bottom: 17px;
}

.skeleton-card {
  min-height: 145px;
  padding: 18px;
  border: 1px solid #e9ebf2;
  border-radius: 13px;
  background: #ffffff;
}

.skeleton {
  background:
    linear-gradient(
      90deg,
      #eceef4 25%,
      #f6f7fa 37%,
      #eceef4 63%
    );
  background-size: 400% 100%;
  animation:
    skeleton-shimmer 1.4s ease infinite;
}

.skeleton-icon {
  width: 32px;
  height: 32px;
  margin-bottom: 15px;
  border-radius: 8px;
}

.skeleton-small {
  width: 70px;
  height: 8px;
  margin-bottom: 10px;
  border-radius: 4px;
}

.skeleton-value {
  width: 125px;
  height: 20px;
  margin-bottom: 8px;
  border-radius: 5px;
}

.skeleton-description {
  width: 90px;
  height: 7px;
  border-radius: 4px;
}

.skeleton-panel {
  min-height: 250px;
  margin-bottom: 17px;
  padding: 19px;
  border: 1px solid #e8eaf1;
  border-radius: 13px;
  background: #ffffff;
}

.skeleton-title {
  width: 145px;
  height: 12px;
  margin-bottom: 20px;
  border-radius: 4px;
}

.skeleton-table {
  display: flex;
  flex-direction: column;
  gap: 13px;
}

.skeleton-row {
  display: grid;
  grid-template-columns:
    1.5fr
    1fr
    1fr
    0.7fr
    0.8fr;
  gap: 12px;
}

.skeleton-row .skeleton {
  height: 10px;
  border-radius: 4px;
}

.skeleton-bottom {
  display: grid;
  grid-template-columns: 1fr 1.05fr;
  gap: 17px;
}

.skeleton-bottom .skeleton-panel {
  min-height: 220px;
  margin-bottom: 0;
}

.skeleton-line {
  width: 100%;
  height: 9px;
  margin-bottom: 17px;
  border-radius: 4px;
}

@keyframes skeleton-shimmer {

  0% {
    background-position: 100% 0;
  }

  100% {
    background-position: -100% 0;
  }

}

/* =========================================
   TABLET
========================================= */

@media (max-width: 1100px) {

  .statement-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .bottom-grid,
  .skeleton-bottom {
    grid-template-columns: 1fr;
  }

  .skeleton-bottom .skeleton-panel {
    margin-bottom: 17px;
  }

}

/* =========================================
   MOBILE
========================================= */

@media (max-width: 700px) {

  .report-page {
    padding: 17px 14px 90px;
  }

  .page-header {
    align-items: flex-start;
    flex-direction: column;
    margin-bottom: 16px;
  }

  .page-header h1 {
    font-size: 22px;
  }

  .print-button {
    width: 100%;
  }

  .period-panel {
    margin-bottom: 10px;
  }

  .period-tabs {
    grid-template-columns: repeat(2, 1fr);
  }

  .period-tab {
    min-height: 38px;
  }

  .date-panel {
    grid-template-columns: 34px 1fr 34px;
    gap: 8px;
    padding: 12px;
  }

  .date-nav-button {
    width: 32px;
    height: 32px;
  }

  .selected-date {
    font-size: 11px;
  }

  .statement-grid,
  .skeleton-stats {
    grid-template-columns: 1fr;
  }

  .stat-card,
  .skeleton-card {
    min-height: 125px;
  }

  .panel-header {
    align-items: flex-start;
    flex-direction: column;
    padding: 16px 15px 12px;
  }

  .panel-badge {
    align-self: flex-start;
  }

  .table-wrapper {
    padding: 0 15px 15px;
  }

  .bottom-grid {
    gap: 14px;
  }

  .breakdown-list,
  .summary-list {
    padding-left: 15px;
    padding-right: 15px;
  }

  .report-footer {
    align-items: flex-start;
    flex-direction: column;
    padding-top: 17px;
  }

  .skeleton-panel {
    padding: 15px;
  }

  .skeleton-row {
    grid-template-columns:
      1.5fr
      1fr
      1fr
      0.7fr
      0.8fr;

    min-width: 700px;
  }

  .skeleton-table {
    overflow: hidden;
  }

}

/* =========================================
   SMALL MOBILE
========================================= */

@media (max-width: 400px) {

  .report-page {
    padding-left: 11px;
    padding-right: 11px;
  }

  .page-header h1 {
    font-size: 20px;
  }

  .period-tab {
    font-size: 9px;
  }

  .date-panel {
    grid-template-columns: 30px 1fr 30px;
  }

  .date-nav-button {
    width: 29px;
    height: 29px;
    font-size: 21px;
  }

  .selected-date {
    font-size: 10px;
  }

  .date-input {
    width: 115px;
  }

  .year-input {
    width: 80px;
  }

  .stat-card {
    padding: 15px;
  }

  .stat-value {
    font-size: 18px;
  }

  .summary-info span {
    font-size: 8px;
  }

  .summary-info strong {
    max-width: 125px;
    font-size: 8px;
  }

}

/* =========================================
   PRINT
========================================= */

@media print {

  .report-page {
    min-height: auto;
    padding: 0;
    background: #ffffff;
  }

  .report-content {
    max-width: none;
  }

  .print-button,
  .period-panel,
  .date-panel {
    display: none !important;
  }

  .statement-grid {
    gap: 10px;
    margin-bottom: 10px;
  }

  .stat-card,
  .panel {
    box-shadow: none;
  }

  .transaction-panel {
    margin-bottom: 10px;
  }

  .bottom-grid {
    gap: 10px;
  }

  .panel-header {
    padding: 12px;
  }

  .table-wrapper {
    padding: 0 12px 12px;
  }

  .report-footer {
    padding-top: 12px;
  }

  .progress-fill {
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
  }

  .type-badge,
  .stat-icon,
  .summary-icon,
  .panel-badge {
    print-color-adjust: exact;
    -webkit-print-color-adjust: exact;
  }

}
</style>