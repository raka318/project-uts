<template>
  <div class="page">

    <div class="page-header">
      <div>
        <span class="eyebrow">PERSONAL ACCOUNT</span>

        <h1>Budgets</h1>

        <p>
          Set spending limits and stay on track.
        </p>
      </div>

      <button class="primary-button" @click="addBudget">
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
            :style="{ width: totalPercent + '%' }"
          ></div>
        </div>

        <span>
          {{ budgets.length ? totalPercent + '% used' : '—' }}
        </span>

      </div>

    </div>


    <!-- BUDGETS -->
    <div class="section-header">

      <div>
        <h2>Your Budgets</h2>

        <p>
          Track your spending limits.
        </p>
      </div>

    </div>


    <!-- EMPTY STATE -->
    <div
      v-if="budgets.length === 0"
      class="empty-state"
    >
      <div class="empty-icon">💜</div>

      <strong>No budgets yet.</strong>

      <span>
        Create a budget to start tracking your spending limits.
      </span>
    </div>


    <!-- BUDGET GRID -->
    <div
      v-else
      class="budget-grid"
    >

      <div
        v-for="budget in budgets"
        :key="budget.id"
        class="budget-card"
      >

        <div class="budget-top">

          <div class="category-icon">
            {{ budget.icon }}
          </div>

          <button class="more-button">
            ⋮
          </button>

        </div>


        <h3>
          {{ budget.name }}
        </h3>


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
            :style="{ width: budget.percent + '%' }"
          ></div>

        </div>


        <div class="budget-footer">

          <span>
            {{ budget.percent }}% used
          </span>

          <span>
            ${{ formatNumber(budget.remaining) }} left
          </span>

        </div>

      </div>

    </div>

  </div>
</template>


<script setup>
import { computed, ref } from 'vue'


/*
 * Empty by default.
 *
 * When your teammate's backend starts returning budget data,
 * you can replace/populate this array from the API.
 */
const budgets = ref([])


const totalLimit = computed(() =>
  budgets.value.reduce(
    (total, budget) => total + Number(budget.limit || 0),
    0
  )
)


const totalSpent = computed(() =>
  budgets.value.reduce(
    (total, budget) => total + Number(budget.spent || 0),
    0
  )
)


const totalPercent = computed(() => {
  if (!totalLimit.value) return 0

  return Math.min(
    Math.round((totalSpent.value / totalLimit.value) * 100),
    100
  )
})


const formatNumber = (value) =>
  Number(value || 0).toLocaleString('en-US', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })


const addBudget = () => {

  budgets.value.push({
    id: Date.now(),
    name: 'New Budget',
    spent: 0,
    limit: 200,
    remaining: 200,
    percent: 0,
    icon: '💜'
  })

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


/* OVERVIEW */

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
}

.budget-progress > span {
  display: block;

  margin-top: 8px;

  text-align: right;
}


/* SECTION */

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


/* EMPTY */

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


/* GRID */

.budget-grid {
  display: grid;

  grid-template-columns:
    repeat(3, 1fr);

  gap: 18px;
}

.budget-card {
  padding: 22px;

  border: 1px solid #e8eaf0;

  border-radius: 13px;

  background: white;
}

.budget-top {
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

  background: transparent;

  color: #9ca3b3;

  font-size: 18px;

  cursor: pointer;
}

.budget-card h3 {
  margin: 0 0 12px;

  font-size: 13px;
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

}

</style>
