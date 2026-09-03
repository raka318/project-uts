<template>
  <section class="dashboard">


    <!-- =========================
         GREETING
    ========================== -->
    <div class="welcome-row">

      <div>
        <h2>
          Good morning, John 👋
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

      <div class="stat-card">

        <div class="stat-top">
          <div class="stat-icon purple">
            $
          </div>

          <span class="percentage positive">
            +8.4%
          </span>
        </div>

        <span class="stat-label">
          Total Balance
        </span>

        <strong class="stat-value">
          $12,450
        </strong>

        <span class="stat-description">
          From last month
        </span>

      </div>


      <div class="stat-card">

        <div class="stat-top">
          <div class="stat-icon green">
            ↗
          </div>

          <span class="percentage positive">
            +12.5%
          </span>
        </div>

        <span class="stat-label">
          Income
        </span>

        <strong class="stat-value">
          $5,200
        </strong>

        <span class="stat-description">
          This month
        </span>

      </div>


      <div class="stat-card">

        <div class="stat-top">
          <div class="stat-icon red">
            ↘
          </div>

          <span class="percentage negative">
            -4.2%
          </span>
        </div>

        <span class="stat-label">
          Expenses
        </span>

        <strong class="stat-value">
          $2,840
        </strong>

        <span class="stat-description">
          This month
        </span>

      </div>


      <div class="stat-card">

        <div class="stat-top">
          <div class="stat-icon orange">
            $
          </div>

          <span class="percentage positive">
            +6.8%
          </span>
        </div>

        <span class="stat-label">
          Savings
        </span>

        <strong class="stat-value">
          $2,360
        </strong>

        <span class="stat-description">
          This month
        </span>

      </div>

    </div>


    <!-- =========================
         CHART + TRANSACTIONS
    ========================== -->
    <div class="middle-grid">

      <!-- Spending -->
      <div class="panel spending-panel">

        <div class="panel-header">

          <div>
            <h3>Spending Overview</h3>
            <p>Your spending activity this month</p>
          </div>

          <button class="month-button">
            This Month
            <span>⌄</span>
          </button>

        </div>


        <!-- Fake chart -->
        <div class="chart">

          <div class="chart-label label-1000">
            $1,000
          </div>

          <div class="chart-label label-750">
            $750
          </div>

          <div class="chart-label label-500">
            $500
          </div>

          <div class="chart-label label-250">
            $250
          </div>

          <div class="chart-label label-0">
            $0
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

            <path
              class="chart-area"
              d="
                M0 180
                C55 165 90 175 125 160
                C170 140 205 150 245 125
                C290 105 320 125 355 105
                C400 82 430 110 470 88
                C520 62 550 95 595 68
                C640 42 675 72 710 45
                C750 22 775 40 800 18
                L800 260
                L0 260
                Z
              "
            />

            <path
              class="chart-line"
              d="
                M0 180
                C55 165 90 175 125 160
                C170 140 205 150 245 125
                C290 105 320 125 355 105
                C400 82 430 110 470 88
                C520 62 550 95 595 68
                C640 42 675 72 710 45
                C750 22 775 40 800 18
              "
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


      <!-- Recent Transactions -->
      <div class="panel transactions-panel">

        <div class="panel-header">

          <div>
            <h3>Recent Transactions</h3>
            <p>Your latest activity</p>
          </div>

          <button class="view-button">
            View all
          </button>

        </div>


        <div class="transaction-list">

          <div
            v-for="transaction in transactions"
            :key="transaction.name"
            class="transaction"
          >

            <div
              class="transaction-icon"
              :class="transaction.color"
            >
              {{ transaction.icon }}
            </div>

            <div class="transaction-info">
              <strong>{{ transaction.name }}</strong>
              <span>{{ transaction.category }}</span>
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

      <!-- Budget -->
      <div class="panel budget-panel">

        <div class="panel-header">

          <div>
            <h3>Budget Overview</h3>
            <p>Track your monthly spending limits</p>
          </div>

          <button class="view-button">
            View budgets
          </button>

        </div>


        <div class="budget-list">

          <div
            v-for="budget in budgets"
            :key="budget.name"
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
                :style="{ width: budget.percent + '%' }"
              ></div>
            </div>

          </div>

        </div>

      </div>


      <!-- Savings -->
      <div class="panel savings-panel">

        <div class="panel-header">

          <div>
            <h3>Savings Goals</h3>
            <p>Keep working toward your goals</p>
          </div>

          <button class="plus-button">
            +
          </button>

        </div>


        <div class="savings-content">

          <!-- SINGLE donut -->
          <div class="donut">

            <div class="donut-inner">
              <strong>68%</strong>
              <span>Complete</span>
            </div>

          </div>


          <div class="goal-info">

            <h3>New Laptop</h3>

            <p>
              $1,360 saved of $2,000
            </p>

            <span>
              Target: December 2026
            </span>

          </div>

        </div>

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

  </section>
</template>


<script setup>
const transactions = [
  {
    name: 'Lunch',
    category: 'Food & Dining',
    amount: '-$42.50',
    date: 'Today',
    icon: '🍔',
    color: 'food',
    type: 'expense'
  },
  {
    name: 'Shopping',
    category: 'Shopping',
    amount: '-$125',
    date: 'Yesterday',
    icon: '🛍',
    color: 'shopping',
    type: 'expense'
  },
  {
    name: 'Salary',
    category: 'Income',
    amount: '+$4,500',
    date: 'Aug 30',
    icon: '💰',
    color: 'income-bg',
    type: 'income'
  },
  {
    name: 'Netflix',
    category: 'Entertainment',
    amount: '-$15.99',
    date: 'Aug 29',
    icon: '🎬',
    color: 'entertainment',
    type: 'expense'
  },
  {
    name: 'Gas',
    category: 'Transportation',
    amount: '-$55',
    date: 'Aug 28',
    icon: '⛽',
    color: 'transport',
    type: 'expense'
  }
]


const budgets = [
  {
    name: 'Food & Dining',
    spent: '$420',
    limit: '$600',
    percent: 70,
    color: 'purple'
  },
  {
    name: 'Shopping',
    spent: '$280',
    limit: '$400',
    percent: 70,
    color: 'blue'
  },
  {
    name: 'Entertainment',
    spent: '$120',
    limit: '$250',
    percent: 48,
    color: 'orange'
  }
]
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


/* =========================
   PAGE HEADER
========================= */

.page-header {
  min-height: 67px;

  background: white;

  border: 1px solid #eceef4;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 0 26px;

  margin-bottom: 27px;
}

.eyebrow {
  font-size: 9px;

  letter-spacing: 1px;

  font-weight: 700;

  color: #9ba3b5;
}

.page-header h1 {
  margin: 3px 0 0;

  font-size: 17px;

  color: #14213d;
}

.header-user {
  display: flex;
  align-items: center;

  gap: 9px;
}

.header-avatar {
  width: 34px;
  height: 34px;

  border-radius: 50%;

  background: #6c5ce7;
  color: white;

  display: flex;
  align-items: center;
  justify-content: center;

  font-size: 10px;
  font-weight: 700;
}

.header-user-info {
  display: flex;
  flex-direction: column;

  gap: 2px;
}

.header-user-info strong {
  font-size: 11px;
}

.header-user-info span {
  font-size: 9px;
  color: #9ba3b5;
}

.header-arrow {
  margin-left: 8px;

  font-size: 12px;

  color: #9299aa;
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

  box-shadow: 0 8px 18px rgba(108, 92, 231, 0.22);

  cursor: pointer;
}

.add-button span {
  font-size: 16px;

  margin-right: 7px;
}


/* =========================
   STAT CARDS
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

  box-shadow: 0 8px 25px rgba(30, 40, 80, 0.025);
}

.stat-top {
  display: flex;

  align-items: center;
  justify-content: space-between;

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

.percentage {
  font-size: 8px;

  padding: 5px 6px;

  border-radius: 5px;

  font-weight: 700;
}

.positive {
  color: #19a566;
  background: #eaf9f2;
}

.negative {
  color: #ef6565;
  background: #fff0f0;
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
   MIDDLE GRID
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

  box-shadow: 0 8px 25px rgba(30, 40, 80, 0.025);
}

.spending-panel {
  min-height: 355px;
}

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

.month-button span {
  margin-left: 10px;
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

.line-1 {
  top: 32px;
}

.line-2 {
  top: 78px;
}

.line-3 {
  top: 124px;
}

.line-4 {
  top: 170px;
}

.line-5 {
  top: 216px;
}

.chart-label {
  position: absolute;

  left: 0;

  font-size: 8px;

  color: #a0a8b9;
}

.label-1000 {
  top: 27px;
}

.label-750 {
  top: 73px;
}

.label-500 {
  top: 119px;
}

.label-250 {
  top: 165px;
}

.label-0 {
  top: 211px;
}

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

.food {
  background: #fff2dd;
}

.shopping {
  background: #f0efff;
}

.income-bg {
  background: #fff2d8;
}

.entertainment {
  background: #f2efff;
}

.transport {
  background: #fff0f0;
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
   BOTTOM GRID
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

.budget-dot.purple {
  background: #6c5ce7;
}

.budget-dot.blue {
  background: #5795ef;
}

.budget-dot.orange {
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

.progress-fill.purple {
  background: #6c5ce7;
}

.progress-fill.blue {
  background: #5795ef;
}

.progress-fill.orange {
  background: #e9a020;
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


/* =========================
   FIXED SINGLE DONUT
========================= */

.donut {
  width: 102px;
  height: 102px;

  flex-shrink: 0;

  border-radius: 50%;

  /* ONE progress ring */
  background: conic-gradient(
    #6c5ce7 0deg 244.8deg,
    #e9eaf1 244.8deg 360deg
  );

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


/* =========================
   GOAL
========================= */

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

  /*
    Hide this desktop-style page header on mobile.
    PublicLayout already provides the mobile header.
  */
  .page-header {
    display: none;
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