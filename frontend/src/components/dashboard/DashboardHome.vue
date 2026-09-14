<template>
  <section class="dashboard">

    <!-- =========================
         LOADING
    ========================== -->
    <div v-if="loading" class="state-banner">
      <div class="loading-spinner"></div>
      <span>Loading your dashboard...</span>
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
           GREETING
      ========================== -->
      <div class="welcome-row">

        <div>
          <h2>
            {{ greeting }}, {{ userName }} 👋
          </h2>

          <p>
            Here's your financial overview
          </p>
        </div>

        <button class="add-button">
          <span>+</span>
          Add Transaction
        </button>

      </div>


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
            Rp{{ formatNumber(stats.totalBalance) }}
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
            Rp{{ formatNumber(stats.income) }}
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
            Rp{{ formatNumber(stats.expenses) }}
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
            {{ primaryGoal ? primaryGoal.percent + '%' : '—' }}
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
                Your spending activity this month
              </p>
            </div>

            <button class="month-button">
              This Month
              <span>⌄</span>
            </button>

          </div>


          <div class="chart">

            <div class="chart-label label-1000">
              Rp1,000,000
            </div>

            <div class="chart-label label-750">
              Rp750,000
            </div>

            <div class="chart-label label-500">
              Rp500,000
            </div>

            <div class="chart-label label-250">
              Rp250,000
            </div>

            <div class="chart-label label-0">
              Rp0
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


              <!-- Dynamic area -->
              <path
                class="chart-area"
                :d="chartAreaPath"
              />


              <!-- Dynamic line -->
              <path
                class="chart-line"
                :d="chartLinePath"
              />

            </svg>


            <div class="days">
              <span>Mon</span>
              <span>Tue</span>
              <span>Wed</span>
              <span>Thu</span>
              <span>Fri</span>
              <span>Sat</span>
              <span>Sun</span>
            </div>

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

            <button class="view-button">
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
              v-for="transaction in transactions"
              :key="transaction.id"
              class="transaction"
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

            <button class="view-button">
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

            <button class="plus-button">
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
                  {{ primaryGoal.percent }}%
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
                Rp{{ formatNumber(primaryGoal.saved) }}
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

        <button>
          <span>+</span>
          Add Transaction
        </button>

        <button>
          <span>▣</span>
          Manage Budget
        </button>

        <button>
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
  onMounted
} from 'vue'

import api from '../../utils/api.js'


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

      console.log(
        'Transactions response:',
        results[0].value.data
      )

      rawTransactions.value =
        results[0].value.data?.data || []

    } else {

      console.error(
        'Transactions failed:',
        results[0].reason
      )

      throw results[0].reason
    }


    // -------------------------------------------------
    // BUDGETS
    // -------------------------------------------------

    if (results[1].status === 'fulfilled') {

      console.log(
        'Budgets response:',
        results[1].value.data
      )

      rawBudgets.value =
        results[1].value.data?.data || []

    } else {

      console.error(
        'Budgets failed:',
        results[1].reason
      )

      throw results[1].reason
    }


    // -------------------------------------------------
    // SAVINGS GOALS
    // -------------------------------------------------

    if (results[2].status === 'fulfilled') {

      console.log(
        'Savings response:',
        results[2].value.data
      )

      rawGoals.value =
        results[2].value.data?.data || []

    } else {

      console.error(
        'Savings goals failed:',
        results[2].reason
      )

      throw results[2].reason
    }


    // -------------------------------------------------
    // WALLETS
    // -------------------------------------------------

    if (results[3].status === 'fulfilled') {

      console.log(
        'Wallets response:',
        results[3].value.data
      )

      rawWallets.value =
        results[3].value.data?.data || []

    } else {

      console.error(
        'Wallets failed:',
        results[3].reason
      )

      throw results[3].reason
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

      errorMsg.value =
        'Your session has expired. Please login again.'

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
// DONUT CHART
// =====================================================

const donutGradient = computed(() => {

  const percent =
    primaryGoal.value?.percent ||
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
// LOAD WHEN COMPONENT MOUNTS
// =====================================================

onMounted(() => {

  loadDashboard()

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


.month-button {
  border: 1px solid #e4e7ef;
  background: white;
  border-radius: 7px;
  padding: 7px 10px;
  color: #647087;
  font-size: 9px;
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
}


.transaction:last-child {
  border-bottom: none;
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