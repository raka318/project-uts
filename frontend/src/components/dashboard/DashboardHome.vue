<template>
  <section class="dashboard">

<!-- =========================
     LOADING SKELETON
========================== -->
<div v-if="loading" class="dashboard-skeleton">

  <!-- Skeleton stats -->
  <div class="stats-grid">

    <div
      v-for="i in 4"
      :key="'stat-' + i"
      class="stat-card skeleton-card"
    >
      <div class="skeleton-icon"></div>

      <div class="skeleton-line small"></div>

      <div class="skeleton-line value"></div>

      <div class="skeleton-line description"></div>
    </div>

  </div>


  <!-- Skeleton middle section -->
  <div class="middle-grid">

    <!-- Spending skeleton -->
    <div class="panel spending-panel skeleton-panel">

      <div class="panel-header">

        <div>
          <div class="skeleton-line title"></div>
          <div class="skeleton-line subtitle"></div>
        </div>

        <div class="skeleton-button"></div>

      </div>


      <div class="skeleton-chart">

        <div class="skeleton-chart-lines">

          <div class="skeleton-chart-line"></div>
          <div class="skeleton-chart-line"></div>
          <div class="skeleton-chart-line"></div>
          <div class="skeleton-chart-line"></div>
          <div class="skeleton-chart-line"></div>

        </div>

        <div class="skeleton-chart-area"></div>

      </div>

    </div>


    <!-- Transactions skeleton -->
    <div class="panel transactions-panel skeleton-panel">

      <div class="panel-header">

        <div>
          <div class="skeleton-line title"></div>
          <div class="skeleton-line subtitle"></div>
        </div>

        <div class="skeleton-link"></div>

      </div>


      <div class="skeleton-transactions">

        <div
          v-for="i in 5"
          :key="'transaction-' + i"
          class="skeleton-transaction"
        >

          <div class="skeleton-transaction-icon"></div>

          <div class="skeleton-transaction-info">
            <div class="skeleton-line transaction-name"></div>
            <div class="skeleton-line transaction-category"></div>
          </div>

          <div class="skeleton-transaction-amount">
            <div class="skeleton-line amount"></div>
            <div class="skeleton-line date"></div>
          </div>

        </div>

      </div>

    </div>

  </div>


  <!-- Skeleton bottom section -->
  <div class="bottom-grid">

    <!-- Budget skeleton -->
    <div class="panel budget-panel skeleton-panel">

      <div class="panel-header">

        <div>
          <div class="skeleton-line title"></div>
          <div class="skeleton-line subtitle"></div>
        </div>

        <div class="skeleton-link"></div>

      </div>


      <div class="skeleton-budgets">

        <div
          v-for="i in 3"
          :key="'budget-' + i"
          class="skeleton-budget"
        >

          <div class="skeleton-budget-top">

            <div class="skeleton-budget-name"></div>

            <div class="skeleton-budget-number"></div>

          </div>

          <div class="skeleton-progress"></div>

        </div>

      </div>

    </div>


    <!-- Savings skeleton -->
    <div class="panel savings-panel skeleton-panel">

      <div class="panel-header">

        <div>
          <div class="skeleton-line title"></div>
          <div class="skeleton-line subtitle"></div>
        </div>

        <div class="skeleton-plus"></div>

      </div>


      <div class="skeleton-savings">

        <div class="skeleton-donut"></div>

        <div class="skeleton-goal-info">

          <div class="skeleton-line goal-title"></div>

          <div class="skeleton-line goal-text"></div>

          <div class="skeleton-line goal-deadline"></div>

        </div>

      </div>

    </div>

  </div>


  <!-- Skeleton quick actions -->
  <div class="quick-actions">

    <div
      v-for="i in 3"
      :key="'action-' + i"
      class="skeleton-action"
    >

      <div class="skeleton-action-icon"></div>

      <div class="skeleton-action-text"></div>

    </div>

  </div>

</div>


    <!-- =========================
         ERROR
    ========================== -->
    <div v-else-if="errorMsg" class="state-banner error">

      <div class="error-icon">
        !
      </div>

      <strong>{{ errorMsg }}</strong>

      <button @click="loadDashboard">
        Try again
      </button>

    </div>


    <!-- =========================
         DASHBOARD CONTENT
    ========================== -->
    <template v-else>

      <!-- =========================
           STAT CARDS
      ========================== -->
      <div class="stats-grid">

        <!-- TOTAL BALANCE -->
        <div class="stat-card">

          <div class="stat-top">
            <div class="stat-icon purple">
              $
            </div>
          </div>

          <span class="stat-label">
            Total Balance
          </span>

          <strong class="stat-value">
            {{ rawWallets.length ? 'Rp' + formatNumber(animatedBalance) : '—' }}
          </strong>

          <span class="stat-description">
            Across all wallets
          </span>

        </div>


        <!-- INCOME -->
        <div class="stat-card">

          <div class="stat-top">
            <div class="stat-icon green">
              ↗
            </div>
          </div>

          <span class="stat-label">
            Income
          </span>

          <strong class="stat-value">
            {{ rawTransactions.length ? 'Rp' + formatNumber(animatedIncome) : '—' }}
          </strong>

          <span class="stat-description">
            All time
          </span>

        </div>


        <!-- EXPENSE -->
        <div class="stat-card">

          <div class="stat-top">
            <div class="stat-icon red">
              ↘
            </div>
          </div>

          <span class="stat-label">
            Expenses
          </span>

          <strong class="stat-value">
            {{ rawTransactions.length ? 'Rp' + formatNumber(animatedExpenses) : '—' }}
          </strong>

          <span class="stat-description">
            All time
          </span>

        </div>


        <!-- SAVINGS -->
        <div class="stat-card">

          <div class="stat-top">
            <div class="stat-icon orange">
              $
            </div>
          </div>

          <span class="stat-label">
            Savings Goal
          </span>

          <strong class="stat-value">
            {{ primaryGoal ? animatedPercent + '%' : '—' }}
          </strong>

          <span class="stat-description">
            {{ primaryGoal ? primaryGoal.name : 'No goal set' }}
          </span>

        </div>

      </div>


      <!-- =========================
           MIDDLE GRID
      ========================== -->
      <div class="middle-grid">

        <!-- =========================
             SPENDING
        ========================== -->
        <div class="panel spending-panel">

          <div class="panel-header">

            <div>
              <h3>Spending Overview</h3>

              <p>
                {{ subtitleText }}
              </p>
            </div>

            <div class="period-dropdown" ref="periodDropdownRef">

              <button class="month-button" @click.stop="togglePeriodMenu">
                {{ periodLabel }}
                <span>⌄</span>
              </button>

              <div v-if="showPeriodMenu" class="period-menu">

                <button
                  v-for="opt in periodOptions"
                  :key="opt.value"
                  :class="{ active: opt.value === chartPeriod }"
                  @click.stop="selectPeriod(opt.value)"
                >
                  {{ opt.label }}
                </button>

              </div>

            </div>

          </div>


          <div class="chart">

            <!-- EMPTY SPENDING STATE -->
            <div
              v-if="!transactions.length"
              class="chart-empty"
            >
              <div class="chart-empty-icon">↗</div>
              <strong>No spending data yet</strong>
              <span>Your spending activity will appear here</span>
            </div>

            <!-- CHART -->
            <template v-else>

              <div
                v-for="(label, i) in yAxisLabels"
                :key="'y-' + i"
                class="chart-label"
                :style="{ top: label.top + 'px' }"
              >
                {{ label.text }}
              </div>

              <div class="grid-line line-1"></div>
              <div class="grid-line line-2"></div>
              <div class="grid-line line-3"></div>
              <div class="grid-line line-4"></div>
              <div class="grid-line line-5"></div>

              <svg
                class="spending-svg"
                viewBox="0 0 800 260"
                preserveAspectRatio="none"
              >
                <defs>
                  <linearGradient
                    id="chartFill"
                    x1="0"
                    y1="0"
                    x2="0"
                    y2="1"
                  >
                    <stop
                      offset="0%"
                      stop-color="rgba(108,92,231,0.22)"
                    />
                    <stop
                      offset="100%"
                      stop-color="rgba(108,92,231,0)"
                    />
                  </linearGradient>
                </defs>

                <g
                  class="chart-group"
                  :class="{ animate: chartAnimated }"
                >
                  <path
                    class="chart-area"
                    :d="chartAreaPath"
                  />

                  <path
                    class="chart-line"
                    :d="chartLinePath"
                  />
                </g>
              </svg>

              <div class="days">
                <span v-for="(label, i) in xLabels" :key="'x-' + i">
                  {{ label }}
                </span>
              </div>

            </template>

          </div>

        </div>


        <!-- =========================
             RECENT TRANSACTIONS
        ========================== -->
        <div class="panel transactions-panel">

          <div class="panel-header">

            <div>
              <h3>Recent Transactions</h3>

              <p>
                Your latest activity
              </p>
            </div>

            <button
                class="view-button"
                @click="router.push('/transactions')"
              >
                View all 
              </button>

          </div>


          <div class="transaction-list">

            <p
              v-if="!transactions.length"
              class="empty-note"
            >
              No transactions yet.
            </p>


            <div
              v-for="(transaction, index) in transactions"
              :key="transaction.id"
              class="transaction"
              :style="{ animationDelay: (index * 0.09) + 's' }"
            >

              <div
                class="transaction-icon"
                :class="transaction.color"
              >
                {{ transaction.icon }}
              </div>


              <div class="transaction-info">

                <strong>
                  {{ transaction.name }}
                </strong>

                <span>
                  {{ transaction.category }}
                </span>

              </div>


              <div class="transaction-amount">

                <strong
                  :class="{
                    income: transaction.type === 'income'
                  }"
                >
                  {{ transaction.amount }}
                </strong>

                <span>
                  {{ transaction.date }}
                </span>

              </div>

            </div>

          </div>

        </div>

      </div>


      <!-- =========================
           BOTTOM GRID
      ========================== -->
      <div class="bottom-grid">

        <!-- =========================
             BUDGET
        ========================== -->
        <div class="panel budget-panel">

          <div class="panel-header">

            <div>
              <h3>Budget Overview</h3>

              <p>
                Track your monthly spending limits
              </p>
            </div>

              <button
                class="view-button"
                @click="router.push('/budgets')"
              >
                View budgets
              </button>

          </div>


          <div class="budget-list">

            <p
              v-if="!budgets.length"
              class="empty-note"
            >
              No budgets set yet.
            </p>


            <div
              v-for="budget in budgets"
              :key="budget.id"
              class="budget-item"
            >

              <div class="budget-title">

                <div class="budget-name">

                  <span
                    class="budget-dot"
                    :class="budget.color"
                  ></span>

                  {{ budget.name }}

                </div>


                <span>
                  {{ budget.spent }} / {{ budget.limit }}
                </span>

              </div>


              <div class="progress">

                <div
                  class="progress-fill"
                  :class="budget.color"
                  :style="{
                    width: budget.percent + '%'
                  }"
                ></div>

              </div>

            </div>

          </div>

        </div>


        <!-- =========================
             SAVINGS
        ========================== -->
        <div class="panel savings-panel">

          <div class="panel-header">

            <div>
              <h3>Savings Goals</h3>

              <p>
                Keep working toward your goals
              </p>
            </div>

                  <button
                    class="plus-button"
                    @click="router.push('/savings')"
                  >
                    +
                  </button>

          </div>


          <div
            v-if="primaryGoal"
            class="savings-content"
          >

            <div
              class="donut"
              :style="{
                background: donutGradient
              }"
            >

              <div class="donut-inner">

                <strong>
                  {{ animatedPercent }}%
                </strong>

                <span>
                  Complete
                </span>

              </div>

            </div>


            <div class="goal-info">

              <h3>
                {{ primaryGoal.name }}
              </h3>

              <p>
                Rp{{ formatNumber(animatedSaved) }}
                saved of
                Rp{{ formatNumber(primaryGoal.target) }}
              </p>

              <span v-if="primaryGoal.deadline">
                Target: {{ primaryGoal.deadline }}
              </span>

            </div>

          </div>


          <p
            v-else
            class="empty-note savings-empty"
          >
            No savings goals yet.
          </p>

        </div>

      </div>


      <!-- =========================
           QUICK ACTIONS
      ========================== -->
      <div class="quick-actions">

        <button @click="router.push('/transactions')">
          <span>+</span>
          Add Transaction
        </button>

        <button @click="router.push('/budgets')">
          <span>▣</span>
          Manage Budget
        </button>

        <button @click="router.push('/savings')">
          <span>$</span>
          Add Savings Goal
        </button>

      </div>

    </template>

  </section>
</template>


<script setup>

import {
  ref,
  computed,
  onMounted,
  onUnmounted,
  watch,
  nextTick
} from 'vue'
import { useRouter } from 'vue-router'

import api from '../../utils/api.js'

const router = useRouter()

const userName = ref('User')

const greeting = computed(() => {
  const hour = new Date().getHours()

  if (hour < 12) return 'Good morning'
  if (hour < 18) return 'Good afternoon'

  return 'Good evening'
})


// =====================================================
// RAW API DATA
// =====================================================

const rawTransactions = ref([])
const rawBudgets = ref([])
const rawGoals = ref([])
const rawWallets = ref([])


// =====================================================
// PAGE STATE
// =====================================================

const loading = ref(true)
const errorMsg = ref('')


// =====================================================
// LOAD DASHBOARD DATA
// =====================================================

async function loadDashboard() {

  const token =
    localStorage.getItem('token') ||
    localStorage.getItem('auth_token') ||
    localStorage.getItem('access_token')

  if (!token) {
    errorMsg.value = 'Please log in to view your dashboard.'
    router.push('/login')
    loading.value = false
    return
  }

  const storedUser = localStorage.getItem('user')
  if (storedUser) {
    try {
      const parsed = JSON.parse(storedUser)
      if (parsed?.name) {
        userName.value = parsed.name
      }
    } catch (error) {
      console.warn('Failed to parse stored user:', error)
    }
  }

  loading.value = true
  errorMsg.value = ''

  try {

    console.log('=================================')
    console.log('LOADING DASHBOARD')
    console.log('=================================')

    console.log(
      'API Base:',
      import.meta.env.VITE_API_BASE_URL
    )


    // -------------------------------------------------
    // CHECK TOKEN
    // -------------------------------------------------

    const token =
      localStorage.getItem('token') ||
      localStorage.getItem('auth_token') ||
      localStorage.getItem('access_token')

    console.log(
      'Authentication token:',
      token ? 'FOUND' : 'NOT FOUND'
    )


    // -------------------------------------------------
    // REQUEST ALL DASHBOARD DATA
    // -------------------------------------------------

    const results = await Promise.allSettled([

      api.get('/transaksi'),

      api.get('/anggaran'),

      api.get('/tujuan-tabungan'),

      api.get('/dompet'),

    ])


    // -------------------------------------------------
    // TRANSACTIONS
    // -------------------------------------------------

    if (results[0].status === 'fulfilled') {
      console.log('Transactions response:', results[0].value.data)
      rawTransactions.value = results[0].value.data?.data || []
    } else {
      console.warn('Transactions unavailable:', results[0].reason)
      rawTransactions.value = []
    }


    // -------------------------------------------------
    // BUDGETS
    // -------------------------------------------------

    if (results[1].status === 'fulfilled') {
      console.log('Budgets response:', results[1].value.data)
      rawBudgets.value = results[1].value.data?.data || []
    } else {
      console.warn('Budgets unavailable:', results[1].reason)
      rawBudgets.value = []
    }


    // -------------------------------------------------
    // SAVINGS GOALS
    // -------------------------------------------------

    if (results[2].status === 'fulfilled') {
      console.log('Savings response:', results[2].value.data)
      rawGoals.value = results[2].value.data?.data || []
    } else {
      console.warn('Savings goals unavailable:', results[2].reason)
      rawGoals.value = []
    }


    // -------------------------------------------------
    // WALLETS
    // -------------------------------------------------

    if (results[3].status === 'fulfilled') {
      console.log('Wallets response:', results[3].value.data)
      rawWallets.value = results[3].value.data?.data || []
    } else {
      console.warn('Wallets unavailable:', results[3].reason)
      rawWallets.value = []
    }


    // A failed optional endpoint should not break the dashboard.
    // Only an authentication error should force the user back to login.
    const unauthorized = results.some(
      result =>
        result.status === 'rejected' &&
        result.reason?.response?.status === 401
    )

    if (unauthorized) {
      localStorage.removeItem('token')
      localStorage.removeItem('auth_token')
      localStorage.removeItem('access_token')
      localStorage.setItem('is_logged_in', '0')

      errorMsg.value =
        'Your session has expired. Please login again.'

      router.push('/login')
      return
    }

    console.log('=================================')
    console.log('DASHBOARD LOADED SUCCESSFULLY')
    console.log('=================================')

  }

  catch (err) {

    console.error(
      '================================='
    )

    console.error(
      'DASHBOARD LOAD FAILED'
    )

    console.error(
      '================================='
    )

    console.error(
      'Error:',
      err
    )

    console.error(
      'Status:',
      err.response?.status
    )

    console.error(
      'Response:',
      err.response?.data
    )

    console.error(
      'URL:',
      err.config?.url
    )

    console.error(
      'Base URL:',
      err.config?.baseURL
    )


    // -------------------------------------------------
    // SHOW A USEFUL ERROR MESSAGE
    // -------------------------------------------------

    if (err.response?.status === 401) {

      localStorage.removeItem('token')
      localStorage.removeItem('auth_token')
      localStorage.removeItem('access_token')
      localStorage.setItem('is_logged_in', '0')

      errorMsg.value =
        'Your session has expired. Please login again.'

      router.push('/login')

    }

    else if (err.response?.status === 403) {

      errorMsg.value =
        'You do not have permission to access dashboard data.'

    }

    else if (err.response?.status === 404) {

      errorMsg.value =
        'Dashboard API endpoint was not found.'

    }

    else if (!err.response) {

      errorMsg.value =
        'Cannot connect to the Laravel backend.'

    }

    else {

      errorMsg.value =
        err.response?.data?.message ||
        'Failed to load dashboard data.'

    }

  }

  finally {

    loading.value = false

  }
}


// =====================================================
// TRY AGAIN BUTTON
// =====================================================

function retryDashboard() {

  loadDashboard()

}


// =====================================================
// FORMAT NUMBER
// =====================================================

function formatNumber(number) {

  return Number(number || 0)
    .toLocaleString('id-ID')

}


// =====================================================
// TRANSACTIONS
// =====================================================

const transactions = computed(() => {

  return [...rawTransactions.value]

    .sort(
      (a, b) =>
        new Date(b.tanggal) -
        new Date(a.tanggal)
    )

    .slice(0, 5)

    .map((transaction) => {

      const isIncome =
        transaction.jenis === 'pemasukan'


      return {

        id:
          transaction.id_transaksi,

        name:
          transaction.judul ||
          'Transaction',

        category:
          transaction.kategori?.nama_kategori ||
          'Uncategorized',

        amount:
          (isIncome ? '+' : '-') +
          'Rp' +
          formatNumber(
            transaction.jumlah
          ),

        date:
          transaction.tanggal,

        icon:
          isIncome
            ? '💰'
            : '🧾',

        color:
          isIncome
            ? 'income-bg'
            : 'shopping',

        type:
          isIncome
            ? 'income'
            : 'expense',

      }

    })

})


// =====================================================
// BUDGETS
// =====================================================

const budgets = computed(() => {

  return rawBudgets.value.map(
    (budget, index) => {

      const spent =
        rawTransactions.value

          .filter((transaction) => {

            return (

              transaction.id_kategori ===
              budget.id_kategori

              &&

              transaction.jenis ===
              'pengeluaran'

              &&

              transaction.tanggal >=
              budget.tanggal_mulai

              &&

              transaction.tanggal <=
              budget.tanggal_selesai

            )

          })

          .reduce(
            (sum, transaction) =>
              sum +
              Number(
                transaction.jumlah || 0
              ),

            0
          )


      const limit =
        Number(
          budget.jumlah || 0
        )


      const percent =
        limit > 0

          ? Math.min(
              100,
              Math.round(
                (spent / limit) * 100
              )
            )

          : 0


      const colors = [
        'purple',
        'blue',
        'orange'
      ]


      return {

        id:
          budget.id_anggaran,

        name:
          budget.nama_anggaran ||
          'Budget',

        spent:
          'Rp' +
          formatNumber(spent),

        limit:
          'Rp' +
          formatNumber(limit),

        percent,

        color:
          colors[
            index %
            colors.length
          ],

      }

    }
  )

})


// =====================================================
// STATISTICS
// =====================================================

const stats = computed(() => {

  // -----------------------------------------------
  // TOTAL BALANCE
  // -----------------------------------------------

  const totalBalance =
    rawWallets.value

      .reduce(
        (sum, wallet) =>
          sum +
          Number(
            wallet.saldo || 0
          ),

        0
      )


  // -----------------------------------------------
  // TOTAL INCOME
  // -----------------------------------------------

  const income =
    rawTransactions.value

      .filter(
        transaction =>
          transaction.jenis ===
          'pemasukan'
      )

      .reduce(
        (sum, transaction) =>
          sum +
          Number(
            transaction.jumlah || 0
          ),

        0
      )


  // -----------------------------------------------
  // TOTAL EXPENSE
  // -----------------------------------------------

  const expenses =
    rawTransactions.value

      .filter(
        transaction =>
          transaction.jenis ===
          'pengeluaran'
      )

      .reduce(
        (sum, transaction) =>
          sum +
          Number(
            transaction.jumlah || 0
          ),

        0
      )


  return {

    totalBalance,

    income,

    expenses,

  }

})


// =====================================================
// PRIMARY SAVINGS GOAL
// =====================================================

const primaryGoal = computed(() => {

  if (
    !rawGoals.value.length
  ) {

    return null

  }


  const goal =
    rawGoals.value[0]


  const target =
    Number(
      goal.target_jumlah || 0
    )


  const saved =
    Number(
      goal.jumlah_terkumpul || 0
    )


  const percent =
    target > 0

      ? Math.min(
          100,
          Math.round(
            (saved / target) *
            100
          )
        )

      : 0


  return {

    name:
      goal.nama_tujuan ||
      'Savings Goal',

    saved,

    target,

    percent,

    deadline:
      goal.deadline ||
      null,

  }

})


// =====================================================
// SPENDING CHART — PERIOD SELECTOR
// =====================================================

const chartPeriod = ref('week') // 'day' | 'week' | 'month'
const showPeriodMenu = ref(false)
const periodDropdownRef = ref(null)

const periodOptions = [
  { value: 'day', label: 'Today' },
  { value: 'week', label: 'This Week' },
  { value: 'month', label: 'This Month' }
]

const periodLabel = computed(() => {
  const found = periodOptions.find(opt => opt.value === chartPeriod.value)
  return found ? found.label : 'This Week'
})

const subtitleText = computed(() => {
  if (chartPeriod.value === 'day') return 'Your spending activity today'
  if (chartPeriod.value === 'month') return 'Your spending activity this month'
  return 'Your spending activity this week'
})

function togglePeriodMenu() {
  showPeriodMenu.value = !showPeriodMenu.value
}

function selectPeriod(value) {
  chartPeriod.value = value
  showPeriodMenu.value = false
}

function handleClickOutside(event) {
  if (
    periodDropdownRef.value &&
    !periodDropdownRef.value.contains(event.target)
  ) {
    showPeriodMenu.value = false
  }
}

function isSameDay(a, b) {
  return (
    a.getFullYear() === b.getFullYear() &&
    a.getMonth() === b.getMonth() &&
    a.getDate() === b.getDate()
  )
}


// =====================================================
// SPENDING CHART — DAY (hourly buckets, today only)
// =====================================================

const dayValues = computed(() => {
  const values = Array(24).fill(0)

  if (!rawTransactions.value.length) return values

  const now = new Date()

  rawTransactions.value.forEach(transaction => {
    if (transaction.jenis !== 'pengeluaran') return

    const date = new Date(transaction.tanggal)
    if (Number.isNaN(date.getTime())) return

    if (!isSameDay(date, now)) return

    // Requires the backend to include a time component in `tanggal`.
    // Date-only values will all fall into the midnight (0) bucket.
    values[date.getHours()] += Number(transaction.jumlah || 0)
  })

  return values
})


// =====================================================
// SPENDING CHART — WEEK (daily buckets, Mon-Sun)
// =====================================================

const weekValues = computed(() => {
  const values = Array(7).fill(0)

  if (!rawTransactions.value.length) return values

  const now = new Date()
  const today = new Date(
    now.getFullYear(),
    now.getMonth(),
    now.getDate()
  )

  const weekStart = new Date(today)
  weekStart.setDate(today.getDate() - ((today.getDay() + 6) % 7))

  rawTransactions.value.forEach(transaction => {
    if (transaction.jenis !== 'pengeluaran') return

    const date = new Date(transaction.tanggal)
    if (Number.isNaN(date.getTime())) return

    const transactionDate = new Date(
      date.getFullYear(),
      date.getMonth(),
      date.getDate()
    )

    const dayIndex = (transactionDate.getDay() + 6) % 7

    if (transactionDate >= weekStart && transactionDate <= today) {
      values[dayIndex] += Number(transaction.jumlah || 0)
    }
  })

  return values
})


// =====================================================
// SPENDING CHART — MONTH (weekly buckets across the month)
// =====================================================

const monthWeekRanges = computed(() => {
  const now = new Date()
  const year = now.getFullYear()
  const month = now.getMonth()
  const daysInMonth = new Date(year, month + 1, 0).getDate()

  const weeks = []

  for (let day = 1; day <= daysInMonth; day += 7) {
    weeks.push({
      start: day,
      end: Math.min(day + 6, daysInMonth)
    })
  }

  return weeks
})

const monthValues = computed(() => {
  const weeks = monthWeekRanges.value
  const values = Array(weeks.length).fill(0)

  if (!rawTransactions.value.length) return values

  const now = new Date()
  const year = now.getFullYear()
  const month = now.getMonth()

  rawTransactions.value.forEach(transaction => {
    if (transaction.jenis !== 'pengeluaran') return

    const date = new Date(transaction.tanggal)
    if (Number.isNaN(date.getTime())) return

    if (date.getFullYear() !== year || date.getMonth() !== month) return

    const dayOfMonth = date.getDate()
    const weekIndex = weeks.findIndex(
      w => dayOfMonth >= w.start && dayOfMonth <= w.end
    )

    if (weekIndex >= 0) {
      values[weekIndex] += Number(transaction.jumlah || 0)
    }
  })

  return values
})


// =====================================================
// SPENDING CHART — ACTIVE VALUES & LABELS
// =====================================================

const spendingValues = computed(() => {
  if (chartPeriod.value === 'day') return dayValues.value
  if (chartPeriod.value === 'month') return monthValues.value
  return weekValues.value
})

const xLabels = computed(() => {
  if (chartPeriod.value === 'day') {
    return ['12 AM', '3 AM', '6 AM', '9 AM', '12 PM', '3 PM', '6 PM', '9 PM']
  }

  if (chartPeriod.value === 'month') {
    return monthWeekRanges.value.map((_, i) => 'Week ' + (i + 1))
  }

  return ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
})

// Rounds the data max up to a "nice" number (1, 2, 5, 10 x a power of 10)
// so the y-axis labels stay readable across very different value ranges.
const maxValue = computed(() => {
  const dataMax = Math.max(...spendingValues.value, 0)

  if (dataMax <= 0) return 1000000

  const magnitude = Math.pow(10, Math.floor(Math.log10(dataMax)))
  return Math.ceil(dataMax / magnitude) * magnitude
})

const yAxisLabels = computed(() => {
  const max = maxValue.value

  return [0, 1, 2, 3, 4].map(i => ({
    top: 27 + i * 46,
    text: 'Rp' + formatNumber(Math.round(max - (max / 4) * i))
  }))
})

const chartLinePath = computed(() => {
  const values = spendingValues.value
  const width = 800
  const height = 260
  const max = maxValue.value
  const divisor = values.length - 1 || 1

  return values
    .map((value, index) => {
      const x = (index / divisor) * width
      const y = height - (value / max) * height
      return `${index === 0 ? 'M' : 'L'} ${x} ${y}`
    })
    .join(' ')
})

const chartAreaPath = computed(() => {
  const line = chartLinePath.value

  if (!line) return ''

  return `${line} L 800 260 L 0 260 Z`
})


// =====================================================
// DONUT CHART
// =====================================================

const donutGradient = computed(() => {

  const percent =
    animatedPercent.value ||
    0


  const degrees =
    (percent / 100) *
    360


  return `
    conic-gradient(
      #6c5ce7 0deg ${degrees}deg,
      #e9eaf1 ${degrees}deg 360deg
    )
  `

})


// =====================================================
// COUNT-UP ANIMATION HELPER
// =====================================================

function animateValue(targetRef, targetValue, duration = 1200) {

  const startValue = targetRef.value
  const startTime = performance.now()

  function step(now) {

    const progress = Math.min((now - startTime) / duration, 1)
    const eased = 1 - Math.pow(1 - progress, 3) // ease-out cubic

    targetRef.value = Math.round(
      startValue + (targetValue - startValue) * eased
    )

    if (progress < 1) {
      requestAnimationFrame(step)
    }

  }

  requestAnimationFrame(step)

}


// =====================================================
// ANIMATED STAT VALUES
// =====================================================

const animatedBalance = ref(0)
const animatedIncome = ref(0)
const animatedExpenses = ref(0)

watch(stats, (newStats) => {

  animateValue(animatedBalance, newStats.totalBalance)
  animateValue(animatedIncome, newStats.income)
  animateValue(animatedExpenses, newStats.expenses)

}, { immediate: true })


// =====================================================
// ANIMATED SAVINGS GOAL
// =====================================================

const animatedSaved = ref(0)
const animatedPercent = ref(0)

watch(primaryGoal, (goal) => {

  if (goal) {
    animateValue(animatedSaved, goal.saved)
    animateValue(animatedPercent, goal.percent)
  } else {
    animatedSaved.value = 0
    animatedPercent.value = 0
  }

}, { immediate: true })


// =====================================================
// CHART RISE-UP ANIMATION
// =====================================================

const chartAnimated = ref(false)

watch(spendingValues, async () => {

  if (transactions.value.length) {

    // Reset first so the animation replays on new data or period switch.
    chartAnimated.value = false

    await nextTick()

    requestAnimationFrame(() => {
      chartAnimated.value = true
    })

  } else {

    chartAnimated.value = false

  }

}, { immediate: true })


// =====================================================
// LOAD WHEN COMPONENT MOUNTS
// =====================================================

onMounted(() => {

  loadDashboard()
  document.addEventListener('click', handleClickOutside)

})

onUnmounted(() => {

  document.removeEventListener('click', handleClickOutside)

})

</script>


<style scoped>

/* =========================
   DASHBOARD
========================= */

.dashboard {
  min-height: 100vh;
  padding: 28px 30px 35px;
  background: #f7f8fc;
}


.state-banner {
  max-width: 1220px;
  margin: 40px auto;
  padding: 30px;
  text-align: center;
  font-size: 13px;
  color: #647087;
}


.state-banner.error {
  color: #ef6565;
}


.state-banner button {
  display: block;
  margin: 15px auto 0;
  border: none;
  background: #6c5ce7;
  color: white;
  padding: 9px 16px;
  border-radius: 7px;
  cursor: pointer;
}


.error-icon {
  width: 35px;
  height: 35px;
  margin: 0 auto 10px;
  border-radius: 50%;
  background: #fff0f0;
  color: #ef6565;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
}


.loading-spinner {
  width: 24px;
  height: 24px;
  border: 3px solid #e7e5fb;
  border-top-color: #6c5ce7;
  border-radius: 50%;
  margin: 0 auto 12px;
  animation: spin .8s linear infinite;
}


@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}


.empty-note {
  padding: 20px 0;
  text-align: center;
  font-size: 11px;
  color: #9aa2b4;
}


.savings-empty {
  padding: 30px 20px;
}


/* =========================
   WELCOME
========================= */

.welcome-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  max-width: 1220px;
  margin: 0 auto 21px;
}


.welcome-row h2 {
  margin: 0;
  font-size: 22px;
  color: #14213d;
}


.welcome-row p {
  margin: 4px 0 0;
  font-size: 12px;
  color: #8992a6;
}


.add-button {
  border: none;
  background: #6c5ce7;
  color: white;
  border-radius: 9px;
  padding: 11px 17px;
  font-size: 11px;
  font-weight: 700;
  box-shadow: 0 8px 18px rgba(108, 92, 231, .22);
  cursor: pointer;
}


.add-button span {
  font-size: 16px;
  margin-right: 7px;
}


/* =========================
   STATS
========================= */

.stats-grid {
  max-width: 1220px;
  margin: 0 auto 18px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
}


.stat-card {
  background: white;
  border: 1px solid #e9ebf2;
  border-radius: 13px;
  padding: 18px;
  min-height: 157px;
  box-shadow: 0 8px 25px rgba(30,40,80,.025);
}


.stat-top {
  display: flex;
  margin-bottom: 16px;
}


.stat-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 15px;
  font-weight: 700;
}


.stat-icon.purple {
  background: #eeebff;
  color: #6c5ce7;
}


.stat-icon.green {
  background: #e5f8ef;
  color: #19a566;
}


.stat-icon.red {
  background: #ffeded;
  color: #ef6565;
}


.stat-icon.orange {
  background: #fff3dc;
  color: #e59a13;
}


.stat-label {
  display: block;
  color: #929bb0;
  font-size: 10px;
  margin-bottom: 17px;
}


.stat-value {
  display: block;
  font-size: 20px;
  color: #14213d;
  margin-bottom: 4px;
}


.stat-description {
  font-size: 9px;
  color: #a2a9b9;
}


/* =========================
   PANELS
========================= */

.middle-grid {
  max-width: 1220px;
  margin: 0 auto 18px;
  display: grid;
  grid-template-columns: 1.65fr 1fr;
  gap: 17px;
}


.panel {
  background: white;
  border: 1px solid #e8eaf1;
  border-radius: 13px;
  box-shadow: 0 8px 25px rgba(30,40,80,.025);
}


.spending-panel,
.transactions-panel {
  min-height: 355px;
}


.panel-header {
  padding: 19px 19px 13px;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
}


.panel-header h3 {
  margin: 0;
  font-size: 13px;
  color: #14213d;
}


.panel-header p {
  margin: 4px 0 0;
  font-size: 9px;
  color: #9aa2b4;
}


.period-dropdown {
  position: relative;
}


.month-button {
  border: 1px solid #e4e7ef;
  background: white;
  border-radius: 7px;
  padding: 7px 10px;
  color: #647087;
  font-size: 9px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 5px;
}


.period-menu {
  position: absolute;
  top: calc(100% + 6px);
  right: 0;
  z-index: 10;
  background: white;
  border: 1px solid #e8eaf1;
  border-radius: 9px;
  box-shadow: 0 10px 25px rgba(30, 40, 80, .09);
  padding: 5px;
  display: flex;
  flex-direction: column;
  min-width: 110px;
  animation: period-menu-in 0.15s ease;
}


@keyframes period-menu-in {
  from {
    opacity: 0;
    transform: translateY(-4px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}


.period-menu button {
  border: none;
  background: transparent;
  text-align: left;
  padding: 7px 9px;
  border-radius: 6px;
  font-size: 10px;
  color: #526079;
  cursor: pointer;
}


.period-menu button:hover {
  background: #f5f5fb;
}


.period-menu button.active {
  background: #eeebff;
  color: #6c5ce7;
  font-weight: 700;
}


/* =========================
   CHART
========================= */

.chart {
  position: relative;
  height: 285px;
  margin: 0 19px;
  padding-left: 42px;
}


.grid-line {
  position: absolute;
  left: 42px;
  right: 0;
  border-top: 1px dashed #e6e8ef;
}


.line-1 { top: 32px; }
.line-2 { top: 78px; }
.line-3 { top: 124px; }
.line-4 { top: 170px; }
.line-5 { top: 216px; }


.chart-label {
  position: absolute;
  left: 0;
  font-size: 8px;
  color: #a0a8b9;
}


.label-1000 { top: 27px; }
.label-750 { top: 73px; }
.label-500 { top: 119px; }
.label-250 { top: 165px; }
.label-0 { top: 211px; }


.spending-svg {
  position: absolute;
  left: 42px;
  right: 0;
  top: 32px;
  width: calc(100% - 42px);
  height: 185px;
  overflow: visible;
}


.chart-area {
  fill: url(#chartFill);
}


.chart-line {
  fill: none;
  stroke: #6c5ce7;
  stroke-width: 4;
  stroke-linecap: round;
}


/* Chart rises up from the baseline when data loads/changes */
.chart-group {
  transform-box: fill-box;
  transform-origin: bottom;
  transform: scaleY(0);
  transition: transform 1.1s cubic-bezier(0.22, 1, 0.36, 1);
}

.chart-group.animate {
  transform: scaleY(1);
}


.days {
  position: absolute;
  left: 42px;
  right: 0;
  bottom: 30px;
  display: flex;
  justify-content: space-between;
  font-size: 8px;
  color: #a0a8b9;
}


/* =========================
   EMPTY CHART
========================= */

.chart-empty {
  position: absolute;
  inset: 32px 0 30px 42px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  color: #9aa2b4;
}

.chart-empty-icon {
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 10px;
  border-radius: 50%;
  background: #f0efff;
  color: #6c5ce7;
  font-size: 18px;
}

.chart-empty strong {
  font-size: 11px;
  color: #647087;
  margin-bottom: 4px;
}

.chart-empty span {
  font-size: 9px;
  color: #a2a9b9;
}


/* =========================
   TRANSACTIONS
========================= */

.view-button {
  border: none;
  background: transparent;
  color: #6c5ce7;
  font-size: 9px;
  font-weight: 700;
  cursor: pointer;
}


.transaction-list {
  padding: 0 19px;
}


.transaction {
  min-height: 56px;
  display: flex;
  align-items: center;
  border-bottom: 1px solid #f0f1f5;
  gap: 10px;

  /* Slide-in animation */
  opacity: 0;
  animation: transaction-slide-in 0.45s ease forwards;
}


.transaction:last-child {
  border-bottom: none;
}


@keyframes transaction-slide-in {
  from {
    opacity: 0;
    transform: translateX(24px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}


.transaction-icon {
  width: 31px;
  height: 31px;
  border-radius: 9px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 14px;
}


.shopping {
  background: #f0efff;
}


.income-bg {
  background: #fff2d8;
}


.transaction-info {
  display: flex;
  flex-direction: column;
  gap: 3px;
  flex: 1;
}


.transaction-info strong {
  font-size: 10px;
}


.transaction-info span {
  font-size: 8px;
  color: #9ca4b5;
}


.transaction-amount {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 3px;
}


.transaction-amount strong {
  font-size: 10px;
}


.transaction-amount span {
  font-size: 8px;
  color: #9ca4b5;
}


.transaction-amount strong.income {
  color: #16a765;
}


/* =========================
   BOTTOM
========================= */

.bottom-grid {
  max-width: 1220px;
  margin: 0 auto 17px;
  display: grid;
  grid-template-columns: 1fr 1.05fr;
  gap: 17px;
}


.budget-panel,
.savings-panel {
  min-height: 220px;
}


/* =========================
   BUDGET
========================= */

.budget-list {
  padding: 0 19px 15px;
}


.budget-item {
  margin-bottom: 18px;
}


.budget-title {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 9px;
  color: #8992a5;
}


.budget-name {
  display: flex;
  align-items: center;
  gap: 7px;
  font-weight: 700;
  color: #33415c;
}


.budget-dot {
  width: 7px;
  height: 7px;
  border-radius: 50%;
}


.budget-dot.purple,
.progress-fill.purple {
  background: #6c5ce7;
}


.budget-dot.blue,
.progress-fill.blue {
  background: #5795ef;
}


.budget-dot.orange,
.progress-fill.orange {
  background: #e9a020;
}


.progress {
  width: 100%;
  height: 5px;
  background: #eef0f4;
  border-radius: 10px;
  overflow: hidden;
}


.progress-fill {
  height: 100%;
  border-radius: 10px;
  transition: width 1s cubic-bezier(0.22, 1, 0.36, 1);
}


/* =========================
   SAVINGS
========================= */

.plus-button {
  width: 27px;
  height: 27px;
  border: none;
  border-radius: 7px;
  background: #efedff;
  color: #6c5ce7;
  font-size: 17px;
  cursor: pointer;
}


.savings-content {
  display: flex;
  align-items: center;
  gap: 22px;
  padding: 4px 20px 20px;
}


.donut {
  width: 102px;
  height: 102px;
  flex-shrink: 0;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  transition: background 0.15s linear;
}


.donut::before {
  content: "";
  position: absolute;
  width: 82px;
  height: 82px;
  border-radius: 50%;
  background: white;
}


.donut-inner {
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  align-items: center;
}


.donut-inner strong {
  font-size: 20px;
  color: #14213d;
}


.donut-inner span {
  margin-top: 1px;
  font-size: 8px;
  color: #9aa2b4;
}


.goal-info h3 {
  margin: 0 0 8px;
  font-size: 13px;
  color: #14213d;
}


.goal-info p {
  margin: 0 0 5px;
  font-size: 10px;
  color: #69758c;
}


.goal-info > span {
  font-size: 9px;
  color: #6c5ce7;
  font-weight: 600;
}


/* =========================
   QUICK ACTIONS
========================= */

.quick-actions {
  max-width: 1220px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 13px;
}


.quick-actions button {
  height: 53px;
  border: 1px solid #e7e9f0;
  background: white;
  border-radius: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 13px;
  font-size: 9px;
  font-weight: 700;
  color: #526079;
  cursor: pointer;
}


.quick-actions button span {
  width: 29px;
  height: 29px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 7px;
  background: #efedff;
  color: #6c5ce7;
  font-size: 14px;
}


/* =========================
   SKELETON LOADING
========================= */

.dashboard-skeleton {
  max-width: 1220px;
  margin: 0 auto;
}

.skeleton-line,
.skeleton-icon,
.skeleton-button,
.skeleton-link,
.skeleton-plus,
.skeleton-chart-area,
.skeleton-transaction-icon,
.skeleton-budget-name,
.skeleton-budget-number,
.skeleton-progress,
.skeleton-donut,
.skeleton-action-icon,
.skeleton-action-text {
  background: linear-gradient(
    90deg,
    #eceef4 25%,
    #f6f7fa 37%,
    #eceef4 63%
  );
  background-size: 400% 100%;
  animation: skeleton-shimmer 1.4s ease infinite;
  border-radius: 6px;
}

@keyframes skeleton-shimmer {
  0% { background-position: 100% 50%; }
  100% { background-position: 0 50%; }
}

/* ---- Stat cards ---- */

.skeleton-card {
  display: flex;
  flex-direction: column;
}

.skeleton-icon {
  width: 32px;
  height: 32px;
  border-radius: 8px;
  margin-bottom: 16px;
}

.skeleton-line.small {
  width: 60px;
  height: 8px;
  margin-bottom: 17px;
}

.skeleton-line.value {
  width: 100px;
  height: 16px;
  margin-bottom: 8px;
}

.skeleton-line.description {
  width: 80px;
  height: 7px;
}

/* ---- Panel headers ---- */

.skeleton-panel .panel-header {
  align-items: flex-start;
}

.skeleton-line.title {
  width: 130px;
  height: 12px;
  margin-bottom: 8px;
}

.skeleton-line.subtitle {
  width: 170px;
  height: 8px;
}

.skeleton-button {
  width: 90px;
  height: 26px;
  border-radius: 7px;
}

.skeleton-link {
  width: 50px;
  height: 10px;
}

.skeleton-plus {
  width: 27px;
  height: 27px;
  border-radius: 7px;
}

/* ---- Spending chart ---- */

.skeleton-chart {
  position: relative;
  height: 285px;
  margin: 0 19px;
  padding: 0 0 30px 42px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.skeleton-chart-lines {
  position: absolute;
  top: 0;
  left: 42px;
  right: 0;
  height: 216px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.skeleton-chart-line {
  height: 1px;
  background: #eceef4;
}

.skeleton-chart-area {
  height: 185px;
  margin-top: 32px;
  border-radius: 10px;
  opacity: 0.6;
}

/* ---- Transactions ---- */

.skeleton-transactions {
  padding: 0 19px;
}

.skeleton-transaction {
  display: flex;
  align-items: center;
  gap: 10px;
  min-height: 56px;
  border-bottom: 1px solid #f0f1f5;
}

.skeleton-transaction:last-child {
  border-bottom: none;
}

.skeleton-transaction-icon {
  width: 31px;
  height: 31px;
  border-radius: 9px;
  flex-shrink: 0;
}

.skeleton-transaction-info {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.skeleton-line.transaction-name {
  width: 100px;
  height: 9px;
}

.skeleton-line.transaction-category {
  width: 70px;
  height: 7px;
}

.skeleton-transaction-amount {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 6px;
}

.skeleton-line.amount {
  width: 60px;
  height: 9px;
}

.skeleton-line.date {
  width: 50px;
  height: 7px;
}

/* ---- Budgets ---- */

.skeleton-budgets {
  padding: 0 19px 15px;
}

.skeleton-budget {
  margin-bottom: 18px;
}

.skeleton-budget-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 8px;
}

.skeleton-budget-name {
  width: 90px;
  height: 9px;
}

.skeleton-budget-number {
  width: 70px;
  height: 8px;
}

.skeleton-progress {
  width: 100%;
  height: 5px;
  border-radius: 10px;
}

/* ---- Savings ---- */

.skeleton-savings {
  display: flex;
  align-items: center;
  gap: 22px;
  padding: 4px 20px 20px;
}

.skeleton-donut {
  width: 102px;
  height: 102px;
  border-radius: 50%;
  flex-shrink: 0;
}

.skeleton-goal-info {
  display: flex;
  flex-direction: column;
  gap: 8px;
  flex: 1;
}

.skeleton-line.goal-title {
  width: 120px;
  height: 12px;
}

.skeleton-line.goal-text {
  width: 160px;
  height: 9px;
}

.skeleton-line.goal-deadline {
  width: 100px;
  height: 8px;
}

/* ---- Quick actions ---- */

.skeleton-action {
  height: 53px;
  border: 1px solid #e7e9f0;
  background: white;
  border-radius: 10px;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 13px;
}

.skeleton-action-icon {
  width: 29px;
  height: 29px;
  border-radius: 7px;
  flex-shrink: 0;
}

.skeleton-action-text {
  width: 100px;
  height: 9px;
}


/* =========================
   TABLET
========================= */

@media (max-width: 1100px) {

  .dashboard {
    padding: 22px;
  }


  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }


  .middle-grid {
    grid-template-columns: 1fr;
  }


  .bottom-grid {
    grid-template-columns: 1fr;
  }

}


/* =========================
   MOBILE
========================= */

@media (max-width: 700px) {

  .dashboard {
    padding: 17px 14px 90px;
  }


  .welcome-row {
    display: block;
    margin-bottom: 18px;
  }


  .welcome-row h2 {
    font-size: 20px;
  }


  .welcome-row p {
    font-size: 11px;
  }


  .add-button {
    width: 100%;
    margin-top: 14px;
  }


  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
  }


  .stat-card {
    min-height: 145px;
    padding: 14px;
  }


  .stat-value {
    font-size: 18px;
  }


  .stat-label {
    margin-bottom: 12px;
  }


  .middle-grid,
  .bottom-grid {
    grid-template-columns: 1fr;
    gap: 12px;
  }


  .spending-panel,
  .transactions-panel {
    min-height: auto;
  }


  .chart {
    height: 250px;
  }


  .savings-content {
    padding-bottom: 24px;
  }


  .quick-actions {
    grid-template-columns: 1fr;
    gap: 9px;
  }


  .quick-actions button {
    height: 48px;
  }

}


/* =========================
   SMALL MOBILE
========================= */

@media (max-width: 400px) {

  .stats-grid {
    grid-template-columns: 1fr;
  }


  .stat-card {
    min-height: 130px;
  }


  .savings-content {
    gap: 15px;
  }


  .donut {
    width: 88px;
    height: 88px;
  }


  .donut::before {
    width: 70px;
    height: 70px;
  }

}

</style>