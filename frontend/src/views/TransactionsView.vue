<template>
  <div class="page">

    <!-- =========================
         PAGE HEADER
    ========================== -->
    <div class="page-header">

      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>

        <h1>Transactions</h1>

        <p>
          Track and manage all your income and expenses.
        </p>
      </div>

      <button class="primary-button" @click="showModal = true">
        <span>+</span>
        Add Transaction
      </button>

    </div>


    <!-- =========================
         SUMMARY CARDS
    ========================== -->
    <div class="summary-grid">

      <div class="summary-card">
        <div class="summary-icon purple">$</div>

        <div>
          <span>Total Balance</span>
          <strong>$12,450</strong>
        </div>
      </div>


      <div class="summary-card">
        <div class="summary-icon green">↗</div>

        <div>
          <span>Total Income</span>
          <strong>$5,200</strong>
        </div>
      </div>


      <div class="summary-card">
        <div class="summary-icon red">↘</div>

        <div>
          <span>Total Expenses</span>
          <strong>$2,840</strong>
        </div>
      </div>

    </div>


    <!-- =========================
         TRANSACTION SECTION
    ========================== -->
    <div class="transaction-card">

      <div class="card-header">

        <div>
          <h2>All Transactions</h2>

          <p>
            Your recent financial activity
          </p>
        </div>

        <div class="filters">

          <select v-model="filter">
            <option value="all">All transactions</option>
            <option value="income">Income</option>
            <option value="expense">Expenses</option>
          </select>

          <select v-model="period">
            <option>This Month</option>
            <option>Last Month</option>
            <option>This Year</option>
          </select>

        </div>

      </div>


      <!-- =========================
           TRANSACTION LIST
      ========================== -->
      <div class="transaction-list">

        <div
          v-for="transaction in filteredTransactions"
          :key="transaction.id"
          class="transaction-item"
        >

          <div
            class="transaction-icon"
            :class="transaction.type"
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


          <div class="transaction-date">
            {{ transaction.date }}
          </div>


          <div
            class="transaction-amount"
            :class="transaction.type"
          >
            {{ transaction.type === 'income' ? '+' : '-' }}${{
              transaction.amount
            }}
          </div>

        </div>

      </div>


      <div
        v-if="filteredTransactions.length === 0"
        class="empty-state"
      >
        No transactions found.
      </div>

    </div>


    <!-- =========================
         ADD TRANSACTION MODAL
    ========================== -->
    <div
      v-if="showModal"
      class="modal-overlay"
      @click.self="showModal = false"
    >

      <div class="modal">

        <button
          class="close-button"
          @click="showModal = false"
        >
          ×
        </button>

        <h2>Add Transaction</h2>

        <p>
          Add a new income or expense.
        </p>


        <div class="form-group">
          <label>Transaction name</label>

          <input
            v-model="newTransaction.name"
            placeholder="Example: Lunch"
          />
        </div>


        <div class="form-group">
          <label>Amount</label>

          <input
            v-model="newTransaction.amount"
            type="number"
            placeholder="0.00"
          />
        </div>


        <div class="form-group">
          <label>Type</label>

          <select v-model="newTransaction.type">
            <option value="expense">Expense</option>
            <option value="income">Income</option>
          </select>
        </div>


        <div class="modal-actions">

          <button
            class="cancel-button"
            @click="showModal = false"
          >
            Cancel
          </button>

          <button
            class="primary-button"
            @click="addTransaction"
          >
            Add Transaction
          </button>

        </div>

      </div>

    </div>

  </div>
</template>


<script setup>
import { computed, ref } from 'vue'


const filter = ref('all')
const period = ref('This Month')

const showModal = ref(false)


const transactions = ref([
  {
    id: 1,
    name: 'Lunch',
    category: 'Food & Dining',
    amount: '42.50',
    type: 'expense',
    date: 'Today',
    icon: '🍔'
  },
  {
    id: 2,
    name: 'Shopping',
    category: 'Shopping',
    amount: '125.00',
    type: 'expense',
    date: 'Yesterday',
    icon: '🛍️'
  },
  {
    id: 3,
    name: 'Salary',
    category: 'Income',
    amount: '4,500.00',
    type: 'income',
    date: 'Aug 30',
    icon: '💰'
  },
  {
    id: 4,
    name: 'Netflix',
    category: 'Entertainment',
    amount: '15.99',
    type: 'expense',
    date: 'Aug 29',
    icon: '🎬'
  },
  {
    id: 5,
    name: 'Gas',
    category: 'Transportation',
    amount: '55.00',
    type: 'expense',
    date: 'Aug 28',
    icon: '⛽'
  },
  {
    id: 6,
    name: 'Freelance',
    category: 'Income',
    amount: '850.00',
    type: 'income',
    date: 'Aug 27',
    icon: '💼'
  }
])


const newTransaction = ref({
  name: '',
  amount: '',
  type: 'expense'
})


const filteredTransactions = computed(() => {

  if (filter.value === 'all') {
    return transactions.value
  }

  return transactions.value.filter(
    transaction => transaction.type === filter.value
  )

})


const addTransaction = () => {

  if (!newTransaction.value.name ||
      !newTransaction.value.amount) {
    return
  }


  transactions.value.unshift({

    id: Date.now(),

    name: newTransaction.value.name,

    category:
      newTransaction.value.type === 'income'
        ? 'Income'
        : 'General',

    amount:
      Number(newTransaction.value.amount).toFixed(2),

    type: newTransaction.value.type,

    date: 'Just now',

    icon:
      newTransaction.value.type === 'income'
        ? '💰'
        : '💳'

  })


  newTransaction.value = {
    name: '',
    amount: '',
    type: 'expense'
  }


  showModal.value = false
}
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
  padding: 50px;

  text-align: center;

  color: #999;
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

</style>